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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/download.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="download_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($download_description[$language['language_id']]) ? $download_description[$language['language_id']]['name'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
              <?php } ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_filename; ?></td>
            <td><input type="file" name="download" value="" />
              <br/>
              <span class="help"><?php echo $filename; ?></span>
              <?php if ($error_download) { ?>
              <span class="error"><?php echo $error_download; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_remaining; ?></td>
            <td><input type="text" name="remaining" value="<?php echo $remaining; ?>" size="6" /></td>
          </tr>
          <?php if ($show_update) { ?>
          <tr>
            <td><?php echo $entry_update; ?></td>
            <td><?php if ($update) { ?>
              <input type="checkbox" name="update" value="1" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="update" value="1" />
              <?php } ?></td>
          </tr>
          <?php } ?>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>