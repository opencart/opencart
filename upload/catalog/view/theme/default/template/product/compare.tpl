{{ header }}
<div class="container">
  <ul class="breadcrumb">
    {% for breadcrumb in breadcrumbs %}
    <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>
    {% endfor %}
  </ul>
  {% if success %}
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ success }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
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
      <h1>{{ heading_title }}</h1>
      {% if products %}
      <table class="table table-bordered">
        <thead>
          <tr>
            <td colspan="<?php echo count($products) + 1; ?>"><strong>{{ text_product }}</strong></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ text_name }}</td>
            {% for product in products %}
            <td><a href="{{ product.href }}"><strong>{{ product.name }}</strong></a></td>
            <?php } ?>
          </tr>
          <tr>
            <td>{{ text_image }}</td>
            {% for product in products %}
            <td class="text-center">{% if product.thumb %}
              <img src="{{ product.thumb }}" alt="{{ product.name }}" title="{{ product.name }}" class="img-thumbnail" />
              <?php } ?></td>
            <?php } ?>
          </tr>
          <tr>
            <td>{{ text_price }}</td>
            {% for product in products %}
            <td>{% if product.price %}
              {% if !$product.special %}
              {{ product.price }}
              {% else %}
              <strike>{{ product.price }}</strike> {{ product.special }}
              <?php } ?>
              <?php } ?></td>
            <?php } ?>
          </tr>
          <tr>
            <td>{{ text_model }}</td>
            {% for product in products %}
            <td>{{ product.model }}</td>
            <?php } ?>
          </tr>
          <tr>
            <td>{{ text_manufacturer }}</td>
            {% for product in products %}
            <td>{{ product.manufacturer }}</td>
            <?php } ?>
          </tr>
          <tr>
            <td>{{ text_availability }}</td>
            {% for product in products %}
            <td>{{ product.availability }}</td>
            <?php } ?>
          </tr>
          {% if review_status %}
          <tr>
            <td>{{ text_rating }}</td>
            {% for product in products %}
            <td class="rating"><?php for ($i = 1; $i <= 5; $i++) { ?>
              {% if product['rating'] < $i %}
              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
              {% else %}
              <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
              <?php } ?>
              <?php } ?>
              <br />
              {{ product.reviews }}</td>
            <?php } ?>
          </tr>
          <?php } ?>
          <tr>
            <td>{{ text_summary }}</td>
            {% for product in products %}
            <td class="description">{{ product.description }}</td>
            <?php } ?>
          </tr>
          <tr>
            <td>{{ text_weight }}</td>
            {% for product in products %}
            <td>{{ product.weight }}</td>
            <?php } ?>
          </tr>
          <tr>
            <td>{{ text_dimension }}</td>
            {% for product in products %}
            <td>{{ product.length }} x {{ product.width }} x {{ product.height }}</td>
            <?php } ?>
          </tr>
        </tbody>
        {% for attribute_group in attribute_groups %}
        <thead>
          <tr>
            <td colspan="<?php echo count($products) + 1; ?>"><strong>{{ attribute_group.name }}</strong></td>
          </tr>
        </thead>
        {% for attribute in attribute_group.attribute|keys) %}
        <tbody>
          <tr>
            <td>{{ attribute.name }}</td>
            {% for product in products %}
            {% if isset($product['attribute'][{{ key }}])) { ?>
            <td><?php echo $product['attribute'][$key]; ?></td>
            {% else %}
            <td></td>
            <?php } ?>
            <?php } ?>
          </tr>
        </tbody>
        <?php } ?>
        <?php } ?>
        <tr>
          <td></td>
          {% for product in products %}
          <td><input type="button" value="{{ button_cart }}" class="btn btn-primary btn-block" onclick="cart.add('{{ product.product_id }}', '{{ product.minimum }}');" />
            <a href="{{ product.remove }}" class="btn btn-danger btn-block">{{ button_remove }}</a></td>
          <?php } ?>
        </tr>
      </table>
      {% else %}
      <p>{{ text_empty }}</p>
      <div class="buttons">
        <div class="pull-right"><a href="{{ continue }}" class="btn btn-default">{{ button_continue }}</a></div>
      </div>
      <?php } ?>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }}
