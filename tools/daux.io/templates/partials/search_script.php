<?php
if (!isset($config)) {
    $config = $params;
}
?>
<?php if ($config->getHTML()->hasSearch()) { ?>
    <script>
        <?php
        $search_strings = [
            "Search_one_result",
            "Search_results",
            "Search_no_results",
            "Search_common_words_ignored",
            "Search_too_short",
            "Search_one_character_or_more",
            "Search_should_be_x_or_more",
            "Link_previous",
            "Link_next",
        ];
        $search_translations = [];
        foreach ($search_strings as $key) {
            $search_translations[$key] = $this->translate($key);
        }
        ?>

        window.searchLanguage = <?= json_encode($page['language']) ?>;
        window.searchTranslation = <?= json_encode($search_translations) ?>;
    </script>

    <!-- Search -->
    <script type="text/javascript" src="<?php echo $base_url; ?>daux_libraries/search.min.js"></script>

    <script>
        window.search({'base_url': '<?php echo $base_url?>'})
    </script>
<?php } ?>