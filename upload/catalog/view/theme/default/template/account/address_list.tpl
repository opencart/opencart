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
  <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}</div>
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
      <h2>{{ text_address_book }}</h2>
      {% if addresses) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <?php foreach ($addresses as $result) { ?>
          <tr>
            <td class="text-left"><?php echo $result['address']; ?></td>
            <td class="text-right"><a href="<?php echo $result['update']; ?>" class="btn btn-info">{{ button_edit }}</a> &nbsp; <a href="<?php echo $result['delete']; ?>" class="btn btn-danger">{{ button_delete }}</a></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <?php } else { ?>
      <p>{{ text_empty }}</p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-left"><a href="{{ back }}" class="btn btn-default">{{ button_back }}</a></div>
        <div class="pull-right"><a href="{{ add }}" class="btn btn-primary">{{ button_new_address }}</a></div>
      </div>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }}