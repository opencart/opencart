<?php $this->layout('theme::layout/05_page') ?>

<article class="Page">
    <div class="Page__header">
        <h1><?= $page['title']; ?></h1>
    </div>

    <div class="s-content">
        <?= $page['content']; ?>
    </div>
</article>
