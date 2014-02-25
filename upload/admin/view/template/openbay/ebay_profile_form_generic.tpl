<?php echo $header; ?><?php echo $menu; ?>
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
        <a href="" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn" onclick="$('#add-profile-form').submit();"><i class="fa fa-plus-circle"></i></a>
        <a data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn" onclick="$('#add-profile-form').submit();"><i class="fa fa-plus-circle"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-file-text fa-lg"></i> <?php echo $text_title_list; ?></h1>
    </div>
    <div class="panel-body">










<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning" style="margin-bottom:10px;"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><?php echo $page_title; ?></h1>
            <div class="buttons">
                <a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
            </div>
        </div>
        <div class="content">
            <div id="tabs" class="htabs">
                <a href="#tab-general"><?php echo $tab_general; ?></a>
                <a href="#tab-profile"><?php echo $text_tab_settings; ?></a>
            </div>
            <form action="<?php echo $btn_save; ?>" method="post" enctype="multipart/form-data" id="form">
                <input type="hidden" name="type" value="<?php echo $type; ?>" />
                <input type="hidden" name="ebay_profile_id" value="<?php echo $ebay_profile_id; ?>" />

                <div id="tab-general">

                    <table class="form">
                        <tr>
                            <td><?php echo $text_profile_default; ?></td>
                            <td>
                                <input type="hidden" name="default" value="0" />
                                <input type="checkbox" name="default" value="1" <?php if($default == 1){ echo 'checked="checked"'; } ?> />
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $text_profile_name; ?></td>
                            <td><input type="text" name="name" size="80" value="<?php if(isset($name)){ echo $name; } ?>"></td>
                        </tr>
                        <tr>
                            <td><?php echo $text_profile_desc; ?></td>
                            <td>
                                <textarea name="description" cols="40" rows="5"><?php if(isset($description)){ echo $description; } ?></textarea>
                            </td>
                        </tr>
                    </table>

                </div>

                <div id="tab-profile">

                    <table class="form">

                        <tr>
                            <td><label for="private_auction"><?php echo $text_general_private; ?></td>
                            <td>
                                <?php if(!isset($data['private_listing'])){ $data['private_listing'] = '0'; } ?>
                                
                                <select name="data[private_listing]" class="width250">
                                    <option value="0" <?php if($data['private_listing'] == '0'){ echo'selected'; } ?>><?php echo $text_no; ?></option>
                                    <option value="1" <?php if($data['private_listing'] == '1'){ echo'selected'; } ?>><?php echo $text_yes; ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><label><?php echo $text_general_price; ?></label></td>
                            <td><input type="text" name="data[price_modify]" id="price_modify" class="width100" maxlength="" value="<?php echo (isset($data['price_modify']) ? $data['price_modify'] : '0');  ?>" /></td>
                        </tr>

                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
    $('#tabs a').tabs();

    $('#price_modify').on('change', function(){
        $(this).text().replace('%', '');
    });
//--></script>

<?php echo $footer; ?>