<?php echo $header; ?>
<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
	<div class="left"></div>
	<div class="right"></div>
	<div class="heading">
		<h1 style="background-image: url('view/image/shipping.png');"><?php echo $heading_title; ?></h1>
		<div class="buttons">
			<a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
			<a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
			<div style="display: inline-block; width: 100%;">
				<div id="tabs" class="vtabs">
					<a tab="#tab_general"><?php echo $tab_general; ?></a>
					<a tab="#tab_allzones"><?php echo $tab_allzones; ?></a>
					<?php foreach ($geo_zones as $geo_zone) { ?>
						<a tab="#tab_geo_zone<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></a>
					<?php } ?>
				</div>
				<div id="tab_general" class="vtabs_page">
					<table class="form">
						<tr>
							<td><?php echo $entry_status; ?></td>
							<td><select name="weight_status">
									<?php if ($weight_status) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_sort_order; ?></td>
							<td><input type="text" name="weight_sort_order" value="<?php echo $weight_sort_order; ?>" size="1" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_tax; ?></td>
							<td><select name="weight_tax_class_id">
									<option value="0"><?php echo $text_none; ?></option>
									<?php foreach ($tax_classes as $tax_class) { ?>
										<?php if ($tax_class['tax_class_id'] == $weight_tax_class_id) { ?>
											<option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div id="tab_allzones" class="vtabs_page">
					<table class="form">
						<tr>
							<td><?php echo $entry_status; ?></td>
							<td><select name="weight_allzones_status">
									<option value="1"<?php if ($weight_allzones_status == '1') echo ' selected="selected"'; ?>><?php echo $text_enabled; ?></option>
									<option value="2"<?php if ($weight_allzones_status == '2') echo ' selected="selected"'; ?>><?php echo $text_enabled_no_geo; ?></option>
									<option value="0"<?php if ($weight_allzones_status == '0') echo ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td><?php echo $entry_base_cost; ?></td>
							<td><input type="text" size="4" name="weight_allzones_basecost" value="<?php echo $weight_allzones_basecost; ?>" /></td>
						</tr>
						<tr>
							<td><?php echo $entry_rate; ?></td>
							<td><textarea name="weight_allzones_rate" cols="40" rows="5"><?php echo $weight_allzones_rate; ?></textarea></td>
						</tr>
					</table>
				</div>
				<?php foreach ($geo_zones as $geo_zone) { ?>
					<div id="tab_geo_zone<?php echo $geo_zone['geo_zone_id']; ?>" class="vtabs_page">
						<table class="form">
							<tr>
								<td><?php echo $entry_status; ?></td>
								<td><select name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_status">
										<?php if (${'weight_' . $geo_zone['geo_zone_id'] . '_status'}) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td><?php echo $entry_base_cost; ?></td>
								<td><input type="text" size="4" name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_basecost" value="<?php echo ${'weight_' . $geo_zone['geo_zone_id'] . '_basecost'}; ?>" /></td>
							</tr>
							<tr>
								<td><?php echo $entry_rate; ?></td>
								<td><textarea name="weight_<?php echo $geo_zone['geo_zone_id']; ?>_rate" cols="40" rows="5"><?php echo ${'weight_' . $geo_zone['geo_zone_id'] . '_rate'}; ?></textarea></td>
							</tr>
						</table>
					</div>
				<?php } ?>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript"><!--
	$.tabs('#tabs a'); 
//--></script>
<?php echo $footer; ?>