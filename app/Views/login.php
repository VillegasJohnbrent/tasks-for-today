<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">TASK MANAGEMENT</p><h1>Log in</h1><p class="muted">Sign in to create, edit, and delete tasks.</p></div></div>
<section class="card form-card">
    <?= view('partials/errors', ['errors' => $errors]) ?>
    <form method="post" action="<?= site_url('login') ?>">
        <?= csrf_field() ?>
        <div class="field"><label for="username">Username</label><input id="username" name="username" value="<?= esc($username, 'attr') ?>" maxlength="50" autocomplete="username" required></div>
        <div class="field"><label for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password" required></div>
        <div class="form-actions"><button class="primary" type="submit">Log in</button><a href="<?= site_url('/') ?>">Back to Welcome</a></div>
    </form>
</section>
<?= $this->endSection() ?>
