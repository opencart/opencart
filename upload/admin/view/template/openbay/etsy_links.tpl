<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="fa fa-link fa-lg"></i> <?php echo $text_linked_items; ?></h1>
    </div>
    <div class="panel-body">
      <p><?php echo $text_text_linked_desc; ?></p>
        <table class="table">
          <thead>
            <tr>
              <th class="text-left"><?php echo $column_product; ?></th>
              <th class="text-center"><?php echo $column_item_id; ?></th>
              <th class="text-center"><?php echo $column_store_stock; ?></th>
              <th class="text-center"><?php echo $column_etsy_stock; ?></th>
              <th class="text-center"><?php echo $column_status; ?></th>
              <th class="text-center"><?php echo $column_action; ?></th>
            </tr>
          </thead>
          <tr>
            <td class="text-left" colspan="6" id="checking-linked-items">
              <a class="btn btn-primary" id="load-usage"><i class="fa fa-cog fa-lg fa-spin"></i> <?php echo $text_loading; ?></a>
            </td>
          </tr>
          <tbody id="show-linked-items">
            <?php foreach ($items as $id => $item) { ?>
              <tr id="row-<?php echo $id; ?>">
                <td class="text-left"><a href="<?php echo $item['link_edit']; ?>" target="_BLANK"><?php echo $item['name']; ?></a></td>
                <td class="text-center"><a href="<?php echo $item['link_etsy']; ?>" target="_BLANK"><?php echo $id; ?></a></td>
                <td class="text-center" id="text-status-<?php echo $id; ?>"></td>
                <td class="text-center" id="column-stock-etsy-<?php echo $id; ?>"></td>
                <td class="text-center" id="column-status-<?php echo $id; ?>"></td>
                <td class="text-center" id="column-action-<?php echo $id; ?>"></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
  $(document).ready(function() {

  });
//--></script>
<?php echo $footer; ?>