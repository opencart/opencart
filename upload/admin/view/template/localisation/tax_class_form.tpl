<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-tax-class" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal" id="form-tax-class">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>
            <div class="col-sm-10">
              <input type="text" name="title" value="<?php echo $title; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title" class="form-control" />
              <?php if ($error_title) { ?>
              <div class="text-danger"><?php echo $error_title; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
            <div class="col-sm-10">
              <input type="text" name="description" value="<?php echo $description; ?>" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control" />
              <?php if ($error_description) { ?>
              <div class="text-danger"><?php echo $error_description; ?></div>
              <?php } ?>
            </div>
          </div>
          <table id="tax-rule" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $entry_rate; ?></td>
                <td class="text-left"><?php echo $entry_based; ?></td>
                <td class="text-left"><?php echo $entry_priority; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $tax_rule_row = 0; ?>
              <?php foreach ($tax_rules as $tax_rule) { ?>
              <tr id="tax-rule-row<?php echo $tax_rule_row; ?>">
                <td class="text-left"><select name="tax_rule[<?php echo $tax_rule_row; ?>][tax_rate_id]" class="form-control">
                    <?php foreach ($tax_rates as $tax_rate) { ?>
                    <?php  if ($tax_rate['tax_rate_id'] == $tax_rule['tax_rate_id']) { ?>
                    <option value="<?php echo $tax_rate['tax_rate_id']; ?>" selected="selected"><?php echo $tax_rate['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $tax_rate['tax_rate_id']; ?>"><?php echo $tax_rate['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><select name="tax_rule[<?php echo $tax_rule_row; ?>][based]" class="form-control">
                    <?php  if ($tax_rule['based'] == 'shipping') { ?>
                    <option value="shipping" selected="selected"><?php echo $text_shipping; ?></option>
                    <?php } else { ?>
                    <option value="shipping"><?php echo $text_shipping; ?></option>
                    <?php } ?>
                    <?php  if ($tax_rule['based'] == 'payment') { ?>
                    <option value="payment" selected="selected"><?php echo $text_payment; ?></option>
                    <?php } else { ?>
                    <option value="payment"><?php echo $text_payment; ?></option>
                    <?php } ?>
                    <?php  if ($tax_rule['based'] == 'store') { ?>
                    <option value="store" selected="selected"><?php echo $text_store; ?></option>
                    <?php } else { ?>
                    <option value="store"><?php echo $text_store; ?></option>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><input type="text" name="tax_rule[<?php echo $tax_rule_row; ?>][priority]" value="<?php echo $tax_rule['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>
                <td class="text-left"><button type="button" onclick="$('#tax-rule-row<?php echo $tax_rule_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $tax_rule_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3"></td>
                <td class="text-left"><button type="button" onclick="addRule();" data-toggle="tooltip" title="<?php echo $button_rule_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
var tax_rule_row = <?php echo $tax_rule_row; ?>;

function addRule() {
	html  = '<tr id="tax-rule-row' + tax_rule_row + '">';
	html += '  <td class="text-left"><select name="tax_rule[' + tax_rule_row + '][tax_rate_id]" class="form-control">';
    <?php foreach ($tax_rates as $tax_rate) { ?>
    html += '    <option value="<?php echo $tax_rate['tax_rate_id']; ?>"><?php echo addslashes($tax_rate['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';
	html += '  <td class="text-left"><select name="tax_rule[' + tax_rule_row + '][based]" class="form-control">';
    html += '    <option value="shipping"><?php echo $text_shipping; ?></option>';
    html += '    <option value="payment"><?php echo $text_payment; ?></option>';
    html += '    <option value="store"><?php echo $text_store; ?></option>';
    html += '  </select></td>';
	html += '  <td class="text-left"><input type="text" name="tax_rule[' + tax_rule_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#tax-rule-row' + tax_rule_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#tax-rule tbody').append(html);
	
	tax_rule_row++;
}
//--></script></div>
<?php echo $footer; ?>