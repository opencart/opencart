<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="panel">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-warning-sign icon-large"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div style="border: 1px solid #DDDDDD; background: #F7F7F7; text-align: center; padding: 15px;"><?php echo $text_permission; ?></div>
  </div>
</div>
<?php echo $footer; ?>