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
      <h3>{{ text_location }}</h3>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            {% if image) { ?>
            <div class="col-sm-3"><img src="{{ image }}" alt="{{ store }}" title="{{ store }}" class="img-thumbnail" /></div>
            <?php } ?>
            <div class="col-sm-3"><strong>{{ store }}</strong><br />
              <address>
              {{ address }}
              </address>
              {% if geocode) { ?>
              <a href="https://maps.google.com/maps?q=<?php echo urlencode($geocode); ?>&hl={{ geocode_hl }}&t=m&z=15" target="_blank" class="btn btn-info"><i class="fa fa-map-marker"></i> {{ button_map }}</a>
              <?php } ?>
            </div>
            <div class="col-sm-3"><strong>{{ text_telephone }}</strong><br>
              {{ telephone }}<br />
              <br />
              {% if fax) { ?>
              <strong>{{ text_fax }}</strong><br>
              {{ fax }}
              <?php } ?>
            </div>
            <div class="col-sm-3">
              {% if open) { ?>
              <strong>{{ text_open }}</strong><br />
              {{ open }}<br />
              <br />
              <?php } ?>
              {% if comment) { ?>
              <strong>{{ text_comment }}</strong><br />
              {{ comment }}
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
      {% if locations) { ?>
      <h3>{{ text_store }}</h3>
      <div class="panel-group" id="accordion">
        <?php foreach ($locations as $location) { ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title"><a href="#collapse-location<?php echo $location['location_id']; ?>" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"><?php echo $location['name']; ?> <i class="fa fa-caret-down"></i></a></h4>
          </div>
          <div class="panel-collapse collapse" id="collapse-location<?php echo $location['location_id']; ?>">
            <div class="panel-body">
              <div class="row">
                {% if location['image']) { ?>
                <div class="col-sm-3"><img src="<?php echo $location['image']; ?>" alt="<?php echo $location['name']; ?>" title="<?php echo $location['name']; ?>" class="img-thumbnail" /></div>
                <?php } ?>
                <div class="col-sm-3"><strong><?php echo $location['name']; ?></strong><br />
                  <address>
                  <?php echo $location['address']; ?>
                  </address>
                  {% if location['geocode']) { ?>
                  <a href="https://maps.google.com/maps?q=<?php echo urlencode($location['geocode']); ?>&hl={{ geocode_hl }}&t=m&z=15" target="_blank" class="btn btn-info"><i class="fa fa-map-marker"></i> {{ button_map }}</a>
                  <?php } ?>
                </div>
                <div class="col-sm-3"> <strong>{{ text_telephone }}</strong><br>
                  <?php echo $location['telephone']; ?><br />
                  <br />
                  {% if location['fax']) { ?>
                  <strong>{{ text_fax }}</strong><br>
                  <?php echo $location['fax']; ?>
                  <?php } ?>
                </div>
                <div class="col-sm-3">
                  {% if location['open']) { ?>
                  <strong>{{ text_open }}</strong><br />
                  <?php echo $location['open']; ?><br />
                  <br />
                  <?php } ?>
                  {% if location['comment']) { ?>
                  <strong>{{ text_comment }}</strong><br />
                  <?php echo $location['comment']; ?>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <?php } ?>
      <form action="{{ action }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <legend>{{ text_contact }}</legend>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name">{{ entry_name }}</label>
            <div class="col-sm-10">
              <input type="text" name="name" value="{{ name }}" id="input-name" class="form-control" />
              {% if error_name) { ?>
              <div class="text-danger">{{ error_name }}</div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email">{{ entry_email }}</label>
            <div class="col-sm-10">
              <input type="text" name="email" value="{{ email }}" id="input-email" class="form-control" />
              {% if error_email) { ?>
              <div class="text-danger">{{ error_email }}</div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-enquiry">{{ entry_enquiry }}</label>
            <div class="col-sm-10">
              <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control">{{ enquiry }}</textarea>
              {% if error_enquiry) { ?>
              <div class="text-danger">{{ error_enquiry }}</div>
              <?php } ?>
            </div>
          </div>
          {{ captcha }}
        </fieldset>
        <div class="buttons">
          <div class="pull-right">
            <input class="btn btn-primary" type="submit" value="{{ button_submit }}" />
          </div>
        </div>
      </form>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }}
