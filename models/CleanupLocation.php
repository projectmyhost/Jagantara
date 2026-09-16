<?php

class CleanupLocation extends Model {
    protected string $table = 'cleanup_locations';

    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';

    public static function getStatusColor($status) {
        $colors = [
            self::STATUS_PENDING => '#FFC107',
            self::STATUS_VERIFIED => '#2196F3',
            self::STATUS_IN_PROGRESS => '#FF9800',
            self::STATUS_COMPLETED => '#4CAF50'
        ];
        return $colors[$status] ?? '#9E9E9E';
    }

    public static function getStatusLabel($status) {
        $labels = [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_VERIFIED => 'Terverifikasi',
            self::STATUS_IN_PROGRESS => 'Ditangani',
            self::STATUS_COMPLETED => 'Selesai Ditangani'
        ];
        return $labels[$status] ?? 'Unknown';
    }

    public static function getStatusOptions() {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_VERIFIED => 'Terverifikasi',
            self::STATUS_IN_PROGRESS => 'Ditangani',
            self::STATUS_COMPLETED => 'Selesai Ditangani'
        ];
    }

    public function getAll(array $filters = []): array {
        $sql = "SELECT cl.*, p.username as admin_name
                FROM {$this->table} cl
                LEFT JOIN profiles p ON cl.admin_id = p.user_id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND cl.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND cl.address LIKE ?";
            $params[] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY cl.created_at DESC";

        return $this->query($sql, $params);
    }

    public function getById(int $id): ?array {
        $sql = "SELECT cl.*, p.username as admin_name
                FROM {$this->table} cl
                LEFT JOIN profiles p ON cl.admin_id = p.user_id
                WHERE cl.id = ?";
        $result = $this->query($sql, [$id]);
        return $result[0] ?? null;
    }

    public function create(array $data): int {
        $sql = "INSERT INTO {$this->table} (address, latitude, longitude, status, description, admin_id)
                VALUES (?, ?, ?, ?, ?, ?)";

        $params = [
            $data['address'],
            $data['latitude'] ?? null,
            $data['longitude'] ?? null,
            $data['status'] ?? self::STATUS_PENDING,
            $data['description'] ?? null,
            $data['admin_id']
        ];

        $this->execute($sql, $params);
        return $this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE {$this->table}
                SET address = ?, latitude = ?, longitude = ?, status = ?, description = ?
                WHERE id = ?";

        $params = [
            $data['address'],
            $data['latitude'] ?? null,
            $data['longitude'] ?? null,
            $data['status'],
            $data['description'] ?? null,
            $id
        ];

        return $this->execute($sql, $params);
    }

    public function updateStatus(int $id, string $status): bool {
        $sql = "UPDATE {$this->table} SET status = ? WHERE id = ?";
        return $this->execute($sql, [$status, $id]);
    }

    public function delete(int $id): bool {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->execute($sql, [$id]);
    }

    public function getActiveLocations(): array {
        $sql = "SELECT id, address, latitude, longitude, status, description, created_at
                FROM {$this->table}
                ORDER BY created_at DESC";
        return $this->query($sql);
    }

    public function getStatistics(): array {
        $sql = "SELECT
                    status,
                    COUNT(*) as count
                FROM {$this->table}
                GROUP BY status";
        $results = $this->query($sql);

        $stats = [
            self::STATUS_PENDING => 0,
            self::STATUS_VERIFIED => 0,
            self::STATUS_IN_PROGRESS => 0,
            self::STATUS_COMPLETED => 0
        ];

        foreach ($results as $row) {
            $stats[$row['status']] = (int)$row['count'];
        }

        return $stats;
    }

    public static function geocodeAddress(string $address): ?array {
        $cleanAddress = trim($address);
        if (empty($cleanAddress)) return null;

        $urlArcgis = 'https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/findAddressCandidates?f=json&outFields=Match_addr,Addr_type&maxLocations=5&singleLine=' . urlencode($cleanAddress);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlArcgis);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) JagantaraApp/2.0');
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $resArcgis = curl_exec($ch);
        curl_close($ch);

        if ($resArcgis) {
            $arcData = json_decode($resArcgis, true);
            if (!empty($arcData['candidates'][0]['location'])) {
                $cand = $arcData['candidates'][0];
                return [
                    'latitude'     => (string)$cand['location']['y'],
                    'longitude'    => (string)$cand['location']['x'],
                    'display_name' => $cand['address'] ?? $cleanAddress,
                    'source'       => 'arcgis',
                    'results'      => $arcData['candidates']
                ];
            }
        }

        $urlOsm = 'https://nominatim.openstreetmap.org/search?format=json&limit=5&countrycodes=id&addressdetails=1&q=' . urlencode($cleanAddress);
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $urlOsm);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) JagantaraApp/2.0');
        curl_setopt($ch2, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
        $resOsm = curl_exec($ch2);
        curl_close($ch2);

        if ($resOsm) {
            $dataOsm = json_decode($resOsm, true);
            if (!empty($dataOsm) && isset($dataOsm[0]['lat'], $dataOsm[0]['lon'])) {
                return [
                    'latitude'     => $dataOsm[0]['lat'],
                    'longitude'    => $dataOsm[0]['lon'],
                    'display_name' => $dataOsm[0]['display_name'] ?? $cleanAddress,
                    'source'       => 'nominatim',
                    'results'      => $dataOsm
                ];
            }
        }

        $simplifiedAddress = preg_replace('/\b(rt|rw|no|blok|gang|gg)[\.\s]*\d+\b/i', '', $cleanAddress);
        $simplifiedAddress = trim(preg_replace('/\s+/', ' ', $simplifiedAddress));

        if (!empty($simplifiedAddress) && $simplifiedAddress !== $cleanAddress) {
            $urlPhoton = 'https://photon.komoot.io/api/?limit=3&q=' . urlencode($simplifiedAddress . ' Indonesia');
            $ch3 = curl_init();
            curl_setopt($ch3, CURLOPT_URL, $urlPhoton);
            curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch3, CURLOPT_USERAGENT, 'JagantaraApp/2.0');
            curl_setopt($ch3, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch3, CURLOPT_SSL_VERIFYHOST, false);
            $resPhoton = curl_exec($ch3);
            curl_close($ch3);

            if ($resPhoton) {
                $photonData = json_decode($resPhoton, true);
                if (!empty($photonData['features'][0]['geometry']['coordinates'])) {
                    $coords = $photonData['features'][0]['geometry']['coordinates'];
                    $props = $photonData['features'][0]['properties'] ?? [];
                    $nameParts = array_filter([$props['name'] ?? '', $props['street'] ?? '', $props['city'] ?? '', $props['state'] ?? '']);
                    return [
                        'latitude'     => (string)$coords[1],
                        'longitude'    => (string)$coords[0],
                        'display_name' => !empty($nameParts) ? implode(', ', $nameParts) : $cleanAddress,
                        'source'       => 'photon',
                        'results'      => $photonData['features']
                    ];
                }
            }
        }

        return null;
    }

    public static function reverseGeocode(float $lat, float $lng): ?string {

        $urlArc = "https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=json&location={$lng},{$lat}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $urlArc);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) JagantaraApp/2.0');
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $resArc = curl_exec($ch);
        curl_close($ch);

        if ($resArc) {
            $arcData = json_decode($resArc, true);
            if (!empty($arcData['address']['Match_addr'])) {
                return $arcData['address']['Match_addr'];
            }
        }

        $urlOsm = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}&addressdetails=1";
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, $urlOsm);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_USERAGENT, 'JagantaraApp/2.0');
        curl_setopt($ch2, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
        $resOsm = curl_exec($ch2);
        curl_close($ch2);

        if ($resOsm) {
            $osmData = json_decode($resOsm, true);
            return $osmData['display_name'] ?? null;
        }

        return null;
    }
}
