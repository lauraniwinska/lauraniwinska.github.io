<?php

/** @var \App\Model\Music[] $musicList */
/** @var \App\Service\Router $router */

$title = 'Music List';
$bodyClass = 'index';

ob_start(); ?>
    <h1>Music List</h1>

    <a href="<?= $router->generatePath('music-create') ?>">Create new music</a>

    <ul class="index-list">
        <?php foreach ($musicList as $music): ?>
            <li><h3><?= $music->getSubject() ?></h3>
                <ul class="action-list">
                    <li><a href="<?= $router->generatePath('music-show', ['id' => $music->getId()]) ?>">Details</a></li>
                    <li><a href="<?= $router->generatePath('music-edit', ['id' => $music->getId()]) ?>">Edit</a></li>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
