<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="page-heading"><div><p class="eyebrow">ABOUT THE PROJECT</p><h1>A simple plan for every day.</h1><p class="muted">Tasks for Today Management System</p></div></div>
<section class="card about-card">
    <span class="project-tag">TECHNICAL SUMMATIVE ASSESSMENT 2</span>
    <h2>Keep today's work in focus.</h2>
    <p>This application brings daily tasks, a complete task list, and a demo user profile into one workspace. The Welcome page displays today's active tasks, while the Task List includes active tasks from every date. Logged-in users can create and edit tasks. Deleting a task archives its database record.</p>
    <dl class="details"><div><dt>Developed by</dt><dd><?= esc($developer) ?></dd></div><div><dt>Section</dt><dd><?= esc($section) ?></dd></div><div><dt>Course</dt><dd>IT0049 — Web System Technologies</dd></div><div><dt>Built with</dt><dd>CodeIgniter 4, PHP, MySQL, HTML, and CSS</dd></div></dl>
</section>
<?= $this->endSection() ?>
