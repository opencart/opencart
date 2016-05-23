<?php echo $header; ?> <?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-free-checkout" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-divido" class="form-horizontal">
                  
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="api_key"><span data-toggle="tooltip" title="<?php echo $help_api_key; ?>"><?php echo $entry_api_key; ?></span></label>
                        <div class="col-sm-10">
                            <input id="api_key" class="form-control" type="text" name="divido_api_key" value="<?php echo $divido_api_key; ?>" size="60"></td>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="divido_status"><span data-toggle="tooltip" title="<?php echo $help_status; ?>"><?php echo $entry_status; ?></span></label>
                        <div class="col-sm-10">
                            <select name="divido_status" id="divido_status" class="form-control">
                                <?php if ($divido_status) { ?>
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
                        <label class="col-sm-2 control-label" for="divido_order_status_id"><span data-toggle="tooltip" title="<?php echo $help_order_status; ?>"><?php echo $entry_order_status; ?></span></label>
                        <div class="col-sm-10">
                            <select name="divido_order_status_id" id="divido_order_status_id" class="form-control">
                                <?php  foreach ($order_statuses as $order_status): ?>
                                <?php $selected = ($order_status['order_status_id'] == $divido_order_status_id) ? 'selected' : ''; ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" <?php echo $selected; ?>>
                                <?php echo $order_status['name']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="divido_title"><span data-toggle="tooltip" title="<?php echo $help_title; ?>"><?php echo $entry_title; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" id="divido_title" name="divido_title" value="<?php echo $divido_title; ?>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="divido_planselection"><span data-toggle="tooltip" title="<?php echo $help_planselection; ?>"><?php echo $entry_planselection; ?></span></label>
                        <div class="col-sm-10">
                            <select name="divido_planselection" id="divido_planselection" class="form-control">
                                <?php foreach ($entry_plans_options as $option => $text): $selected = $option == $divido_planselection ? 'selected' : null; ?>
                                <option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div id="plan-list" class="form-group">
                        <label class="col-sm-2 control-label"><?php echo $entry_planlist; ?></label>
                        <div class="col-sm-10">
                            <?php foreach ($divido_plans as $plan): $checked = in_array($plan->id, $divido_plans_selected) ? 'checked' : null; ?>
                            <label>
                                <input type="checkbox" name="divido_plans_selected[]" value="<?php echo $plan->id; ?>" <?php echo $checked; ?>>
                                <?php echo "{$plan->text} ({$plan->interest_rate}% APR)"; ?>
                            </label><br>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="divido_productselection"><span data-toggle="tooltip" title="<?php echo $help_productselection; ?>"><?php echo $entry_productselection; ?></span></label>
                        <div class="col-sm-10">
                            <select name="divido_productselection" id="divido_productselection" class="form-control">
                                <?php foreach ($entry_products_options as $option => $text): $selected = $option == $divido_productselection ? 'selected' : null; ?>
                                <option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div id="threshold" class="form-group">
                        <label class="col-sm-2 control-label" for="divido_price_threshold"><?php echo $entry_price_threshold; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="divido_price_threshold" value="<?php echo $divido_price_threshold; ?>" class="form-control" id="divido_price_threshold">
                        </div>
                    </div>

                    <div id="cart-threshold" class="form-group">
                        <label class="col-sm-2 control-label" for="divido_cart_threshold"><span data-toggle="tooltip" title="<?php echo $help_cart_threshold; ?>"><?php echo $entry_cart_threshold; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="divido_cart_threshold" value="<?php echo $divido_cart_threshold; ?>" class="form-control" id="divido_cart_threshold">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="sort_order"><?php echo $entry_sort_order; ?></label>
                        <div class="col-sm-10">
                            <input type="text" id="divido_sort_order" class="form-control" name="divido_sort_order" value="<?php echo $divido_sort_order; ?>" size="1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
                        <div class="col-sm-10">
                            <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="category" class="form-control" />
                            <div id="divido-category" class="well well-sm" style="height: 150px; overflow: auto;">
                            <?php foreach ($categories as $category) { ?>
                                <div id="category<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
                                    <input type="hidden" name="divido_categories[]" value="<?php echo $category['category_id']; ?>" />
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
(function($) {
    var divido = {
        initialize: function () {
            this.bindEvents();
            this.toggleFields();
        },

        bindEvents: function () {
            $('#divido_productselection').on('change', this.toggleFields);
            $('#divido_planselection').on('change', this.toggleFields);

        },

        toggleFields: function () {
            var $apiKeyField = $('#api_key');

            if ($apiKeyField.val().length < 1) {
                $apiKeyField.closest('.form-group').siblings().hide();
            }

            var productSelection = $('#divido_productselection').val();
            var $threshold       = $('#threshold');
            if (productSelection == 'threshold') {
                $threshold.show();
            } else {
                $threshold.hide();
            }

            var planSelection = $('#divido_planselection').val();
            var $planList     = $('#plan-list');
            if (planSelection == 'selected') {
                $planList.show();
            } else {
                $planList.hide();
            }
        }
    };

    $(function () {
        divido.initialize();
    });

	$('input[name="category"]').autocomplete({
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['category_id']
						}
					}));
				}
			});
		},
		select: function(item) {
			$('input[name=\'category\']').val('');
			$('#divido-category' + item['value']).remove();
			$('#divido-category').append('<div id="divido-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="divido_categories[]" value="' + item['value'] + '" /></div>');
		}
	});

	$('#divido-category').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});

})(jQuery);
</script>
<?php echo $footer; ?>
