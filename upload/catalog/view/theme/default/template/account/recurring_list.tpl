{{ header }}
<div class="container">
  <ul class="breadcrumb">
    {% for breadcrumb in breadcrumbs %}
    <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>
    {% endfor %}
  </ul>
  <div class="row">{{ column_left }}
    {% if column_left and column_right %}
    <?php $class = 'col-sm-6'; ?>
    {% elseif column_left || column_right %}
    <?php $class = 'col-sm-9'; ?>
    {% else %}
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="{{ class }}">{{ content_top }}
      <h1>{{ heading_title }}</h1>
      {% if recurrings %}
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-right">{{ column_order_recurring_id; }}</td>
              <td class="text-left">{{ column_product; }}</td>
              <td class="text-left">{{ column_status; }}</td>
              <td class="text-left">{{ column_date_added; }}</td>
              <td class="text-right"></td>
            </tr>
          </thead>
          <tbody>
           {% for recurring in recurrings %}
            <tr>
              <td class="text-right">#{{ recurring.order_recurring_id }}</td>
              <td class="text-left">{{ recurring.product }}</td>
              <td class="text-left">{{ recurring.status }}</td>
              <td class="text-left">{{ recurring.date_added }}</td>
              <td class="text-right"><a href="{{ recurring.view }}" data-toggle="tooltip" title="{{ button_view }}" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="text-right">{{ pagination }}</div>
      {% else %}
      <p>{{ text_empty }}</p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="{{ continue }}" class="btn btn-primary">{{ button_continue }}</a></div>
      </div>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }}