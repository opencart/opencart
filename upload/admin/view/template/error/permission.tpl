<?php echo $header; ?>
<div id="container"><?php echo $menu; ?>
  <div id="content">
    <div class="container">
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
      <div class="page-header">
        <div class="container">
          <h1><i class="fa fa-exclamation-circle fa-lg"></i> <?php echo $heading_title; ?></h1>
        </div>
      </div>
      <p class="text-center"><?php echo $text_permission; ?></p>
    </div>
  </div>
</div>
<?php echo $footer; ?>