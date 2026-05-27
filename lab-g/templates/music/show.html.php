<?php

/** @var \App\Model\Music $music */
/** @var \App\Service\Router $router */

$title = "{$music->getSubject()} ({$music->getId()})";
$bodyClass = 'show';

ob_start(); ?>
    <h1><?= $music->getSubject() ?></h1>
    <article>
        <?= $music->getContent();?>
    </article>

    <ul class="action-list">
        <li> <a href="<?= $router->generatePath('music-index') ?>">Back to list</a></li>
        <li><a href="<?= $router->generatePath('music-edit', ['id'=> $music->getId()]) ?>">Edit</a></li>
    </ul>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
