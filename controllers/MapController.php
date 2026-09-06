<?php

class MapController extends Controller {
    private $reportModel;
    private $regionModel;
    private $cleanupLocationModel;
    private $notifModel;

    public function __construct() {
        parent::__construct();
        $this->reportModel = new Report();
        $this->regionModel = new Region();
        $this->cleanupLocationModel = new CleanupLocation();
        $this->notifModel = new Notification();
    }

    public function index() {
        $regions = $this->regionModel->getAll();
        $reports = $this->reportModel->getForMap();
        $cleanupLocations = $this->cleanupLocationModel->getActiveLocations();

        $this->view('home.map', [
            'pageTitle' => 'Peta Sebaran Laporan Lingkungan - ' . APP_NAME,
            'regions'   => $regions,
            'reports'   => $reports,
            'cleanupLocations' => $cleanupLocations,
        ]);
    }

    public function getData() {
        $reports = $this->reportModel->getForMap();

        $data = array_map(function($r) {
            return [
                'id'            => (int)$r['id'],
                'title'         => $r['title'],
                'status'        => $r['status'],
                'status_label'  => statusLabel($r['status']),
                'category'      => $r['category_name'] ?? 'Umum',
                'category_color'=> $r['category_color'] ?? '#1D4533',
                'region'        => $r['region_name'] ?? 'JABODETABEK',
                'location_name' => $r['location_name'] ?? '',
                'lat'           => (float)$r['latitude'],
                'lng'           => (float)$r['longitude'],
                'url'           => url('reports/' . $r['id']),
            ];
        }, $reports);

        $this->json(['success' => true, 'data' => $data]);
    }

    public function getCleanupLocations() {
        $locations = $this->cleanupLocationModel->getActiveLocations();

        $data = array_map(function($location) {
            return [
                'id' => (int)$location['id'],
                'address' => $location['address'],
                'latitude' => (float)$location['latitude'],
                'longitude' => (float)$location['longitude'],
                'status' => $location['status'],
                'statusLabel' => CleanupLocation::getStatusLabel($location['status']),
                'statusColor' => CleanupLocation::getStatusColor($location['status']),
                'description' => $location['description'] ?? '',
                'created_at' => $location['created_at']
            ];
        }, $locations);

        $this->json(['success' => true, 'data' => $data]);
    }

    public function getNotificationCount(): void {
        if (!isLoggedIn()) {
            $this->json(['success' => true, 'count' => 0]);
            return;
        }

        $userId = (int)currentUserId();
        $count  = $this->notifModel->getUnreadCount($userId);
        $this->json(['success' => true, 'count' => $count]);
    }

    public function reverseGeocode(): void {
        try {
            $lat = null;
            $lng = null;

            $jsonInput = json_decode(file_get_contents('php://input'), true);
            if (is_array($jsonInput)) {
                $lat = $jsonInput['latitude'] ?? $jsonInput['lat'] ?? null;
                $lng = $jsonInput['longitude'] ?? $jsonInput['lng'] ?? null;
            }

            if ($lat === null && isset($_POST['latitude'])) $lat = $_POST['latitude'];
            if ($lat === null && isset($_POST['lat'])) $lat = $_POST['lat'];
            if ($lng === null && isset($_POST['longitude'])) $lng = $_POST['longitude'];
            if ($lng === null && isset($_POST['lng'])) $lng = $_POST['lng'];

            if ($lat === null && isset($_GET['latitude'])) $lat = $_GET['latitude'];
            if ($lat === null && isset($_GET['lat'])) $lat = $_GET['lat'];
            if ($lng === null && isset($_GET['longitude'])) $lng = $_GET['longitude'];
            if ($lng === null && isset($_GET['lng'])) $lng = $_GET['lng'];

            if ($lat === null || $lng === null || !is_numeric($lat) || !is_numeric($lng)) {
                $this->json([
                    'success' => false,
                    'message' => 'Koordinat latitude dan longitude tidak valid atau belum diisi.'
                ], 400);
                return;
            }

            $lat = (float)$lat;
            $lng = (float)$lng;

            if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
                $this->json([
                    'success' => false,
                    'message' => 'Rentang koordinat latitude/longitude di luar batas geografis.'
                ], 400);
                return;
            }

            $geoResult = $this->fetchReverseGeocodeProviders($lat, $lng);

            if (!$geoResult || empty($geoResult['address'])) {
                $this->json([
                    'success' => false,
                    'message' => 'Alamat tidak dapat ditemukan dari koordinat tersebut.'
                ], 404);
                return;
            }

            $allRegions = $this->regionModel->getAll();
            $matchedRegion = $this->matchRegionFromGeoData($allRegions, $geoResult);

            $this->json([
                'success'       => true,
                'address'       => $geoResult['address'],
                'region_id'     => $matchedRegion ? (int)$matchedRegion['id'] : null,
                'region_name'   => $matchedRegion ? $matchedRegion['name'] . (!empty($matchedRegion['parent_name']) ? ' (' . $matchedRegion['parent_name'] . ')' : '') : null,
                'location_name' => $geoResult['location_name'] ?? '',
                'latitude'      => $lat,
                'longitude'     => $lng,
                'details'       => $geoResult['details'] ?? []
            ]);
        } catch (\Throwable $e) {
            logError('reverseGeocode error: ' . $e->getMessage());
            $this->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses titik lokasi: ' . $e->getMessage()
            ], 500);
        }
    }

    private function fetchReverseGeocodeProviders(float $lat, float $lng): ?array {
        $osmUrl = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$lat}&lon={$lng}&addressdetails=1&accept-language=id";
        $osmRes = $this->curlGet($osmUrl, 'JagantaraApp/2.0 (info@jagantara.id)');
        if ($osmRes) {
            $osmData = json_decode($osmRes, true);
            if (!empty($osmData['address']) || !empty($osmData['display_name'])) {
                $addrDetails = $osmData['address'] ?? [];
                $formatted = $this->formatIndonesianAddress($osmData['display_name'] ?? '', $addrDetails);
                $locName = $osmData['name'] ?? $addrDetails['road'] ?? '';

                return [
                    'address'       => $formatted,
                    'location_name' => $locName,
                    'details'       => $addrDetails,
                    'raw'           => $osmData
                ];
            }
        }

        $arcUrl = "https://geocode.arcgis.com/arcgis/rest/services/World/GeocodeServer/reverseGeocode?f=json&location={$lng},{$lat}";
        $arcRes = $this->curlGet($arcUrl, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) JagantaraApp/2.0');
        if ($arcRes) {
            $arcData = json_decode($arcRes, true);
            if (!empty($arcData['address']['Match_addr'])) {
                $match = $arcData['address']['Match_addr'];
                $locName = $arcData['address']['Address'] ?? $arcData['address']['ShortLabel'] ?? '';
                return [
                    'address'       => $match,
                    'location_name' => $locName,
                    'details'       => $arcData['address'],
                    'raw'           => $arcData
                ];
            }
        }

        $bdcUrl = "https://api.bigdatacloud.net/data/reverse-geocode-client?latitude={$lat}&longitude={$lng}&localityLanguage=id";
        $bdcRes = $this->curlGet($bdcUrl, 'Mozilla/5.0 JagantaraApp/2.0');
        if ($bdcRes) {
            $bdcData = json_decode($bdcRes, true);
            $parts = array_filter([
                $bdcData['locality'] ?? '',
                $bdcData['city'] ?? '',
                $bdcData['principalSubdivision'] ?? '',
                $bdcData['postcode'] ?? ''
            ]);
            if (!empty($parts)) {
                return [
                    'address'       => implode(', ', $parts),
                    'location_name' => $bdcData['locality'] ?? '',
                    'details'       => $bdcData,
                    'raw'           => $bdcData
                ];
            }
        }

        return null;
    }

    private function formatIndonesianAddress(string $displayName, array $details): string {
        $parts = [];

        $street = trim(($details['road'] ?? '') . (!empty($details['house_number']) ? ' No. ' . $details['house_number'] : ''));
        if (!empty($street)) $parts[] = $street;

        $kel = $details['village'] ?? $details['suburb'] ?? $details['neighbourhood'] ?? '';
        if (!empty($kel) && !in_array(strtolower($kel), array_map('strtolower', $parts), true)) {
            $parts[] = $kel;
        }

        $kec = $details['city_district'] ?? $details['district'] ?? '';
        if (!empty($kec) && !in_array(strtolower($kec), array_map('strtolower', $parts), true)) {
            $parts[] = 'Kec. ' . $kec;
        }

        $city = $details['city'] ?? $details['county'] ?? $details['municipality'] ?? '';
        if (!empty($city) && !in_array(strtolower($city), array_map('strtolower', $parts), true)) {
            $parts[] = $city;
        }

        $state = $details['state'] ?? '';
        if (!empty($state) && !in_array(strtolower($state), array_map('strtolower', $parts), true)) {
            $parts[] = $state;
        }

        if (!empty($details['postcode'])) {
            $parts[] = $details['postcode'];
        }

        if (count($parts) >= 2) {
            return implode(', ', $parts);
        }

        $clean = preg_replace('/, Indonesia$/i', '', trim($displayName));
        return $clean ?: $displayName;
    }

    private function matchRegionFromGeoData(array $regions, array $geoResult): ?array {
        $details = $geoResult['details'] ?? [];
        $textToSearch = strtolower(implode(' ', [
            $geoResult['address'] ?? '',
            $details['display_name'] ?? '',
            $details['road'] ?? '',
            $details['suburb'] ?? '',
            $details['village'] ?? '',
            $details['city_district'] ?? '',
            $details['district'] ?? '',
            $details['city'] ?? '',
            $details['county'] ?? '',
            $details['Subregion'] ?? '',
            $details['Neighborhood'] ?? '',
            $details['state_district'] ?? '',
        ]));

        $cleanText = preg_replace('/\b(kota|kabupaten|kab\.|kecamatan|kec\.|kelurahan|kel\.)\b/i', ' ', $textToSearch);

        $children = array_filter($regions, fn($r) => !empty($r['parent_id']));
        foreach ($children as $reg) {
            $regName = strtolower(trim($reg['name']));
            if (preg_match('/\b' . preg_quote($regName, '/') . '\b/i', $cleanText)) {
                return $reg;
            }
        }

        foreach ($children as $reg) {
            $regName = strtolower(trim($reg['name']));
            if (str_contains($cleanText, $regName)) {
                return $reg;
            }
        }

        $parents = array_filter($regions, fn($r) => empty($r['parent_id']));
        foreach ($parents as $parent) {
            $pName = strtolower(trim($parent['name']));
            if (preg_match('/\b' . preg_quote($pName, '/') . '\b/i', $cleanText)) {
                return $parent;
            }
        }

        return null;
    }

    private function curlGet(string $url, string $userAgent = 'JagantaraApp/2.0'): ?string {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_TIMEOUT, 6);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $res = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($res && $httpCode >= 200 && $httpCode < 300) {
            return $res;
        }

        return null;
    }
}
