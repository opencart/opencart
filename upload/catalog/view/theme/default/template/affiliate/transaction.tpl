{{ header }}
<div class="container">
  <ul class="breadcrumb">
    {% for breadcrumb in breadcrumbs %}
    <li><a href="{{ breadcrumb.href }}"> {{ breadcrumb.text }} </a> </li>
    <?php } ?>
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
      <p>{{ text_balance; ?> <strong><?php echo $balance }}</strong>.</p>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left">{{ column_date_added; }}</td>
              <td class="text-left">{{ column_description; }}</td>
              <td class="text-right">{{ column_amount; }}</td>
            </tr>
          </thead>
          <tbody>
            {% if transactions) { ?>
            <?php foreach ($transactions  as $transaction) { ?>
            <tr>
              <td class="text-left">{{ transaction.date_added }}</td>
              <td class="text-left">{{ transaction.description }}</td>
              <td class="text-right">{{ transaction.amount }}</td>
            </tr>
            <?php } ?>
            {% else %}
            <tr>
              <td class="text-center" colspan="5">{{ text_empty }}</td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="text-right">{{ pagination }}</div>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="{{ continue }}" class="btn btn-primary">{{ button_continue }}</a></div>
      </div>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }}