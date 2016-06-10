<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <?php if (isset($url_create_new)) { ?>
        <a href="<?php echo $url_create_new; ?>" data-toggle="tooltip" title="<?php echo $button_create_new_listing; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
        <?php } ?>
        <?php if (isset($url_delete_links)) { ?>
        <a href="<?php echo $url_delete_links; ?>" data-toggle="tooltip" title="<?php echo $button_remove_links; ?>" class="btn btn-danger"><i class="fa fa-times-circle"></i></a>
        <?php } ?>
        <a href="<?php echo $url_return; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> </div>
      <h1><?php echo $text_edit_heading; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($has_saved_listings) { ?>
    <div class="well">
      <div class="row">
        <div class="col-sm-12">
          <p><?php echo $text_has_saved_listings; ?></p>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div class="pull-right"> <a onclick="location = '<?php echo $url_saved_listings; ?>'" class="btn btn-primary"><?php echo $button_saved_listings; ?></a> </div>
        </div>
      </div>
    </div>
    <?php } ?>
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th class="text-left"><?php echo $column_name; ?></th>
          <th class="text-left"><?php echo $column_sku; ?></th>
          <th class="text-left"><?php echo $column_model; ?></th>
          <th class="text-left"><?php echo $column_combination; ?></th>
          <th class="text-left"><?php echo $column_sku_variant; ?></th>
          <th class="text-left"><?php echo $column_amazon_sku; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($product_links)) { ?>
          <?php foreach ($product_links as $link) { ?>
          <tr>
            <td class="text-left"><?php echo $link['product_name']; ?></td>
            <td class="text-left"><?php echo $link['sku']; ?></td>
            <td class="text-left"><?php echo $link['model']; ?></td>
            <td class="text-left"><?php echo $link['combination']; ?></td>
            <td class="text-left"><?php echo $link['var']; ?></td>
            <td class="text-left"><?php echo $link['amazon_sku']; ?></td>
          </tr>
          <?php } ?>
        <?php } else { ?>
        <tr>
          <td colspan="6" class="text-center"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<?php echo $footer; ?>