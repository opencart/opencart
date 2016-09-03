<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
 <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-auspost" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
           <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-auspost" class="form-horizontal">
            
             <div class="row">
                    <div class="col-sm-2">
                      <ul id="method-list" class="nav nav-pills nav-stacked">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                        <?php
						  for($i=1;$i<=12;$i++){
						?>
                        <li><a href="#tab-setting<?php echo $i;?>" data-toggle="tab"><?php echo $tab_rate.' '.$i; ?></a></li>
					   <?php }?>
                      </ul>
                    </div>
	                
                  <div class="col-sm-10">
                    <div id="shipping-container" class="tab-content">
                     <div class="tab-pane active" id="tab-general"> 
                             <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status"><?php echo $tab_general; ?></label>
                            <div class="col-sm-10">
                              <select name="xshipping_status" id="input-status" class="form-control">
                                <?php if ($xshipping_status) { ?>
                                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                  <option value="0"><?php echo $text_disabled; ?></option>
                                  <?php } else { ?>
                                  <option value="1"><?php echo $text_enabled; ?></option>
                                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                              </select>
                             </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="xshipping_sort_order" value="<?php echo $xshipping_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                            </div>
                          </div>  
                            
                       </div> <!--end of tab general-->
                       <?php
				for($i=1;$i<=12;$i++){
		     ?>
	   <div id="tab-setting<?php echo $i;?>" class="tab-pane">
         
		 <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
            <div class="col-sm-10"><input class="form-control" type="text" name="xshipping_name<?php echo $i;?>" value="<?php echo ${'xshipping_name'.$i}; ?>" /></div>
          </div>
		  
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_cost; ?></label>
            <div class="col-sm-10"><input class="form-control" type="text" name="xshipping_cost<?php echo $i;?>" value="<?php echo ${'xshipping_cost'.$i}; ?>" /></div>
          </div>
         <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_tax; ?></label>
            <div class="col-sm-10"><select class="form-control" name="xshipping_tax_class_id<?php echo $i;?>">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == ${'xshipping_tax_class_id'.$i}) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></div>
          </div>
         <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10"><select class="form-control" name="xshipping_geo_zone_id<?php echo $i;?>">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == ${'xshipping_geo_zone_id'.$i}) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_free; ?></label>
            <div class="col-sm-10"><input class="form-control" type="text" name="xshipping_free<?php echo $i;?>" value="<?php echo ${'xshipping_free'.$i}; ?>" /></div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10"><input class="form-control" type="text" name="xshipping_sort_order<?php echo $i;?>" value="<?php echo ${'xshipping_sort_order'.$i}; ?>" size="1" /></div>
          </div>
		  <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
              <div class="col-sm-10"><select class="form-control" name="xshipping_status<?php echo $i;?>">
                  <?php if (${'xshipping_status'.$i}) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></div>
            </div>
       
        </div>
		<?php }?>
                   </div>
                 </div>
               </div>      
            </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>