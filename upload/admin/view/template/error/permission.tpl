<?php echo $header; ?>
<div id="content" class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-warning-sign"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <p class="text-center"><?php echo $text_permission; ?></p>
    </div>
  </div>
</div>
<?php echo $footer; ?>