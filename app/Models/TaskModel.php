<?php
namespace App\Models;
use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['title', 'status', 'task_date', 'created_at', 'is_archived'];

    public function forDate(string $date): array
    {
        return $this->where('is_archived', 0)->where('task_date', $date)
            ->orderBy('id', 'ASC')->findAll();
    }
    public function allByDate(): array
    {
        return $this->where('is_archived', 0)->orderBy('task_date', 'ASC')
            ->orderBy('id', 'ASC')->findAll();
    }
    public function activeTask(int $id): ?array
    {
        return $this->where('is_archived', 0)->where('id', $id)->first();
    }
    public function archiveTask(int $id): bool
    {
        // Keep the original row; do not call delete().
        return $this->update($id, ['is_archived' => 1]);
    }
}
