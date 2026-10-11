<?php
namespace App\Controllers;

use App\Models\TaskModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    protected $helpers = ['url', 'form'];
    public function index(): string
    {
        $today = date('Y-m-d');
        $tasks = (new TaskModel())->forDate($today);
        return view('welcome', [
            'title' => 'Welcome', 'active' => 'welcome',
            'today' => $today, 'tasks' => $tasks,
            'completed' => count(array_filter($tasks, static fn ($task) => $task['status'] === 'completed')),
        ]);
    }

    public function tasks(): string
    {
        return view('tasks', [
            'title' => 'Task List', 'active' => 'tasks',
            'tasks' => (new TaskModel())->allByDate(),
        ]);
    }

    public function profile(): string
    {
        return view('profile', [
            'title' => 'Profile', 'active' => 'profile',
            'user' => (new UserModel())->demoUser(),
        ]);
    }

    public function about(): string
    {
        return view('about', [
            'title' => 'About', 'active' => 'about',
            'developer' => env('developer.name', 'John'),
            'section' => env('developer.section', 'IT0049'),
        ]);
    }
}
