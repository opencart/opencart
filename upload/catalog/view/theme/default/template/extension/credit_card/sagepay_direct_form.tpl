{{ header }}
<div class="container">
  <ul class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
  </ul>
  <div class="row">{{ column_left }}
	{% if column_left && $column_right) { ?>
		<?php $class = 'col-sm-6'; ?>
	<?php } elseif ($column_left || $column_right) { ?>
		<?php $class = 'col-sm-9'; ?>
	<?php } else { ?>
		<?php $class = 'col-sm-12'; ?>
	<?php } ?>
    <div id="content" class="{{ class }}">{{ content_top }}
      <h1>{{ heading_title }}</h1>
	  <form class="form-horizontal" action="{{ add }}" method="post">
		<fieldset id="payment">
		  <div id="card-new">
			<div class="form-group required">
			  <label class="col-sm-2 control-label" for="input-cc-owner">{{ entry_cc_owner }}</label>
			  <div class="col-sm-10">
				<input type="text" name="cc_owner" value="" placeholder="{{ entry_cc_owner }}" id="input-cc-owner" class="form-control" />
			  </div>
			</div>
			<div class="form-group required">
			  <label class="col-sm-2 control-label" for="input-cc-type">{{ entry_cc_type }}</label>
			  <div class="col-sm-10">
				<select name="cc_type" id="input-cc-type" class="form-control">
				  <?php foreach ($cards as $card) { ?>
					  <option value="<?php echo $card['value']; ?>"><?php echo $card['text']; ?></option>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="form-group required">
			  <label class="col-sm-2 control-label" for="input-cc-number">{{ entry_cc_number }}</label>
			  <div class="col-sm-10">
				<input type="text" name="cc_number" value="" placeholder="{{ entry_cc_number }}" id="input-cc-number" class="form-control" />
			  </div>
			</div>
			<div class="form-group required">
			  <label class="col-sm-2 control-label" for="input-cc-expire-date">{{ entry_cc_expire_date }}</label>
			  <div class="col-sm-3">
				<select name="cc_expire_date_month" id="input-cc-expire-date" class="form-control">
				  <?php foreach ($months as $month) { ?>
					  <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
				  <?php } ?>
				</select>
			  </div>
			  <div class="col-sm-3">
				<select name="cc_expire_date_year" class="form-control">
				  <?php foreach ($year_expire as $year) { ?>
					  <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="form-group required">
			  <label class="col-sm-2 control-label" for="input-cc-cvv2">{{ entry_cc_cvv2 }}</label>
			  <div class="col-sm-10">
				<input type="text" name="cc_cvv2" value="" placeholder="{{ entry_cc_cvv2 }}" id="input-cc-cvv2" class="form-control" />
			  </div>
			</div>
		  </div>
		  <div class="buttons clearfix">
			<div class="pull-left"><a href="{{ back }}" class="btn btn-default">{{ button_back }}</a></div>
			<div class="pull-right"><input type="submit" value="{{ button_add_card }}" class="btn btn-primary" /></div>
		  </div>
		</fieldset>
	  </form>
	  {{ content_bottom }}</div>
	{{ column_right }}</div>
</div>
{{ footer }}
