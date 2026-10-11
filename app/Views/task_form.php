<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">TASK MANAGEMENT</p><h1><?= esc($title) ?></h1><p class="muted">Set a title, scheduled date, and status.</p></div></div>
<section class="card form-card">
    <?= view('partials/errors', ['errors' => $errors]) ?>
    <form method="post" action="<?= isset($task['id']) ? site_url('tasks/' . $task['id'] . '/update') : site_url('tasks') ?>">
        <?= csrf_field() ?>
        <div class="field"><label for="title">Title <span class="muted">(required)</span></label><input id="title" name="title" value="<?= esc($task['title'], 'attr') ?>" maxlength="150" required></div>
        <div class="field"><label for="task_date">Task date <span class="muted">(required)</span></label><input id="task_date" name="task_date" type="date" value="<?= esc($task['task_date'], 'attr') ?>" required></div>
        <div class="field"><label for="status">Status</label><select id="status" name="status" required><?php foreach (['pending', 'in progress', 'completed'] as $status): ?><option value="<?= esc($status, 'attr') ?>" <?= $task['status'] === $status ? 'selected' : '' ?>><?= esc(ucfirst($status)) ?></option><?php endforeach ?></select></div>
        <div class="form-actions"><button class="primary" type="submit"><?= isset($task['id']) ? 'Save changes' : 'Create task' ?></button><a href="<?= site_url('tasks') ?>">Cancel</a></div>
    </form>
</section>
<?= $this->endSection() ?>
