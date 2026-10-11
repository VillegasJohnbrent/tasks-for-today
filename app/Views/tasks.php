<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">THE COMPLETE PICTURE</p><h1>Task List</h1><p class="muted">Every active task, ordered by scheduled date from earliest to latest.</p></div><span class="date-chip"><?= count($tasks) ?> tasks</span></div>
<section class="card">
    <div class="card-heading"><h2>All scheduled tasks</h2><?php if (session()->get('logged_in') === true): ?><a class="primary" href="<?= site_url('tasks/new') ?>">+ New Task</a><?php else: ?><a class="button" href="<?= site_url('login') ?>">Log in to manage tasks →</a><?php endif ?></div>
    <?= view('partials/task_table', ['tasks' => $tasks, 'emptyMessage' => 'No tasks have been added yet.']) ?>
</section>
<?= $this->endSection() ?>
