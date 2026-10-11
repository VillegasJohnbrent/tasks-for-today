<?php $canManage = session()->get('logged_in') === true; ?>
<div class="table-wrap">
<table>
    <thead><tr><th scope="col">ID</th><th scope="col">Task</th><th scope="col">Status</th><th scope="col">Scheduled date</th><th scope="col">Created</th><?php if ($canManage): ?><th scope="col">Actions</th><?php endif ?></tr></thead>
    <tbody>
    <?php if ($tasks === []): ?>
        <tr><td colspan="<?= $canManage ? 6 : 5 ?>" class="empty"><?= esc($emptyMessage) ?></td></tr>
    <?php else: ?>
        <?php foreach ($tasks as $task): ?>
            <?php $statusClass = ['pending' => 'pending', 'in progress' => 'progress', 'completed' => 'completed'][$task['status']] ?? 'pending'; ?>
            <tr>
                <td class="muted">#<?= esc((string) $task['id']) ?></td>
                <td class="task-title"><?= esc($task['title']) ?></td>
                <td><span class="badge <?= $statusClass ?>"><?= esc(ucfirst($task['status'])) ?></span></td>
                <td class="nowrap"><?= esc(date('M j, Y', strtotime($task['task_date']))) ?></td>
                <td class="nowrap muted"><?= esc(date('M j, Y · g:i A', strtotime($task['created_at']))) ?></td>
                <?php if ($canManage): ?>
                <td><div class="row-actions">
                    <a href="<?= site_url('tasks/' . $task['id'] . '/edit') ?>">Edit</a>
                    <form method="post" action="<?= site_url('tasks/' . $task['id'] . '/delete') ?>" onsubmit="return confirm('Delete this task from the list? Its record will be archived.');">
                        <?= csrf_field() ?><button type="submit" class="danger-link">Delete</button>
                    </form>
                </div></td>
                <?php endif ?>
            </tr>
        <?php endforeach ?>
    <?php endif ?>
    </tbody>
</table>
</div>
