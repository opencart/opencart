<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php foreach ($downloads as $download) { ?>
  <div class="download-list">
    <div class="download-id"><b><?php echo $text_order; ?></b> <?php echo $download['order_id']; ?></div>
    <div class="download-status"><b><?php echo $text_size; ?></b> <?php echo $download['size']; ?></div>
    <div class="download-content">
      <div><b><?php echo $text_name; ?></b> <?php echo $download['name']; ?><br />
        <b><?php echo $text_date_added; ?></b> <?php echo $download['date_added']; ?></div>
      <div><b><?php echo $text_remaining; ?></b> <?php echo $download['remaining']; ?></div>
      <div class="download-info">
        <?php if ($download['remaining'] > 0) { ?>
        <a href="<?php echo $download['href']; ?>"><img src="catalog/view/theme/default/image/download.png" alt="<?php echo $button_download; ?>" title="<?php echo $button_download; ?>" /></a>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php } ?>
  <div class="pagination"><?php echo $pagination; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>