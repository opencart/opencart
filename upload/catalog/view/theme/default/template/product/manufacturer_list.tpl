<?php echo $header; ?>

<div class="container"> 
  <!-- Breadcrumb -->
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li> <a href="<?php echo $breadcrumb['href']; ?>"> <?php echo $breadcrumb['text']; ?> </a> </li>
    <?php } ?>
  </ul>
  <div class="row"> <?php echo $column_left; ?>
    <div class="span12"> <?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($categories) { ?>
      <div class="pagination">
        <ul>
          <?php foreach ($categories as $category) { ?>
          <li> <a href="index.php?route=product/manufacturer#<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a> </li>
          <?php } ?>
        </ul>
      </div>
      <!-- pagination -->
      
      <?php foreach ($categories as $category) { ?>
      <ul class="nav nav-list">
        <li class="nav-header"> <?php echo $category['name']; ?> <span id="<?php echo $category['name']; ?>"></span> </li>
        <?php if ($category['manufacturer']) { ?>
        <?php for ($i = 0; $i < count($category['manufacturer']);) { ?>
        <?php $j = $i + ceil(count($category['manufacturer']) / 4); ?>
        <?php for (; $i < $j; $i++) { ?>
        <?php if (isset($category['manufacturer'][$i])) { ?>
        <li> <a href="<?php echo $category['manufacturer'][$i]['href']; ?>"> <?php echo $category['manufacturer'][$i]['name']; ?> </a> </li>
        <?php } ?>
        <?php } ?>
        <?php } ?>
        <?php } ?>
      </ul>
      <?php } ?>
      <?php } else { ?>
      <div class="content"><?php echo $text_empty; ?></div>
      <div class="buttons">
        <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?> </div>
    <!-- content span9 --> 
    
    <?php echo $column_right; ?> </div>
  <!-- row --></div>
<?php echo $footer; ?>