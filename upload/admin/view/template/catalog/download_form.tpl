<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/download.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <?php foreach ($languages as $language) { ?>
		<tr>
          <td><span class="required">*</span> <?php echo $entry_name; ?></td>
          <td><input name="download_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($download_description[$language['language_id']]) ? $download_description[$language['language_id']]['name'] : ''; ?>" />
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
            <?php if (isset($error_name[$language['language_id']])) { ?>
            <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
            <?php } ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_filename; ?></td>
          <td><input type="file" name="download" value="" />
            <br/><span class="help" style="font-style: italic;"><?php echo $filename; ?></span>
            <?php if ($error_download) { ?>
            <span class="error"><?php echo $error_download; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_remaining; ?></td>
          <td><input type="input" name="remaining" value="<?php echo $remaining; ?>" size="6" /></td>
        </tr>
        <?php if ($show_update) { ?>
        <tr>
          <td><?php echo $entry_update; ?></td>
          <td>
          <?php if ($update) { ?>
          <input type="checkbox" name="update" value="1" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="update" value="1" />
          <?php } ?>
          </td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>