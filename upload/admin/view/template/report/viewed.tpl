<?php echo $header; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/report.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location = '<?php echo $reset; ?>';" class="button"><span><?php echo $button_reset; ?></span></a></div>  
  </div>
  <div class="content">
    <table class="list">
      <thead>
        <tr>
          <td class="left"><?php echo $column_name; ?></td>
          <td class="left"><?php echo $column_model; ?></td>
          <td class="right"><?php echo $column_viewed; ?></td>
          <td class="right"><?php echo $column_percent; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php if ($products) { ?>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td class="left"><?php echo $product['name']; ?></td>
          <td class="left"><?php echo $product['model']; ?></td>
          <td class="right"><?php echo $product['viewed']; ?></td>
          <td class="right"><?php echo $product['percent']; ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<?php echo $footer; ?>