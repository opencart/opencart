{{ header }}
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  {% if error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}</div>
  <?php } ?>
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
      <p>{{ text_description }}</p>
      <form action="{{ action }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-to-name">{{ entry_to_name }}</label>
          <div class="col-sm-10">
            <input type="text" name="to_name" value="{{ to_name }}" id="input-to-name" class="form-control" />
            {% if error_to_name) { ?>
            <div class="text-danger">{{ error_to_name }}</div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-to-email">{{ entry_to_email }}</label>
          <div class="col-sm-10">
            <input type="text" name="to_email" value="{{ to_email }}" id="input-to-email" class="form-control" />
            {% if error_to_email) { ?>
            <div class="text-danger">{{ error_to_email }}</div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-from-name">{{ entry_from_name }}</label>
          <div class="col-sm-10">
            <input type="text" name="from_name" value="{{ from_name }}" id="input-from-name" class="form-control" />
            {% if error_from_name) { ?>
            <div class="text-danger">{{ error_from_name }}</div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-from-email">{{ entry_from_email }}</label>
          <div class="col-sm-10">
            <input type="text" name="from_email" value="{{ from_email }}" id="input-from-email" class="form-control" />
            {% if error_from_email) { ?>
            <div class="text-danger">{{ error_from_email }}</div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label">{{ entry_theme }}</label>
          <div class="col-sm-10">
            <?php foreach ($voucher_themes as $voucher_theme) { ?>
            {% if voucher_theme['voucher_theme_id'] == $voucher_theme_id) { ?>
            <div class="radio">
              <label>
                <input type="radio" name="voucher_theme_id" value="<?php echo $voucher_theme['voucher_theme_id']; ?>" checked="checked" />
                <?php echo $voucher_theme['name']; ?></label>
            </div>
            <?php } else { ?>
            <div class="radio">
              <label>
                <input type="radio" name="voucher_theme_id" value="<?php echo $voucher_theme['voucher_theme_id']; ?>" />
                <?php echo $voucher_theme['name']; ?></label>
            </div>
            <?php } ?>
            <?php } ?>
            {% if error_theme) { ?>
            <div class="text-danger">{{ error_theme }}</div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-message"><span data-toggle="tooltip" title="{{ help_message }}">{{ entry_message }}</span></label>
          <div class="col-sm-10">
            <textarea name="message" cols="40" rows="5" id="input-message" class="form-control">{{ message }}</textarea>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-amount"><span data-toggle="tooltip" title="{{ help_amount }}">{{ entry_amount }}</span></label>
          <div class="col-sm-10">
            <input type="text" name="amount" value="{{ amount }}" id="input-amount" class="form-control" size="5" />
            {% if error_amount) { ?>
            <div class="text-danger">{{ error_amount }}</div>
            <?php } ?>
          </div>
        </div>
        <div class="buttons clearfix">
          <div class="pull-right"> {{ text_agree }}
            {% if agree) { ?>
            <input type="checkbox" name="agree" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="agree" value="1" />
            <?php } ?>
            &nbsp;
            <input type="submit" value="{{ button_continue }}" class="btn btn-primary" />
          </div>
        </div>
      </form>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }}