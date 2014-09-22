<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <a data-toggle="tooltip" title="<?php echo $button_refresh; ?>" class="btn btn-default" onclick="loadUsage();"><i class="fa fa-cog fa-lg"></i></a>
        <a href="<?php echo $return; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <h4><div class="btn btn-primary" id="load_usage_loading"><i class="fa fa-cog fa-lg fa-spin"></i></div></h4>
      <div id="usageTable" class="displayNone"></div>
    </div>
</div>

<script type="text/javascript"><!--
  function loadUsage(){
	    $.ajax({
        url: 'index.php?route=openbay/ebay/getusage&token=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        beforeSend: function(){
            $('#usageTable').hide();
            $('#load_usage_loading').show();
        },
        success: function(json) {
            $('#load_usage_loading').hide();
            $('#usageTable').html(json.html).show();
            if (json.lasterror){ alert(json.lastmsg); }
        },
        failure: function(){
            $('#load_usage_loading').hide();
            $('#usageTable').hide();
            alert('<?php echo $error_ajax_load; ?>');
        },
        error: function(){
            $('#load_usage_loading').hide();
            $('#usageTable').hide();
            alert('<?php echo $error_ajax_load; ?>');
        }
	    });
  }

  $(document).ready(function() {
    loadUsage();
  });
//--></script>
<?php echo $footer; ?>