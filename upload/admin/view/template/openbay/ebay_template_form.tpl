<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <a onclick="$('#form').submit();" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-default"><i class="fa fa-check-circle"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-code fa-lg"></i> <?php echo $page_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $btn_save; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
        <input type="hidden" name="template_id" value="<?php echo $template_id; ?>" />
          <div class="form-group">
            <label class="col-sm-2 control-label" for="name"><?php echo $entry_template_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php if (isset($name)){ echo $name; } ?>" placeholder="<?php echo $entry_template_name; ?>" id="name" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="code"><?php echo $entry_template_html; ?></label>
            <div class="col-sm-10">
              <textarea name="html" cols="100" rows="100" placeholder="<?php echo $entry_template_html; ?>" id="code" class="form-control"><?php if (isset($html)){ echo $html; } ?></textarea>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>
<style type="text/css"> .CodeMirror {border: 1px solid #888; height:1000px; width:100%;} </style>
<script type="text/javascript"><!--
  var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
    mode: 'text/html',
    autoCloseTags: true,
    lineNumbers: true,
    tabMode: "indent",
    lineWrapping: true,
    indentUnit: 2
  });
//--></script>
<?php echo $footer; ?>