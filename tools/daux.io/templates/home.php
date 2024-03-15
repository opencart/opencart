<?php $this->layout('theme::layout/00_layout') ?>

<?php $this->start('classes') ?>homepage<?php $this->stop() ?>

<div class="Navbar NoPrint">
    <div class="Container">
        <?php $this->insert('theme::partials/navbar_content'); ?>
    </div>
</div>

<div class="Homepage">
    <div class="HomepageTitle Container">
        <?= ($config->hasTagline()) ? '<h2>' . $config->getTagline() . '</h2>' : '' ?>
    </div>

    <div class="HomepageImage Container">
        <?= ($config->hasImage()) ? '<img class="homepage-image img-responsive" src="' . $config->getImage() . '" alt="' . $config->getTitle() . '">' : '' ?>
    </div>

    <?php if ($config->getHTML()->hasRepository() || count($page['entry_page']) > 0) { ?>
    <div class="HomepageButtons">
        <div class="Container">
            <?php if ($config->getHTML()->hasRepository()) { ?>
               <a href="https://github.com/<?= $config->getHTML()->getRepository(); ?>" class="Button Button--secondary Button--hero" rel="noopener noreferrer">
                   <?= $this->translate("View_on_github") ?>
               </a>
            <?php }
            $view_doc = $this->translate("View_documentation");
            foreach ($page['entry_page'] as $key => $node) {
                echo '<a href="' . $node . '" class="Button Button--primary Button--hero">' . str_replace("__VIEW_DOCUMENTATION__", $view_doc, $key) . '</a>';
            }
            if ($config->getHTML()->hasButtons()) {
                foreach ($config->getHTML()->getButtons() as $name => $link) {
                    echo '<a href="' . $link . '" class="Button Button--secondary Button--hero">' . $name . '</a>';
                }
            }
            ?>
        </div>
    </div>
    <?php } ?>
</div>

<div class="HomepageContent">
    <div class="Container">
        <div class="Container--inner">
            <div class="doc_content s-content">
                <?= $page['content']; ?>
            </div>
        </div>
    </div>
</div>

<div class="HomepageFooter">
    <div class="Container">
        <div class="Container--inner">
            <?php if ($config->getHTML()->hasLinks()) { ?>
                <ul class="HomepageFooter__links">
                    <?php foreach ($config->getHTML()->getLinks() as $name => $url) { ?>
                        <li><a href="<?= $url; ?>" target="_blank" rel="noopener noreferrer"><?= $name ?></a></li>
                    <?php } ?>
                </ul>
            <?php } ?>

            <?php if ($config->getHTML()->hasTwitterHandles()) { ?>
                <div class="HomepageFooter__twitter">
                    <div class="Twitter">
                        <?php $this->insert('theme::partials/twitter_buttons'); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
