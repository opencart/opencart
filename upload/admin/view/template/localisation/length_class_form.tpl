<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/length.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div class="htabs">
        <?php foreach ($languages as $language) { ?>
        <a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
        <?php } ?>
      </div>
      <?php foreach ($languages as $language) { ?>
      <div id="language<?php echo $language['language_id']; ?>">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_title; ?></td>
            <td><input name="length_class_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($length_class_description[$language['language_id']]) ? $length_class_description[$language['language_id']]['title'] : ''; ?>" />
              <?php if (isset($error_title[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_unit; ?></td>
            <td><input name="length_class_description[<?php echo $language['language_id']; ?>][unit]" value="<?php echo isset($length_class_description[$language['language_id']]) ? $length_class_description[$language['language_id']]['unit'] : ''; ?>" />
              <?php if (isset($error_unit[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_unit[$language['language_id']]; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </div>
      <?php } ?>
      <table class="form">
        <tr>
          <td><?php echo $entry_value; ?></td>
          <td><input type="text" name="value" value="<?php echo $value; ?>" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$.tabs('.htabs a'); 
//--></script>
<?php echo $footer; ?>