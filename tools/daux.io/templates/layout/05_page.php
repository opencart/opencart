<?php $this->layout('theme::layout/00_layout') ?>
<div class="Columns content">
    <aside class="Columns__left Collapsible">
        <button type="button" class="Button Collapsible__trigger" aria-controls="sidebar_content" aria-expanded="false" aria-label="<?= $this->translate("Toggle_navigation") ?>">
            <span class="Collapsible__trigger__bar"></span>
            <span class="Collapsible__trigger__bar"></span>
            <span class="Collapsible__trigger__bar"></span>
        </button>

        <?php $this->insert('theme::partials/navbar_content'); ?>

        <div class="Collapsible__content" id="sidebar_content">
            <!-- Navigation -->
            <?php
            $rendertree = $tree;
            $path = '';

            if ($page['language'] !== '') {
                $rendertree = $tree[$page['language']];
                $path = $page['language'];
            }

            echo $this->get_navigation($rendertree, $path, $config->hasRequest() ? $config->getRequest() : '', $base_page, $config->getMode());
            ?>

            <div class="Links">
                <?php if ($config->getHTML()->hasLinks()) { ?>
                    <hr/>
                    <?php foreach ($config->getHTML()->getLinks() as $name => $url) { ?>
                        <a href="<?= $url ?>" target="_blank"  rel="noopener noreferrer"><?= $name ?></a>
                        <br />
                    <?php } ?>
                <?php } ?>
            </div>

            <?php if ($config->getHTML()->showCodeToggle()) { ?>
                <div class="CodeToggler">
                    <hr/>
                    <label class="Checkbox"><?=$this->translate("CodeBlocks_show") ?>
                        <input type="checkbox" class="CodeToggler__button--main" checked="checked"/>
                        <div class="Checkbox__indicator"></div>
                    </label>
                </div>
            <?php } ?>

            <div class="DarkModeToggler">
                <hr/>
                <label class="Checkbox"><?=$this->translate("DarkMode") ?>
                    <input type="checkbox" class="ColorMode__button" />
                    <div class="Checkbox__indicator"></div>
                </label>
            </div>

                <?php if ($config->getHTML()->hasTwitterHandles()) { ?>
                    <div class="Twitter">
                        <hr/>
                        <?php $this->insert('theme::partials/twitter_buttons'); ?>
                    </div>
                <?php } ?>

                <?php if ($config->getHTML()->hasPoweredBy()) { ?>
                    <div class="PoweredBy">
                        <hr/>
                        <?= $config->getHTML()->getPoweredBy()?>
                    </div>
                <?php } ?>
        </div>
    </aside>
    <div class="Columns__right">
        <div class="Columns__right__content">
            <div class="doc_content">
                <?= $this->section('content'); ?>
            </div>
        </div>
    </div>
</div>
