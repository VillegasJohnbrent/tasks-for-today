<?php
namespace App\Controllers;
use App\Models\TaskModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class TaskManager extends BaseController
{
    protected $helpers = ['url', 'form'];
    private array $rules = [
        'title' => ['label' => 'Title', 'rules' => 'required|max_length[150]'],
        'task_date' => ['label' => 'Task date', 'rules' => 'required|valid_date[Y-m-d]'],
        'status' => ['label' => 'Status', 'rules' => 'required|in_list[pending,in progress,completed]'],
    ];

    public function new(): string
    {
        return $this->form(['title' => '', 'task_date' => date('Y-m-d'), 'status' => 'pending']);
    }

    public function create()
    {
        $data = $this->submittedData();
        if (! $this->validateData($data, $this->rules)) {
            $this->response->setStatusCode(422);
            return $this->form($this->displayData($data), $this->validator->getErrors());
        }
        // Only these fields can be supplied by the form.
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['is_archived'] = 0;
        if ((new TaskModel())->insert($data) === false) {
            throw new \RuntimeException('The task could not be saved.');
        }
        return redirect()->to(site_url('tasks'))->with('success', 'Task created.');
    }

    public function edit(int $id): string
    {
        return $this->form($this->findTask($id));
    }

    public function update(int $id)
    {
        $this->findTask($id);
        $data = $this->submittedData();
        if (! $this->validateData($data, $this->rules)) {
            $this->response->setStatusCode(422);
            return $this->form(['id' => $id] + $this->displayData($data), $this->validator->getErrors());
        }
        if (! (new TaskModel())->update($id, $data)) {
            throw new \RuntimeException('The task could not be updated.');
        }
        return redirect()->to(site_url('tasks'))->with('success', 'Task updated.');
    }

    public function archive(int $id)
    {
        $this->findTask($id);
        if (! (new TaskModel())->archiveTask($id)) {
            throw new \RuntimeException('The task could not be archived.');
        }
        return redirect()->to(site_url('tasks'))->with('success', 'Task deleted from the list and archived in the database.');
    }

    private function findTask(int $id): array
    {
        $task = (new TaskModel())->activeTask($id);
        if ($task === null) {
            throw PageNotFoundException::forPageNotFound('Task not found.');
        }
        return $task;
    }

    private function submittedData(): array
    {
        $data = $this->request->getPost(['title', 'status', 'task_date']);
        foreach ($data as $field => $value) {
            if (is_string($value)) {
                $data[$field] = trim($value);
            }
        }
        return $data;
    }

    private function displayData(array $data): array
    {
        // Invalid array inputs must not be rendered into text fields.
        foreach ($data as $field => $value) {
            $data[$field] = is_string($value) ? $value : '';
        }
        return $data;
    }

    private function form(array $task, array $errors = []): string
    {
        return view('task_form', [
            'title' => isset($task['id']) ? 'Edit Task' : 'New Task',
            'active' => 'tasks', 'task' => $task, 'errors' => $errors,
        ]);
    }
}
