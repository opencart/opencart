<!DOCTYPE html>
<html class="no-js" lang="<?=$config->getLanguage() ?>">
<head>
    <title><?= $page['title']; ?> <?= ($page['title'] != $config->getTitle()) ? '- ' . $config->getTitle() : "" ?></title>
<?php
    //SEO meta tags...
    $meta = [];

    if (array_key_exists('attributes', $page) && array_key_exists('description', $page['attributes'])) {
        $meta['description'] = $page['attributes']['description'];
    } elseif ($config->hasTagline()) {
        $meta['description'] = $config->getTagLine();
    }
    if (array_key_exists('attributes', $page) && array_key_exists('keywords', $page['attributes'])) {
        $meta['keywords'] = $page['attributes']['keywords'];
    }
    if (array_key_exists('attributes', $page) && array_key_exists('author', $page['attributes'])) {
        $meta['author'] = $page['attributes']['author'];
    } elseif ($config->hasAuthor()) {
        $meta['author'] = $config->getAuthor();
    }

    foreach ($meta as $name => $content) {
        echo "    <meta name=\"{$name}\" content=\"{$content}\">\n";
    }
?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" href="<?= $config->getTheme()->getFavicon(); ?>" type="image/x-icon">

    <!-- Mobile -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- JS -->
    <script>
        window.base_url = "<?= $base_url?>";
        document.documentElement.classList.remove('no-js');
    </script>

    <!-- Font -->
    <?php foreach ($config->getTheme()->getFonts() as $font) { ?>
        <link href='<?= $font; ?>' rel='stylesheet' type='text/css'>
    <?php } ?>

    <!-- CSS -->
    <?php foreach ($config->getTheme()->getCSS() as $css) { ?>
        <link href='<?= $css; ?>' rel='stylesheet' type='text/css'>
    <?php } ?>
</head>
<body class="<?= $this->section('classes'); ?>">
    <?= $this->section('content'); ?>

    <?php
    if ($config->getHTML()->hasGoogleAnalytics()) {
        $this->insert('theme::partials/google_analytics', ['analytics' => $config->getHTML()->getGoogleAnalyticsId(), 'host' => $config->hasHost() ? $config->getHost() : '']);
    }
    if ($config->getHTML()->hasPiwikAnalytics()) {
        $this->insert('theme::partials/piwik_analytics', ['url' => $config->getHTML()->getPiwikAnalyticsUrl(), 'id' => $config->getHTML()->getPiwikAnalyticsId()]);
    }
    if ($config->getHTML()->hasPlausibleAnalyticsDomain()) {
        $this->insert('theme::partials/plausible_analytics', ['domain' => $config->getHTML()->getPlausibleAnalyticsDomain()]);
    }
    ?>

    <!-- JS -->
    <?php foreach ($config->getTheme()->getJS() as $js) { ?>
        <script src="<?= $js; ?>"></script>
    <?php } ?>

    <?php $this->insert('theme::partials/search_script', ['page' => $page, 'base_url' => $base_url]); ?>
</body>
</html>
