<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('#form').attr('action', '<?php echo $copy; ?>'); $('#form').submit();" class="button"><?php echo $button_copy; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>        
                <td class="center" width="1"><input type="checkbox" onclick="$('input[name*=\'profile_ids\']').attr('checked', this.checked)" /></td>
                <td class="left"><?php echo $column_name ?></td>
                <td class="left"><?php echo $column_sort_order ?></td>
                <td class="right"><?php echo $column_action ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($profiles) { ?>
                <?php foreach ($profiles as $profile) { ?>
                <tr>
                    <td class="center"><input type="checkbox" name="profile_ids[]" value="<?php echo $profile['profile_id'] ?>" /></td>
                    <td class="left"><?php echo $profile['name'] ?></td>
                    <td class="left"><?php echo $profile['sort_order'] ?></td>
                    <td class="right">
                        <?php foreach ($profile['action'] as $action): ?>
                        [<a href="<?php echo $action['href'] ?>"><?php echo $action['name'] ?></a>]
                        <?php endforeach;?>
                    </td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                  <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>