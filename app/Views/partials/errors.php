<?php if ($errors !== []): ?>
<div class="alert error" role="alert"><strong>Please check the form.</strong><ul><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach ?></ul></div>
<?php endif ?>
