{{ header }}
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  {% if success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ success }}</div>
  <?php } ?>
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
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left">{{ column_type; }}</td>
              <td class="text-left">{{ column_digits; }}</td>
              <td class="text-right">{{ column_expiry; }}</td>
            </tr>
          </thead>
          <tbody>
            {% if cards) { ?>
            <?php foreach ($cards  as $card) { ?>
            <tr>
              <td class="text-left"><?php echo $card['type']; ?></td>
              <td class="text-left"><?php echo $card['digits']; ?></td>
              <td class="text-right"><?php echo $card['expiry']; ?></td>
			  <td class="text-right"><a href="<?php echo $delete . $card['card_id']; ?>" class="btn btn-danger">{{ button_delete }}</a></td>

            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="5">{{ text_empty }}</td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left">{{ pagination }}</div>
        <div class="col-sm-6 text-right">{{ results }}</div>
      </div>
	  <div class="buttons clearfix">
        <div class="pull-left"><a href="{{ back }}" class="btn btn-default">{{ button_back }}</a></div>
        <div class="pull-right"><a href="{{ add }}" class="btn btn-primary">{{ button_new_card }}</a></div>
      </div>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }}
