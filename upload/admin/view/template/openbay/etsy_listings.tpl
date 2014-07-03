<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="fa fa-bars fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <div class="well">
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label class="control-label" for="input-keywords"><?php echo $entry_keywords; ?></label>
              <input type="text" name="keywords" value="<?php echo isset($filter['keywords']) ? $filter['keywords'] : ''; ?>" placeholder="<?php echo $entry_keywords; ?>" id="input-keywords" class="form-control" />
            </div>
            <div class="form-group">
              <label class="control-label" for="input-limit"><?php echo $entry_limit; ?></label>
              <select name="limit" id="input-limit" class="form-control">
                <option value="1"<?php if ($filter['limit'] == 1) { echo ' selected'; } ?>>1</option>
                <option value="10"<?php if ($filter['limit'] == 10) { echo ' selected'; } ?>>10</option>
                <option value="50"<?php if ($filter['limit'] == 50) { echo ' selected'; } ?>>50</option>
              </select>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
              <select name="status" id="input-status" class="form-control">
                <option value="active"<?php if ($filter['status'] == 'active') { echo ' selected'; } ?>>Active</option>
                <option value="inactive"<?php if ($filter['status'] == 'inactive') { echo ' selected'; } ?>>Inactive</option>
                <option value="draft"<?php if ($filter['status'] == 'draft') { echo ' selected'; } ?>>Draft</option>
                <option value="expired"<?php if ($filter['status'] == 'expired') { echo ' selected'; } ?>>Expired</option>
              </select>
            </div>
            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
          </div>
        </div>
      </div>
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
            <tr>
              <td class="text-left"><?php echo $column_listing_id; ?></td>
              <td class="text-left"><?php echo $column_title; ?></td>
              <td class="text-center"><?php echo $column_listing_qty; ?></td>
              <td class="text-center"><?php echo $column_store_qty; ?></td>
              <td class="text-center"><?php echo $column_link_status; ?></td>
              <td class="text-center"><?php echo $column_status; ?></td>
              <td class="text-right"><?php echo $column_action; ?></td>
            </tr>
            </thead>
            <tbody>
            <?php if ($listings) { ?>
              <?php foreach ($listings as $listing) { ?>
              <tr>
                <td class="text-left"><?php echo $listing['listing']['listing_id']; ?></td>
                <td class="text-left"><?php echo $listing['listing']['title']; ?></td>
                <td class="text-center"><?php echo $listing['listing']['quantity']; ?></td>
                <td class="text-center">
                  <?php
                    if(isset($listing['link']['quantity'])) {
                      echo $listing['link']['quantity'];
                    } else {
                      echo '<i class="fa fa-minus"></i>';
                    }
                  ?>
                </td>
                <td class="text-center">
                  <?php
                    if(isset($listing['link']['link_status']) && $listing['link']['link_status'] == 1) {
                      echo '<i class="fa fa-check" style="color: green;"></i>';
                    } else {
                      echo '<i class="fa fa-times" style="color: red;"></i>';
                    }
                  ?>
                </td>
                <td class="text-center">
                  <?php
                    if (empty($listing['link'])) {
                      echo $text_status_nolink;
                    } elseif(isset($listing['link']['quantity']) && $listing['link']['quantity'] != $listing['listing']['quantity']) {
                      echo $text_status_stock;
                    } else {
                      echo $text_status_ok;
                    }
                  ?>
                </td>
                <td class="text-right"></td>
              </tr>
              <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </form>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
  var url = 'index.php?route=openbay/etsy_product/listings&token=<?php echo $token; ?>';

  var status = $('select[name=\'status\']').val();

  url += '&status=' + encodeURIComponent(status);

  var limit = $('select[name=\'limit\']').val();

  url += '&limit=' + encodeURIComponent(limit);

  var keywords = $('input[name=\'keywords\']').val();

  if(keywords != '') {
    url += '&keywords=' + encodeURIComponent(keywords);
  }

  location = url;
});
//--></script>
<?php echo $footer; ?>