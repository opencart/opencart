<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons"><button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="tabbable tabs-left">
          <ul class="nav nav-tabs" id="country">
            <?php foreach ($countries as $country) { ?>
            <li><a href="#tab-<?php echo $country['code']; ?>" data-toggle="tab"><?php echo $country['name']; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <?php foreach ($countries as $country) { ?>
            <div class="tab-pane" id="tab-<?php echo $country['code']; ?>">
              <div class="control-group">
                <label class="control-label" for="input-total<?php echo $country['code']; ?>"><?php echo $entry_total ?></label>
                <div class="controls">
                  <input type="text" name="klarna_fee[<?php echo $country['code']; ?>][total]" value="<?php echo isset($klarna_fee[$country['code']]) ? $klarna_fee[$country['code']]['total'] : ''; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total<?php echo $country['code']; ?>" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-fee<?php echo $country['code']; ?>"><?php echo $entry_fee ?></label>
                <div class="controls">
                  <input type="text" name="klarna_fee[<?php echo $country['code']; ?>][fee]" value="<?php echo isset($klarna_fee[$country['code']]) ? $klarna_fee[$country['code']]['fee'] : ''; ?>" placeholder="<?php echo $entry_fee; ?>" id="input-fee<?php echo $country['code']; ?>" />
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-tax-class<?php echo $country['code']; ?>"><?php echo $entry_tax_class; ?></label>
                <div class="controls">
                  <select name="klarna_fee[<?php echo $country['code']; ?>][tax_class_id]" id="input-tax-class<?php echo $country['code']; ?>">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($tax_classes as $tax_class) { ?>
                    <?php if (isset($klarna_fee[$country['code']]) && $klarna_fee[$country['code']]['tax_class_id'] == $tax_class['tax_class_id']) { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-status<?php echo $country['code']; ?>"><?php echo $entry_status; ?></label>
                <div class="controls">
                  <select name="klarna_fee[<?php echo $country['code']; ?>][status]" id="input-status<?php echo $country['code']; ?>">
                    <?php if (isset($klarna_fee[$country['code']]) && $klarna_fee[$country['code']]['status']) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="control-group">
                <label class="control-label" for="input-sort-order<?php echo $country['code']; ?>"><?php echo $entry_sort_order; ?></label>
                <div class="controls">
                  <input type="text" name="klarna_fee[<?php echo $country['code']; ?>][sort_order]" value="<?php echo isset($klarna_fee[$country['code']]) ? $klarna_fee[$country['code']]['sort_order'] : ''; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order<?php echo $country['code']; ?>" class="input-mini" />
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#country a:first').tab('show');
//--></script> 
<?php echo $footer; ?> 