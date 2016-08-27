<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
		<div class="col-sm-5 pull-right">
                  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ecustommenu" class="form-inline pull-right">
				  <?php echo $text_custommenu_enable; ?>
				  <label class="radio-inline">
                    <?php if ($configcustommenu_custommenu) { ?>
                    <input type="radio" name="configcustommenu_custommenu" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="configcustommenu_custommenu" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$configcustommenu_custommenu) { ?>
                    <input type="radio" name="configcustommenu_custommenu" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="configcustommenu_custommenu" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
		&nbsp;&nbsp;<button type="submit" form="form-ecustommenu" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-xs btn-success"><i class="fa fa-check"></i></button>
        </form>
		</div>
      </div>
      <div class="panel-body">
		<div class="col-sm-3">
			<div class="heading-accordion"><span class="custommenu-item-add"><?php echo $text_new_custommenu_item; ?></span></div>
			<div class="module_accordion accordion">
				<div class="accordion-heading"><?php echo $text_custom; ?></div>
					<div class="accordion-content accordion-content-drag addCustom">
						<div class="input-group" id="addCustom">
                            <div class="input-group">
                                <input type="text" name="custom_name" value="" placeholder="<?php echo $column_custom_name; ?>" id="input-custom-name" class="form-control input-full-width" />
                            </div>
                            <br/>
                            <div class="input-group">
                                <input type="text" name="custom_url" value="" placeholder="<?php echo $column_custom_link; ?>" id="input-custom-link" class="form-control input-full-width" />
                            </div>
							<br/>
							<div class="pull-right">
								<button type="button" onclick="addcustommenu('custom');" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_custom;?>">
									<i class="fa fa-plus-circle"></i>
								</button>
							</div>
						</div>
					</div>
				<div class="accordion-heading"><?php echo $text_category; ?></div>
					<div class="accordion-content accordion-content-drag addCategory">
						<div class="input-group" id="addCategory">
							<input type="text" name="filter_category_name" value="" placeholder="<?php echo $column_category_name; ?>" id="input-category-name" class="form-control input-full-width" autocomplete="off" />
							<input type="hidden" name="category_id" value="" id="category_id" class="form-control"/>
							<div class="input-group-btn">
								<button type="button" onclick="addcustommenu('category');" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_categories; ?>">
									<i class="fa fa-plus-circle"></i>
								</button>
							</div>
						</div>
					</div>
				<div class="accordion-heading"><?php echo $text_product; ?></div>
					<div class="accordion-content accordion-content-drag addProduct">
						<div class="input-group" id="addProduct">
							<input type="text" name="filter_product_name" value="" placeholder="<?php echo $column_product_name; ?>" id="input-product-name" class="form-control input-full-width" autocomplete="off" />
							<input type="hidden" name="product_id" value="" id="product_id" class="form-control"/>
							<div class="input-group-btn">
								<button type="button" onclick="addcustommenu('product');" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_products;?>">
									<i class="fa fa-plus-circle"></i>
								</button>
							</div>
						</div>
					</div>
				<div class="accordion-heading"><?php echo $text_manufacturer; ?></div>
					<div class="accordion-content accordion-content-drag addManufacturer">
						<div class="input-group" id="addManufacturer">
							<input type="text" name="filter_manufacturer_name" value="" placeholder="<?php echo $column_manufacturer_name; ?>" id="input-manufacturer-name" class="form-control input-full-width" autocomplete="off" />
							<input type="hidden" name="manufacturer_id" value="" id="manufacturer_id" class="form-control"/>
							<div class="input-group-btn">
								<button type="button" onclick="addcustommenu('manufacturer');" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_manufacturers; ?>">
									<i class="fa fa-plus-circle"></i>
								</button>
							</div>
						</div>
					</div>
				<div class="accordion-heading"><?php echo $text_information; ?></div>
					<div class="accordion-content accordion-content-drag addInformation">
						<div class="input-group" id="addInformation">
							<input type="text" name="filter_information_name" value="" placeholder="<?php echo $column_information_name; ?>" id="input-information-name" class="form-control input-full-width" autocomplete="off" />
							<input type="hidden" name="information_id" value="" id="information_id" class="form-control"/></td>
							<div class="input-group-btn">
								<button type="button" onclick="addcustommenu('information');" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_informations; ?>">
									<i class="fa fa-plus-circle"></i>
								</button>
							</div>
						</div>
					</div>
			</div>
		</div>		  	  
		<div class="col-sm-9" id="custommenu-management">
			<form method="post" enctype="multipart/form-data" id="form-custommenu">
				<div class="heading-accordion"><span class="custommenu-item-add"><?php echo $text_custommenu_title; ?></span></div>
				<div class="custommenu-edit ">
					<div id="post-body">
						<div id="post-body-content">								
							<div class="drag-instructions post-body-plain">
								<p><?php echo $text_custommenu_description; ?></p>
							</div>
								<ul class="custommenu" id="custommenu-to-edit">
								<?php $class_count = 1; $sub_custommenu = ''; $count = 0;?>
								<?php foreach($custommenus as $custommenu) { ?>
								<?php if($custommenu['isSubcustommenu'] && ($sub_custommenu == $custommenu['isSubcustommenu'] || empty($sub_custommenu))) {
										$class = 'custommenu-item-depth-' . $class_count;
										$sub_custommenu = $custommenu['isSubcustommenu'];
										$delteItem = 'child-';
									  } else if($custommenu['isSubcustommenu']) {
										$class_count++;
										$sub_custommenu = $custommenu['isSubcustommenu'];
										$class = 'custommenu-item-depth-' . $class_count;
										$delteItem = 'child-';
									  } else {
										$class_count = 1;
										$class = 'custommenu-item-depth-0';
										$delteItem = '';
										$sub_custommenu = '';
									  }
									$count++;
									  ?>
									<li id="custommenu-<?php echo $delteItem; ?>item-<?php echo $custommenu['custommenu_id']; ?>" class="custommenu-item <?php echo $class; ?> custommenu-item-page custommenu-item-edit-inactive pending">
										<dl class="custommenu-item-bar">
											<dt class="custommenu-item-handle">
												<span class="item-title"><span class="custommenu-item-title"><?php echo $custommenu['name']; ?></span> <span class="is-subcustommenu" <?php echo ($custommenu['isSubcustommenu']) ? '' : 'style="display: none;"'; ?> ><?php echo $text_sub_item; ?></span></span>
												<span class="item-controls">
													<span class="item-type"><?php echo ucwords($custommenu['custommenu_type']);?></span>
													<a class="item-edit opencustommenuItem  <?php echo $custommenu['custommenu_type'];?>" id="edit-<?php echo $delteItem . $custommenu['custommenu_id']; ?>" title="">
														<i class="fa fa-caret-down"></i>
													</a>
												</span>
											</dt>
										</dl>
										
										<div class="custommenu-item-settings" id="custommenu-item-settings-edit-<?php echo $delteItem; ?><?php echo $custommenu['custommenu_id']; ?>">
											<?php echo $text_custommenu_name;?><br>
											<?php foreach ($languages as $language) { ?>
												<?php if($custommenu['isSubcustommenu']) { ?>
												<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
													<input type="text" name="custommenu_child_name[<?php echo $language['language_id']; ?>]" value="<?php echo isset($custommenu_child_desc[$custommenu['custommenu_id']][$language['language_id']]['name']) ? $custommenu_child_desc[$custommenu['custommenu_id']][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $text_custommenu_name; ?>" class="form-control input-full-width" />
												</div>
												<?php if (isset($error_group[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_group[$language['language_id']]; ?></div>
												<?php } ?>												
												<?php } else { ?>
												<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
													<input type="text" name="custommenu_name[<?php echo $language['language_id']; ?>]" value="<?php echo isset($custommenu_desc[$custommenu['custommenu_id']][$language['language_id']]['name']) ? $custommenu_desc[$custommenu['custommenu_id']][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $text_custommenu_name; ?>" class="form-control input-full-width" />
												</div>
												<?php if (isset($error_group[$language['language_id']])) { ?>
													<div class="text-danger"><?php echo $error_group[$language['language_id']]; ?></div>
												<?php } ?>
												<?php } ?>
											<?php } ?>
											<br>
                                            <?php if ($custommenu['custommenu_type'] == 'custom') { ?>
                                                <?php echo $text_custommenu_link;?><br>
                                                <?php foreach ($languages as $language) { ?>
                                                  <?php if($custommenu['isSubcustommenu']) { ?>
                                                  <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                                                    <input type="text" name="custommenu_child_link[<?php echo $language['language_id']; ?>]" value="<?php echo isset($custommenu_child_desc[$custommenu['custommenu_id']][$language['language_id']]['link']) ? $custommenu_child_desc[$custommenu['custommenu_id']][$language['language_id']]['link'] : ''; ?>" placeholder="<?php echo $text_custommenu_link; ?>" class="form-control input-full-width" />
                                                  </div>
                                                  <?php } else { ?>
                                                  <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                                                    <input type="text" name="custommenu_link[<?php echo $language['language_id']; ?>]" value="<?php echo isset($custommenu_desc[$custommenu['custommenu_id']][$language['language_id']]['link']) ? $custommenu_desc[$custommenu['custommenu_id']][$language['language_id']]['link'] : ''; ?>" placeholder="<?php echo $text_custommenu_link; ?>" class="form-control input-full-width" />
                                                  </div>
                                                  <?php } ?>
                                                <?php } ?>
                                                <br>
                                            <?php } else { ?>
                                                <?php foreach ($languages as $language) { ?>
                                                    <?php if($custommenu['isSubcustommenu']) { ?>
                                                        <input type="hidden" name="custommenu_child_link[<?php echo $language['language_id']; ?>]" value="<?php echo isset($custommenu_child_desc[$custommenu['custommenu_id']][$language['language_id']]['link']) ? $custommenu_child_desc[$custommenu['custommenu_id']][$language['language_id']]['link'] : ''; ?>" />
                                                    <?php } else { ?>
                                                        <input type="hidden" name="custommenu_link[<?php echo $language['language_id']; ?>]" value="<?php echo isset($custommenu_desc[$custommenu['custommenu_id']][$language['language_id']]['link']) ? $custommenu_desc[$custommenu['custommenu_id']][$language['language_id']]['link'] : ''; ?>" />
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
										    <?php if(empty($custommenu['isSubcustommenu'])) { ?>
											<?php echo $entry_columns; ?>
											<div class="input-group">
											  <input type="text" name="custommenu_columns" value="<?php echo isset($custommenu_desc[$custommenu['custommenu_id']][$language['language_id']]['columns']) ? $custommenu_desc[$custommenu['custommenu_id']][$language['language_id']]['columns'] : ''; ?>" placeholder="<?php echo $entry_columns; ?>" id="input-columns" class="form-control input-full-width" />
										    </div>
										    <br />
											<?php } ?>
                                            <?php if(count($stores) > 0) { ?>
                                            <?php echo $entry_store; ?>
                                            <br />
                                            <div class="well well-sm" style="height: 100%; max-height: 150px;  margin-right: 10px; overflow: auto;padding-right: 10px; margin-bottom: 5px;">
                                                <div class="checkbox">
                                                    <label>
                                                        <?php if (in_array(0, $custommenu['store'])) { ?>
                                                        <input type="checkbox" name="custommenu_store[]" value="0" checked="checked" />
                                                        <?php echo $text_default; ?>
                                                        <?php } else { ?>
                                                        <input type="checkbox" name="custommenu_store[]" value="0" />
                                                        <?php echo $text_default; ?>
                                                        <?php } ?>
                                                    </label>
                                                </div>
                                                <?php foreach ($stores as $store) { ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <?php if (in_array($store['store_id'], $custommenu['store'])) { ?>
                                                        <input type="checkbox" name="custommenu_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                                        <?php echo $store['name']; ?>
                                                        <?php } else { ?>
                                                        <input type="checkbox" name="custommenu_store[]" value="<?php echo $store['store_id']; ?>" />
                                                        <?php echo $store['name']; ?>
                                                        <?php } ?>
                                                    </label>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <?php } ?>
										    <div class="pull-right">
											<?php if(empty($custommenu['status'])) { ?>
												<a id="enablecustommenu-<?php echo $count; ?>" onclick="statuscustommenu('enable', '<?php echo $custommenu['custommenu_id']; ?>', 'custommenu-<?php echo $delteItem; ?>item-<?php echo $custommenu['custommenu_id']; ?>', 'enablecustommenu-<?php echo $count; ?>')" data-type="iframe" data-toggle="tooltip" style="top:2px!important;font-size:1.2em !important;" title="<?php echo $button_enable; ?>" class="btn btn-success btn-xs btn-edit btn-group"><i class="fa fa-check-circle"></i></a>
											<?php } else { ?>
												<a id="disablecustommenu-<?php echo $count; ?>" onclick="statuscustommenu('disable', '<?php echo $custommenu['custommenu_id']; ?>', 'custommenu-<?php echo $delteItem; ?>item-<?php echo $custommenu['custommenu_id']; ?>', 'disablecustommenu-<?php echo $count; ?>')" data-type="iframe" data-toggle="tooltip" style="top:2px!important;font-size:1.2em !important;" title="<?php echo $button_disable; ?>" class="btn btn-danger btn-xs btn-edit btn-group"><i class="fa fa-times-circle"></i></a>
											<?php }?>
												<a onclick="savecustommenu('custommenu-item-settings-edit-<?php echo $delteItem; ?><?php echo $custommenu['custommenu_id']; ?>', 'custommenu-<?php echo $delteItem; ?>item-<?php echo $custommenu['custommenu_id']; ?>')" data-type="iframe" data-toggle="tooltip" style="top:2px!important;font-size:1.2em !important;" title="<?php echo $button_save; ?>" class="btn btn-success btn-xs btn-edit btn-group"><i class="fa fa-save"></i></a>
												<button type="button" data-toggle="tooltip" title="" style="top:2px!important;font-size:1.2em !important;" class="btn btn-danger btn-xs btn-edit btn-group btn-loading" onclick="confirm('<?php echo $text_confirm; ?>') ? deletecustommenu('<?php echo $custommenu['custommenu_id']; ?>', 'custommenu-<?php echo $delteItem; ?>item-<?php echo $custommenu['custommenu_id']; ?>') : false;" data-original-title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></button>	
											</div>
										    <br>
										    <br>
										<?php (!empty($custommenu['isSubcustommenu'])) ? $custommenuID = 'Childcustommenu-' : $custommenuID = 'Maincustommenu-'; ?>
											<input class="custommenu-item-data-typecustommenu" type="hidden" name="custommenu-item-typecustommenu[<?php echo  $custommenuID . $custommenu['custommenu_id']; ?>]" value="<?php echo (!empty($custommenu['isSubcustommenu'])) ? 'Childcustommenu' : 'Maincustommenu' ; ?>">
											<input class="custommenu-item-data-db-id" type="hidden" name="custommenu-item-db-id[<?php echo  $custommenuID . $custommenu['custommenu_id']; ?>]" value="<?php echo $custommenu['custommenu_id']; ?>">
											<input class="custommenu-item-data-parent-id" type="hidden" name="custommenu-item-parent-id[<?php echo $custommenuID . $custommenu['custommenu_id']; ?>]" value="<?php echo ($custommenu['isSubcustommenu']) ? $custommenu['isSubcustommenu'] : '0' ; ?>">
											<input class="custommenu-item-data-position" type="hidden" name="custommenu-item-position[<?php echo $custommenuID . $custommenu['custommenu_id']; ?>]" value="<?php echo $custommenu['custommenu_id']; ?>">
											<input class="custommenu-item-data-type" type="hidden" name="custommenu-item-type[<?php echo $custommenuID . $custommenu['custommenu_id']; ?>]" value="post_type">
										</div>
										<ul class="custommenu-item-transport"></ul>
									</li>
								<?php } ?>	
								</ul>
						</div>
					</div>
				</div>		
			</form>				
		</div>      
      </div>
    </div>
  </div>
</div>  
<script type="text/javascript">
var changecustommenuPosition     = '<?php echo str_replace('amp;', '', $changecustommenuPosition); ?>';

var token 				   = '<?php echo $token; ?>';
var addcustommenuHref			   = '<?php echo str_replace('amp;', '', $add);?>';

var savecustommenuHref 		   = '<?php echo str_replace('amp;', '', $save); ?>';

var statuscustommenuEnable 	   = '<?php echo str_replace('amp;', '', $enablecustommenu); ?>';
var statuscustommenuDisable 	   = '<?php echo str_replace('amp;', '', $disablecustommenu); ?>';

var statuscustommenuChildEnable  = '<?php echo str_replace('amp;', '', $enableChildcustommenu); ?>';
var statuscustommenuChildDisable = '<?php echo str_replace('amp;', '', $disableChildcustommenu); ?>';

var deletecustommenuHref 		   = '<?php echo str_replace('amp;', '', $deletecustommenu); ?>';
var deletecustommenuChildHref    = '<?php echo str_replace('amp;', '', $deleteChildcustommenu); ?>';
</script>
<?php echo $footer; ?>
