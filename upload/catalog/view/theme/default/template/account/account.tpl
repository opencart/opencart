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
        <li><a href="{{ address }}">{{ text_address }}</a></li>
        <li><a href="{{ wishlist }}">{{ text_wishlist }}</a></li>
      </ul>
      {% if credit_cards %}
      <h2>{{ text_credit_card }}</h2>
      <ul class="list-unstyled">
       {% for credit_card in credit_cards %}
        <li><a href="{{ credit_card.href }}">{{ credit_card.name }}</a></li>
        <?php } ?>
      </ul>
      <?php } ?>
      <h2>{{ text_my_orders }}</h2>
      <ul class="list-unstyled">
        <li><a href="{{ order }}">{{ text_order }}</a></li>
        <li><a href="{{ download }}">{{ text_download }}</a></li>
        {% if reward %}
        <li><a href="{{ reward }}">{{ text_reward }}</a></li>
        <?php } ?>
        <li><a href="{{ return }}">{{ text_return }}</a></li>
        <li><a href="{{ transaction }}">{{ text_transaction }}</a></li>
        <li><a href="{{ recurring }}">{{ text_recurring }}</a></li>
      </ul>
      <h2>{{ text_my_newsletter }}</h2>
      <ul class="list-unstyled">
        <li><a href="{{ newsletter }}">{{ text_newsletter }}</a></li>
      </ul>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }} 