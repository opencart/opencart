<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/shipping.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div class="tabs">
        <?php foreach ($languages as $language) { ?>
        <a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
        <?php } ?>
      </div>
      <?php foreach ($languages as $language) { ?>
      <div id="language<?php echo $language['language_id']; ?>">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_title; ?></td>
            <td><input name="weight_class[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($weight_class[$language['language_id']]) ? $weight_class[$language['language_id']]['title'] : ''; ?>" />
              <?php if (isset($error_title[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_unit; ?></td>
            <td><input name="weight_class[<?php echo $language['language_id']; ?>][unit]" value="<?php echo isset($weight_class[$language['language_id']]) ? $weight_class[$language['language_id']]['unit'] : ''; ?>" />
              <?php if (isset($error_unit[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_unit[$language['language_id']]; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </div>
      <?php } ?>
      <table class="form">
        <?php foreach ($weight_tos as $weight_to) { ?>
        <tr>
          <td><?php echo $weight_to['title']; ?>:</td>
          <td><input type="text" name="weight_rule[<?php echo $weight_to['weight_class_id']; ?>]" value="<?php echo isset($weight_rule[$weight_to['weight_class_id']]) ? $weight_rule[$weight_to['weight_class_id']]['rule'] : ''; ?>" /></td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>
<?php echo $footer; ?>