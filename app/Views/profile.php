<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">YOUR WORKSPACE ACCOUNT</p><h1>Profile</h1><p class="muted">The demo user registered in this system.</p></div></div>
<?php if ($user): ?>
<section class="card profile-card">
    <div class="profile-header"><span class="avatar"><?= esc(strtoupper(substr($user['username'], 0, 1))) ?></span><div><h2><?= esc($user['full_name']) ?></h2><p class="muted">@<?= esc($user['username']) ?></p></div><span class="badge completed">Demo account</span></div>
    <dl class="details"><div><dt>User ID</dt><dd><?= esc((string) $user['id']) ?></dd></div><div><dt>Username</dt><dd><?= esc($user['username']) ?></dd></div><div><dt>Full name</dt><dd><?= esc($user['full_name']) ?></dd></div><div><dt>Email address</dt><dd><?= esc($user['email']) ?></dd></div><div><dt>Created at</dt><dd><?= esc(date('F j, Y · g:i A', strtotime($user['created_at']))) ?></dd></div></dl>
</section>
<?php else: ?>
<section class="card empty">No demo user is available.</section>
<?php endif ?>
<?= $this->endSection() ?>
