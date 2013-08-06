<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="icon-ok-sign"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-list"></i> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="btn"><i class="icon-plus"></i> <?php echo $button_insert; ?></a>
        <button type="submit" form="form-customer-ban-ip" class="btn"><i class="icon-trash"></i> <?php echo $button_delete; ?></button>
      </div>
    </div>
    <div class="box-content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer-ban-ip">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <td width="1" class="center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'ip') { ?>
                <a href="<?php echo $sort_ip; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_ip; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_ip; ?>"><?php echo $column_ip; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_customer; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($customer_ban_ips) { ?>
            <?php foreach ($customer_ban_ips as $customer_ban_ip) { ?>
            <tr>
              <td class="center"><?php if ($customer_ban_ip['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $customer_ban_ip['customer_ban_ip_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $customer_ban_ip['customer_ban_ip_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $customer_ban_ip['ip']; ?></td>
              <td class="right"><?php if ($customer_ban_ip['total']) { ?>
                <a href="<?php echo $customer_ban_ip['customer']; ?>"><?php echo $customer_ban_ip['total']; ?></a>
                <?php } else { ?>
                <?php echo $customer_ban_ip['total']; ?>
                <?php } ?></td>
              <td class="right"><?php foreach ($customer_ban_ip['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="row-fluid">
        <div class="span6"><?php echo $pagination; ?></div>
        <div class="span6">
          <div class="results"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 