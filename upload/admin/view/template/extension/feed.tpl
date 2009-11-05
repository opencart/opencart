<?php echo $header; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
</div>
<table class="list">
  <thead>
    <tr>
      <td class="left"><?php echo $column_name; ?></td>
      <td class="left"><?php echo $column_status; ?></td>
      <td class="right"><?php echo $column_action; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($extensions) { ?>
    <?php $class = 'odd'; ?>
    <?php foreach ($extensions as $extension) { ?>
    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
    <tr class="<?php echo $class; ?>">
      <td class="left"><?php echo $extension['name']; ?></td>
      <td class="left"><?php echo $extension['status'] ?></td>
      <td class="right"><?php foreach ($extension['action'] as $action) { ?>
        [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
        <?php } ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr class="even">
      <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<?php echo $footer; ?>