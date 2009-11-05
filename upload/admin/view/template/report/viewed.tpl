<?php echo $header; ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
</div>
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
    <?php $class = 'odd'; ?>
    <?php foreach ($products as $product) { ?>
    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
    <tr class="<?php echo $class; ?>">
      <td class="left"><?php echo $product['name']; ?></td>
      <td class="left"><?php echo $product['model']; ?></td>
      <td class="right"><?php echo $product['viewed']; ?></td>
      <td class="right"><?php echo $product['percent']; ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr class="even">
      <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<div class="pagination"><?php echo $pagination; ?></div>
<?php echo $footer; ?>