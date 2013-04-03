<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons"><button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_name; ?></label>
          <div class="controls">
            <?php foreach ($languages as $language) { ?>
            <input type="text" name="customer_group_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($customer_group_description[$language['language_id']]) ? $customer_group_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" />
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
            <?php if (isset($error_name[$language['language_id']])) { ?>
            <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
            <?php } ?>
            <?php } ?>
          </div>
        </div>
        <?php foreach ($languages as $language) { ?>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_description; ?></label>
          <div class="controls">
            <textarea name="customer_group_description[<?php echo $language['language_id']; ?>][description]" cols="40" rows="5" placeholder="<?php echo $entry_description; ?>"><?php echo isset($customer_group_description[$language['language_id']]) ? $customer_group_description[$language['language_id']]['description'] : ''; ?></textarea>
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" align="top" /> </div>
        </div>
        <?php } ?>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_approval; ?></label>
          <div class="controls">
            <label class="radio inline">
              <?php if ($approval) { ?>
              <input type="radio" name="approval" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="approval" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio inline">
              <?php if (!$approval) { ?>
              <input type="radio" name="approval" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="approval" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>
            <span class="help-block"><?php echo $help_approval; ?></span></div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_sort_order; ?></label>
          <div class="controls">
            <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="input-mini" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>