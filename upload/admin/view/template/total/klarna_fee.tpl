<?php echo $header; ?>
<style type="text/css">
    
    table.form > tbody > tr > td:first-child {
        width: 15px;
    }
    
    table.sub-form > tbody > tr > td:first-child {
        width: 200px;
    }
    
    .hidden {
        display: none;
    }
    
    span.help {
        display: inline;
    }
    
</style>
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
      <h1><img src="view/image/total.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <?php //die(var_dump($klarna_fee_country)); ?>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
        <tr>
            <td colspan="2"><h2><?php echo $text_activated_countries ?></h2></td>
        </tr>
        
        <?php foreach ($country_names as $iso_3 => $country) { ?>
            <tr>
                <td>
                    <?php if ($klarna_fee_country[$iso_3]['status'] == 1) { ?>
                        <input type="checkbox" checked="checked" class="input_country" id="input_coutry_<?php echo $iso_3 ?>" name="klarna_fee_country[<?php echo $iso_3 ?>][status]" value="1" />
                    <?php } else { ?>
                        <input type="checkbox" class="input_country" id="input_coutry_<?php echo $iso_3 ?>" name="klarna_fee_country[<?php echo $iso_3 ?>][status]" value="1" />
                    <?php } ?>
                    
                </td>
                <td>
                    <label for="input_coutry_<?php echo $iso_3 ?>" ><?php echo $country ?></label><br />
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php if ($klarna_fee_country[$iso_3]['status'] == 1) { ?>
                        <div class="country_options">
                    <?php } else { ?>
                         <div class="country_options hidden">
                    <?php } ?>
                        <table class="form sub-form">
                            <tr>
                                <td><label for="input_total_<?php echo $iso_3 ?>"><?php echo $entry_total ?></label><br /><span class="help"><?php echo $help_total ?></span></td>
                                <td><input type="text" id="input_total_<?php echo $iso_3 ?>" name="klarna_fee_country[<?php echo $iso_3 ?>][total]" value="<?php echo $klarna_fee_country[$iso_3]['total'] ?>" /></td>
                            </tr>
                            <tr>
                                <td><label for="input_fee_<?php echo $iso_3 ?>"><?php echo $entry_fee ?></label></td>
                                <td><input type="text" id="input_fee_<?php echo $iso_3 ?>" name="klarna_fee_country[<?php echo $iso_3 ?>][fee]" value="<?php echo $klarna_fee_country[$iso_3]['fee'] ?>" /></td>
                            </tr>
                            <tr>
                                <td><label for="select_tax_<?php echo $iso_3 ?>"><?php echo $entry_tax_class; ?></label></td>
                                <td>
                                    <select id="select_tax_<?php echo $iso_3 ?>"name="klarna_fee_country[<?php echo $iso_3 ?>][tax_class_id]">
                                        <option value="0"><?php echo $text_none; ?></option>
                                        <?php foreach ($tax_classes as $tax_class) { ?>
                                            <?php if ($klarna_fee_country[$iso_3]['tax_class_id'] == $tax_class['tax_class_id']) { ?>
                                                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="input_sort_order_<?php echo $iso_3 ?>"><?php echo $entry_sort_order ?></label></td>
                                <td><input type="text" id="input_sort_order_<?php echo $iso_3 ?>" name="klarna_fee_country[<?php echo $iso_3 ?>][sort_order]" value="<?php echo $klarna_fee_country[$iso_3]['sort_order'] ?>" /></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>

        <?php } ?>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    $('.input_country').change(function(){
        var target = $(this).closest('tr').next().find('.country_options');

        if ($(this).is(':checked')) {
            target.slideDown(300);
        } else {
            target.slideUp(220);
        }
    });
</script>
<?php echo $footer; ?> 