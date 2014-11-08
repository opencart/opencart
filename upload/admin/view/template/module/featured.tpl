<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="featured_status" id="input-status" class="form-control">
                <?php if ($featured_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <script type="text/javascript"><!--
          function enableAutocomplete(token) {
            $('input[name=\'product' + token + '\']').autocomplete({
              'source': function(request, response) {
                $.ajax({
                  url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                  dataType: 'json',      
                  success: function(json) {
                    response($.map(json, function(item) {
                      return {
                        label: item['name'],
                        value: item['product_id']
                      }
                    }));
                  }
                });
              },
              'select': function(item) {
                $('#product-item' + token + '-' + item['value']).remove();
                $('#product-list' + token).append('<div id="product-item' + token + '-' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" value="' + item['value'] + '" /></div>');  
            
                data = $.map($('#product-list' + token + ' input'), function(element) {
                return $(element).attr('value');
                });
              
                $('input[name=\'featured_module[' + token + '][product]\']').val(data.join());  
              }  
            });
          
            $('#product-list' + token).delegate('.fa-minus-circle', 'click', function() {
              $(this).parent().remove();
            
              data = $.map($('#product-list' + token + ' input'), function(element) {
                return $(element).attr('value');
              });
            
              $('input[name=\'featured_module[' + token + '][product]\']').val(data.join());  
            });
          }
          //--></script> 
          <table id="module" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-right">#</td>
                <td class="text-left"><span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></td>
                <td class="text-left"><?php echo $entry_limit; ?></td>
                <td class="text-left"><?php echo $entry_image; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $module_row = 1; ?>
              <?php foreach ($featured_modules as $featured_module) { ?>
              <tr id="module-row<?php echo $featured_module['key']; ?>">
                <td class="text-right"><?php echo $module_row; ?></td>
                <td class="text-left"><input type="text" name="product<?php echo $featured_module['key']; ?>" value="" placeholder="<?php echo $entry_product; ?>" id="input-product<?php echo $featured_module['key']; ?>" class="form-control" />
                  <div id="product-list<?php echo $featured_module['key']; ?>" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php $products = ''; ?>
                    <?php foreach ($featured_module['product'] as $product) { ?>
                    <?php 
                      if ($products == ''){ $products = $product['product_id']; }
                      else { $products .= ',' . $product['product_id']; }
                    ?>
                    <div id="product-item<?php echo $featured_module['key']; ?>-<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                      <input type="hidden" value="<?php echo $product['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                  <input type="hidden" name="featured_module[<?php echo $featured_module['key']; ?>][product]" value="<?php echo $products; ?>" /></td>
                <td class="text-left"><input type="text" name="featured_module[<?php echo $featured_module['key']; ?>][limit]" value="<?php echo $featured_module['limit']; ?>" placeholder="<?php echo $entry_limit; ?>" class="form-control" /></td>
                <td class="text-left"><input type="text" name="featured_module[<?php echo $featured_module['key']; ?>][width]" value="<?php echo $featured_module['width']; ?>" placeholder="<?php echo $entry_width; ?>" class="form-control" />
                  <input type="text" name="featured_module[<?php echo $featured_module['key']; ?>][height]" value="<?php echo $featured_module['height']; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  <?php if (isset($error_image[$featured_module['key']])) { ?>
                  <div class="text-danger"><?php echo $error_image[$featured_module['key']]; ?></div>
                  <?php } ?></td>
                <td class="text-left"><button type="button" onclick="$('#module-row<?php echo $featured_module['key']; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                <script type="text/javascript"><!--
      enableAutocomplete('<?php echo $featured_module['key']; ?>');
                //--></script>        
              </tr>
              <?php $module_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="4"></td>
                <td class="text-left"><button type="button" onclick="addModule();" data-toggle="tooltip" title="<?php echo $button_module_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
 
  <script type="text/javascript"><!--
function addModule() {
  var token = Math.random().toString(36).substr(2);
      
  html  = '<tr id="module-row' + token + '">';
  html += '  <td class="text-right">' + ($('tbody tr').length + 1) + '</td>';
  html += '  <td class="text-left"><input type="text" name="product' + token +'" value="" placeholder="<?php echo $entry_product; ?>" id="input-product' + token + '" class="form-control" />';
    html += '    <div id="product-list' + token + '" class="well well-sm" style="height: 150px; overflow: auto;"> </div>';
    html += '      <input type="hidden" name="featured_module[' + token + '][product]" value="" /></td>';
  html += '  <td class="text-left"><input type="text" name="featured_module[' + token + '][limit]" value="5" placeholder="<?php echo $entry_limit; ?>" class="form-control" /></td>';
  html += '  <td class="text-left"><input type="text" name="featured_module[' + token + '][width]" value="200" placeholder="<?php echo $entry_width; ?>" class="form-control" /> <input type="text" name="featured_module[' + token + '][height]" value="200" placeholder="<?php echo $entry_height; ?>" class="form-control" /></td>';  
  html += '  <td class="text-left"><button type="button" onclick="$(\'#module-row' + token + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';
  
  $('#module tbody').append(html);
 
 enableAutocomplete(token); 
 
}
//--></script></div>
<?php echo $footer; ?>
