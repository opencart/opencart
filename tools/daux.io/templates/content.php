<?php $this->layout('theme::layout/05_page') ?>
<article class="Page">

    <div class="Page__header">
        <h1><?= $page['breadcrumbs'] ? $this->get_breadcrumb_title($page, $base_page) : $page['title'] ?></h1>
        <?php if ($config->getHTML()->showDateModified() && $page['modified_time']) { ?>
        <span class="ModifiedDate">
            <?= Todaymade\Daux\FormatDate::format($config, $page['modified_time']) ?>
        </span>
        <?php } ?>
        <?php
        $edit_on = $config->getHTML()->getEditOn();
        if ($edit_on && $page['relative_path']) { ?>
        <span class="EditOn">
            <a href="<?= $edit_on['basepath'] ?>/<?= $page['relative_path'] ?>" target="_blank">
                <?= str_replace(":name:", $edit_on['name'], $this->translate("Edit_on")) ?>
            </a>
        </span>
        <?php } ?>
    </div>

    <div class="s-content">
        <?= $page['content']; ?>
    </div>

    <?php
    $hasPrevNext = (!empty($page['prev']) || !empty($page['next']));
    if ($hasPrevNext && $config->getHTML()->showPreviousNextLinks()) {
        ?>
    <nav>
        <ul class="Pager">
            <?php if (!empty($page['prev'])) {
            ?><li class=Pager--prev><a href="<?= $base_url . $page['prev']->getUrl() ?>"><?= $this->translate("Link_previous") ?></a></li><?php
        } ?>
            <?php if (!empty($page['next'])) {
            ?><li class=Pager--next><a href="<?= $base_url . $page['next']->getUrl() ?>"><?= $this->translate("Link_next") ?></a></li><?php
        } ?>
        </ul>
    </nav>
    <?php
    } ?>
</article>

