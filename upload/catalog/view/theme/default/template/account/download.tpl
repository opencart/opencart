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
      <h2>{{ heading_title }}</h2>
      {% if downloads) { ?>
      <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-right">{{ column_order_id; }}</td>
            <td class="text-left">{{ column_name; }}</td>
            <td class="text-left">{{ column_size; }}</td>
            <td class="text-left">{{ column_date_added; }}</td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($downloads as $download) { ?>
          <tr>
            <td class="text-right"><?php echo $download['order_id']; ?></td>
            <td class="text-left"><?php echo $download['name']; ?></td>
            <td class="text-left"><?php echo $download['size']; ?></td>
            <td class="text-left"><?php echo $download['date_added']; ?></td>
            <td><a href="<?php echo $download['href']; ?>" data-toggle="tooltip" title="{{ button_download }}" class="btn btn-primary"><i class="fa fa-cloud-download"></i></a></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left">{{ pagination }}</div>
        <div class="col-sm-6 text-right">{{ results }}</div>
      </div>
      <?php } else { ?>
      <p>{{ text_empty }}</p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="{{ continue }}" class="btn btn-primary">{{ button_continue }}</a></div>
      </div>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }}