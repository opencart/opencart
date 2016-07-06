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
      <div class="row">
        <div class="col-sm-6">
          <ul>
            <?php foreach ($categories as $category_1) { ?>
            <li><a href="<?php echo $category_1['href']; ?>"><?php echo $category_1['name']; ?></a>
              {% if category_1['children']) { ?>
              <ul>
                <?php foreach ($category_1['children'] as $category_2) { ?>
                <li><a href="<?php echo $category_2['href']; ?>"><?php echo $category_2['name']; ?></a>
                  {% if category_2['children']) { ?>
                  <ul>
                    <?php foreach ($category_2['children'] as $category_3) { ?>
                    <li><a href="<?php echo $category_3['href']; ?>"><?php echo $category_3['name']; ?></a></li>
                    <?php } ?>
                  </ul>
                  <?php } ?>
                </li>
                <?php } ?>
              </ul>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
        </div>
        <div class="col-sm-6">
          <ul>
            <li><a href="{{ special }}">{{ text_special }}</a></li>
            <li><a href="{{ account }}">{{ text_account }}</a>
              <ul>
                <li><a href="{{ edit }}">{{ text_edit }}</a></li>
                <li><a href="{{ password }}">{{ text_password }}</a></li>
                <li><a href="{{ address }}">{{ text_address }}</a></li>
                <li><a href="{{ history }}">{{ text_history }}</a></li>
                <li><a href="{{ download }}">{{ text_download }}</a></li>
              </ul>
            </li>
            <li><a href="{{ history }}">{{ text_cart }}</a></li>
            <li><a href="{{ checkout }}">{{ text_checkout }}</a></li>
            <li><a href="{{ search }}">{{ text_search }}</a></li>
            <li>{{ text_information }}
              <ul>
                <?php foreach ($informations as $information) { ?>
                <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
                <?php } ?>
                <li><a href="{{ contact }}">{{ text_contact }}</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }}