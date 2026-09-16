<?php
class ReportStatusHistory extends Model {
    protected string $table = 'report_status_history';

    public function getByReport(int $reportId): array {
        return $this->query(
            "SELECT h.*, p.full_name AS changed_by_name, u.role AS changed_by_role
             FROM report_status_history h
             LEFT JOIN users u ON u.id = h.changed_by
             LEFT JOIN profiles p ON p.user_id = h.changed_by
             WHERE h.report_id = ?
             ORDER BY h.created_at DESC",
            [$reportId]
        );
    }

    public function addEntry(int $reportId, string $status, ?string $notes = null, ?int $changedBy = null): void {
        $this->create([
            'report_id'  => $reportId,
            'status'     => $status,
            'notes'      => $notes,
            'changed_by' => $changedBy ?? currentUserId(),
        ]);
    }
}
