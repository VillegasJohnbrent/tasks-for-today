<?php helper(['url', 'form']); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Tasks for Today Management System">
    <title><?= esc($title) ?> | Tasks for Today</title>
    <link rel="stylesheet" href="<?= base_url('assets/style.css') ?>">
</head>
<body>
<header class="topbar">
    <a class="brand" href="<?= site_url('/') ?>"><span class="brand-mark">✓</span> Tasks for Today</a>
    <div class="account-nav">
    <?php if (session()->get('logged_in') === true): ?>
        <span class="muted">@<?= esc(session()->get('username')) ?></span>
        <form method="post" action="<?= site_url('logout') ?>"><?= csrf_field() ?><button type="submit" class="secondary">Log out</button></form>
    <?php else: ?>
        <a class="primary" href="<?= site_url('login') ?>">Log in</a>
    <?php endif ?>
    </div>
</header>
<div class="shell">
    <aside>
        <p class="eyebrow">WORKSPACE</p>
        <nav aria-label="Main navigation">
            <?php foreach (['welcome' => ['/', 'Welcome'], 'tasks' => ['tasks', 'Task List'], 'profile' => ['profile', 'Profile'], 'about' => ['about', 'About']] as $key => $item): ?>
                <a class="nav-item <?= $active === $key ? 'selected' : '' ?>" <?= $active === $key ? 'aria-current="page"' : '' ?> href="<?= site_url($item[0]) ?>"><?= esc($item[1]) ?></a>
            <?php endforeach ?>
        </nav>
        <div class="sidebar-note">A clearer view of<br>what needs doing.</div>
    </aside>
    <main id="main">
        <?php foreach (['success', 'error'] as $kind): ?>
            <?php if ($message = session()->getFlashdata($kind)): ?><div class="alert <?= $kind ?>" role="alert"><?= esc($message) ?></div><?php endif ?>
        <?php endforeach ?>
        <?= $this->renderSection('content') ?>
    </main>
</div>
<footer>Tasks for Today Management System <span>CodeIgniter 4 · IT0049 TSA2</span></footer>
</body>
</html>
