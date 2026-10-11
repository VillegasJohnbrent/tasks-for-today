<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="page-heading">
    <div><p class="eyebrow">YOUR DAILY OVERVIEW</p><h1>Welcome to your day.</h1><p class="muted">Focus on the tasks scheduled for today.</p></div>
    <span class="date-chip"><?= esc(date('D, M j, Y', strtotime($today))) ?></span>
</div>
<div class="stats">
    <div class="stat"><span>Today's tasks</span><strong><?= count($tasks) ?></strong></div>
    <div class="stat"><span>Completed today</span><strong><?= $completed ?></strong></div>
    <div class="stat"><span>Still to do today</span><strong><?= count($tasks) - $completed ?></strong></div>
</div>
<section class="card">
    <div class="card-heading"><div><h2>Today's tasks</h2><p class="muted">Scheduled for <?= esc(date('F j, Y', strtotime($today))) ?> · Philippine time</p></div><a class="button" href="<?= site_url('tasks') ?>">View all tasks →</a></div>
    <?= view('partials/task_table', ['tasks' => $tasks, 'emptyMessage' => 'No tasks are scheduled for today.']) ?>
</section>
<?= $this->endSection() ?>
