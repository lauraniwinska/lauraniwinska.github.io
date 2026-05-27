<?php

/** @var \App\Model\Music $music */
/** @var \App\Service\Router $router */

$title = 'Create Music';
$bodyClass = "edit";

ob_start(); ?>
    <h1>Create new Music</h1>
    <form action="<?= $router->generatePath('music-create') ?>" method="music" class="edit-form">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>
        <input type="hidden" name="action" value="music-create">
    </form>

    <a href="<?= $router->generatePath('music-index') ?>">Back to list</a>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
