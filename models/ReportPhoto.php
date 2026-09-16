<?php
class ReportPhoto extends Model {
    protected string $table = 'report_photos';

    public function getByReport(int $reportId): array {
        return $this->query(
            'SELECT * FROM report_photos WHERE report_id = ? ORDER BY `order` ASC, id ASC',
            [$reportId]
        );
    }

    public function countByReport(int $reportId): int {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM report_photos WHERE report_id = ?');
        $stmt->execute([$reportId]);
        return (int)$stmt->fetchColumn();
    }

    public function setPrimary(int $reportId, int $photoId): void {
        $this->execute('UPDATE report_photos SET is_primary = 0 WHERE report_id = ?', [$reportId]);
        $this->execute('UPDATE report_photos SET is_primary = 1 WHERE id = ? AND report_id = ?', [$photoId, $reportId]);
    }

    public function addPhotos(int $reportId, array $filePaths): void {

        $maxPhotos = (int)setting('max_report_photos', (string)MAX_REPORT_PHOTOS);
        if ($maxPhotos < 1) $maxPhotos = 10;
        $existing = $this->countByReport($reportId);
        $allowed = $maxPhotos - $existing;
        if ($allowed <= 0) return;

        $filePaths = array_slice($filePaths, 0, $allowed);

        foreach ($filePaths as $index => $path) {
            $isPrimary = ($existing === 0 && $index === 0) ? 1 : 0;
            $this->create([
                'report_id'  => $reportId,
                'file_path'  => $path,
                'is_primary' => $isPrimary,
                'order'      => $existing + $index,
            ]);
        }
    }

    public function deleteByReport(int $reportId): array {
        $photos = $this->getByReport($reportId);
        $this->execute('DELETE FROM report_photos WHERE report_id = ?', [$reportId]);
        return $photos;
    }
}
