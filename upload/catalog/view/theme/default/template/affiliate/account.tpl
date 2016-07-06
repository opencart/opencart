{{ header }}
<div class="container">
  <ul class="breadcrumb">
    {% for breadcrumb in breadcrumbs %}
    <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>
    {% endfor %}
  </ul>
  {% if success %}
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ success }}</div>
  <?php } ?>
  <div class="row">{{ column_left }}
    {% if column_left and column_right %}
    {% set class = 'col-sm-6' %}
    {% elseif column_left or column_right %}
    {% set class = 'col-sm-9' %}
    {% else %}
    {% set class = 'col-sm-12' %}
    <?php } ?>
    <div id="content" class="{{ class }}">{{ content_top }}
      <h2>{{ text_my_account }}</h2>
      <ul class="list-unstyled">
        <li><a href="{{ edit }}">{{ text_edit }}</a></li>
        <li><a href="{{ password }}">{{ text_password }}</a></li>
        <li><a href="{{ payment }}">{{ text_payment }}</a></li>
      </ul>
      <h2>{{ text_my_tracking }}</h2>
      <ul class="list-unstyled">
        <li><a href="{{ tracking }}">{{ text_tracking }}</a></li>
      </ul>
      <h2>{{ text_my_transactions }}</h2>
      <ul class="list-unstyled">
        <li><a href="{{ transaction }}">{{ text_transaction }}</a></li>
      </ul>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }}