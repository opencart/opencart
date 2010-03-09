<?php echo $header; ?> 
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/feed.png');"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
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
        <?php foreach ($extensions as $extension) { ?>
        <tr>
          <td class="left"><?php echo $extension['name']; ?></td>
          <td class="left"><?php echo $extension['status'] ?></td>
          <td class="right"><?php foreach ($extension['action'] as $action) { ?>
            [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
            <?php } ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<?php echo $footer; ?>