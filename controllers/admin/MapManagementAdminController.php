<?php

class MapManagementAdminController extends Controller {
    private CleanupLocation $cleanupLocationModel;

    public function __construct() {
        parent::__construct();
        $this->cleanupLocationModel = new CleanupLocation();
    }

    public function index(): void {
        $this->requireRole(ROLE_ADMIN);

        $filters = [
            'status' => (string)$this->query('status', ''),
            'search' => trim((string)$this->query('search', ''))
        ];

        $locations = $this->cleanupLocationModel->getAll($filters);
        $statistics = $this->cleanupLocationModel->getStatistics();
        $statusOptions = CleanupLocation::getStatusOptions();

        $this->view('admin.map_management.index', [
            'pageTitle'     => 'Manajemen Peta Lokasi - ' . APP_NAME,
            'adminTitle'    => 'Manajemen Peta Lokasi',
            'locations'     => $locations,
            'statistics'    => $statistics,
            'statusOptions' => $statusOptions,
            'filters'       => $filters
        ], 'admin');
    }

    public function create(): void {
        $this->requireRole(ROLE_ADMIN);

        $statusOptions = CleanupLocation::getStatusOptions();

        $this->view('admin.map_management.create', [
            'pageTitle'     => 'Tambah Lokasi Pembersihan - ' . APP_NAME,
            'adminTitle'    => 'Tambah Lokasi Pembersihan',
            'statusOptions' => $statusOptions,
            'needsMap'      => true
        ], 'admin');
    }

    public function store(): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();

        $address = trim((string)$this->input('address', ''));
        $latitude = $this->input('latitude', null);
        $longitude = $this->input('longitude', null);
        $status = (string)$this->input('status', CleanupLocation::STATUS_PENDING);
        $description = trim((string)$this->input('description', ''));

        if (empty($address)) {
            setFlash('error', 'Alamat wajib diisi.');
            $this->redirect('admin/map-management/create');
        }

        $lat = !empty($latitude) && is_numeric($latitude) ? (float)$latitude : null;
        $lng = !empty($longitude) && is_numeric($longitude) ? (float)$longitude : null;

        if ($lat === null || $lng === null) {
            $coords = CleanupLocation::geocodeAddress($address);
            if ($coords) {
                $lat = (float)$coords['latitude'];
                $lng = (float)$coords['longitude'];
            }
        }

        $data = [
            'address'     => $address,
            'latitude'    => $lat,
            'longitude'   => $lng,
            'status'      => in_array($status, array_keys(CleanupLocation::getStatusOptions())) ? $status : CleanupLocation::STATUS_PENDING,
            'description' => $description ?: null,
            'admin_id'    => (int)currentUserId()
        ];

        try {
            $newId = $this->cleanupLocationModel->create($data);

            $this->audit()->logAdminAction(
                'create_cleanup_location',
                'cleanup_locations',
                $newId,
                ['address' => $data['address'], 'status' => $data['status']]
            );

            setFlash('success', 'Lokasi pembersihan berhasil ditambahkan.');
            $this->redirect('admin/map-management');
        } catch (\Exception $e) {
            setFlash('error', 'Gagal menambahkan lokasi: ' . $e->getMessage());
            $this->redirect('admin/map-management/create');
        }
    }

    public function edit(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $id = (int)$id;

        $location = $this->cleanupLocationModel->getById($id);
        if (!$location) {
            $this->abort(404, 'Lokasi tidak ditemukan.');
        }

        $statusOptions = CleanupLocation::getStatusOptions();

        $this->view('admin.map_management.edit', [
            'pageTitle'     => 'Edit Lokasi Pembersihan - ' . APP_NAME,
            'adminTitle'    => 'Edit Lokasi Pembersihan',
            'location'      => $location,
            'statusOptions' => $statusOptions,
            'needsMap'      => true
        ], 'admin');
    }

    public function update(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $location = $this->cleanupLocationModel->getById($id);
        if (!$location) {
            $this->abort(404, 'Lokasi tidak ditemukan.');
        }

        $address = trim((string)$this->input('address', ''));
        $latitude = $this->input('latitude', null);
        $longitude = $this->input('longitude', null);
        $status = (string)$this->input('status', CleanupLocation::STATUS_PENDING);
        $description = trim((string)$this->input('description', ''));

        if (empty($address)) {
            setFlash('error', 'Alamat wajib diisi.');
            $this->redirect('admin/map-management/edit/' . $id);
        }

        $lat = !empty($latitude) && is_numeric($latitude) ? (float)$latitude : null;
        $lng = !empty($longitude) && is_numeric($longitude) ? (float)$longitude : null;

        if (($lat === null || $lng === null) || $address !== $location['address']) {
            if ($lat === null || $lng === null) {
                $coords = CleanupLocation::geocodeAddress($address);
                if ($coords) {
                    $lat = (float)$coords['latitude'];
                    $lng = (float)$coords['longitude'];
                }
            }
        }

        $data = [
            'address'     => $address,
            'latitude'    => $lat,
            'longitude'   => $lng,
            'status'      => in_array($status, array_keys(CleanupLocation::getStatusOptions())) ? $status : $location['status'],
            'description' => $description ?: null
        ];

        try {
            $this->cleanupLocationModel->update($id, $data);

            $this->audit()->logAdminAction(
                'update_cleanup_location',
                'cleanup_locations',
                $id,
                ['address' => $data['address'], 'status' => $data['status']]
            );

            setFlash('success', 'Lokasi pembersihan berhasil diperbarui.');
            $this->redirect('admin/map-management');
        } catch (\Exception $e) {
            setFlash('error', 'Gagal memperbarui lokasi: ' . $e->getMessage());
            $this->redirect('admin/map-management/edit/' . $id);
        }
    }

    public function updateStatus(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $id = (int)$id;

        $location = $this->cleanupLocationModel->getById($id);
        if (!$location) {
            $this->json(['success' => false, 'message' => 'Lokasi tidak ditemukan'], 404);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $status = $data['status'] ?? null;

        if (!in_array($status, [
            CleanupLocation::STATUS_PENDING,
            CleanupLocation::STATUS_VERIFIED,
            CleanupLocation::STATUS_IN_PROGRESS,
            CleanupLocation::STATUS_COMPLETED
        ], true)) {
            $this->json(['success' => false, 'message' => 'Status tidak valid'], 400);
        }

        try {
            $this->cleanupLocationModel->updateStatus($id, $status);

            $this->audit()->logAdminAction(
                'change_cleanup_location_status',
                'cleanup_locations',
                $id,
                ['from' => $location['status'], 'to' => $status]
            );

            $this->json([
                'success'     => true,
                'message'     => 'Status berhasil diperbarui',
                'status'      => $status,
                'statusLabel' => CleanupLocation::getStatusLabel($status),
                'statusColor' => CleanupLocation::getStatusColor($status)
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Gagal memperbarui status: ' . $e->getMessage()], 500);
        }
    }

    public function delete(string|int $id): void {
        $this->requireRole(ROLE_ADMIN);
        $this->validateCsrf();
        $id = (int)$id;

        $location = $this->cleanupLocationModel->getById($id);
        if (!$location) {
            $this->abort(404, 'Lokasi tidak ditemukan.');
        }

        try {
            $this->cleanupLocationModel->delete($id);

            $this->audit()->logAdminAction(
                'delete_cleanup_location',
                'cleanup_locations',
                $id,
                ['address' => $location['address']]
            );

            setFlash('success', 'Lokasi pembersihan berhasil dihapus.');
        } catch (\Exception $e) {
            setFlash('error', 'Gagal menghapus lokasi: ' . $e->getMessage());
        }

        $this->redirect('admin/map-management');
    }

    public function getLocationsJson(): void {
        $this->requireRole(ROLE_ADMIN);

        $locations = $this->cleanupLocationModel->getActiveLocations();

        $output = array_map(function($location) {
            return [
                'id'          => (int)$location['id'],
                'address'     => $location['address'],
                'latitude'    => $location['latitude'] !== null ? (float)$location['latitude'] : null,
                'longitude'   => $location['longitude'] !== null ? (float)$location['longitude'] : null,
                'status'      => $location['status'],
                'statusLabel' => CleanupLocation::getStatusLabel($location['status']),
                'statusColor' => CleanupLocation::getStatusColor($location['status']),
                'description' => $location['description'] ?? '',
                'created_at'  => $location['created_at']
            ];
        }, $locations);

        $this->json($output);
    }

    public function geocode(): void {
        $this->requireRole(ROLE_ADMIN);

        $data = json_decode(file_get_contents('php://input'), true);
        $address = trim($data['address'] ?? '');

        if (empty($address)) {
            $this->json(['success' => false, 'message' => 'Alamat harus diisi'], 400);
        }

        $coords = CleanupLocation::geocodeAddress($address);

        if ($coords) {
            $this->json([
                'success'      => true,
                'latitude'     => (float)$coords['latitude'],
                'longitude'    => (float)$coords['longitude'],
                'display_name' => $coords['display_name'] ?? $address,
                'results'      => $coords['results'] ?? []
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Alamat tidak sesuai atau tidak ditemukan di peta'], 404);
        }
    }

    public function reverseGeocode(): void {
        $this->requireRole(ROLE_ADMIN);

        $data = json_decode(file_get_contents('php://input'), true);
        $lat = isset($data['latitude']) ? (float)$data['latitude'] : null;
        $lng = isset($data['longitude']) ? (float)$data['longitude'] : null;

        if ($lat === null || $lng === null) {
            $this->json(['success' => false, 'message' => 'Koordinat latitude dan longitude wajib diisi'], 400);
        }

        $address = CleanupLocation::reverseGeocode($lat, $lng);

        if ($address) {
            $this->json([
                'success' => true,
                'address' => $address
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Gagal mendapatkan alamat dari titik koordinat'], 404);
        }
    }
}
