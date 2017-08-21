{{ header }}{{ column_left }}
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-recurring" data-toggle="tooltip" title="{{ button_save }}" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="{{ cancel }}" data-toggle="tooltip" title="{{ button_cancel }}" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>{{ heading_title }}</h1>
      <ul class="breadcrumb">
        {% for breadcrumb in breadcrumbs %}
        <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>
        {% endfor %}
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    {% if error_warning %}
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {% endif %}
    <div class="alert alert-info">{{ text_recurring }}</div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> {{ text_form }}</h3>
      </div>
      <div class="panel-body">
        <form action="{{ action }}" method="post" enctype="multipart/form-data" id="form-recurring" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label">{{ entry_name }}</label>
            <div class="col-sm-10">
              {% for language in languages %}
              <div class="input-group"><span class="input-group-addon"><img src="language/{{ language.code }}/{{ language.code }}.png" title="{{ language.name }}" /></span>
                <input type="text" name="recurring_description[{{ language.language_id }}][name]" value="{{ recurring_description[language.language_id] ? recurring_description[language.language_id].name }}" placeholder="{{ entry_name }}" class="form-control" />
              </div>
              {% if error_name[language.language_id] %}
              <div class="text-danger">{{ error_name[language.language_id] }}</div>
              {% endif %}
              {% endfor %}
            </div>
          </div>
          <fieldset>
            <legend>{{ text_profile }}</legend>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-price">{{ entry_price }}</label>
              <div class="col-sm-10">
                <input type="text" name="price" value="{{ price }}" placeholder="{{ entry_price }}" id="input-price" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-duration">{{ entry_duration }}</label>
              <div class="col-sm-10">
                <input type="text" name="duration" value="{{ duration }}" placeholder="{{ entry_duration }}" id="input-duration" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-cycle">{{ entry_cycle }}</label>
              <div class="col-sm-10">
                <input type="text" name="cycle" value="{{ cycle }}" placeholder="{{ entry_cycle }}" id="input-cycle" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-frequency">{{ entry_frequency }}</label>
              <div class="col-sm-10">
                <select name="frequency" id="input-frequency" class="form-control">
                  {% for frequency_option in frequencies %}
                  {% if frequency == frequency_option.value %}
                  <option value="{{ frequency_option.value }}" selected="selected">{{ frequency_option.text }}</option>
                  {% else %}
                  <option value="{{ frequency_option.value }}">{{ frequency_option.text }}</option>
                  {% endif %}
                  {% endfor %}
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-status">{{ entry_status }}</label>
              <div class="col-sm-10">
                <select name="status" id="input-status" class="form-control">
                  {% if status %}
                  <option value="1" selected="selected">{{ text_enabled }}</option>
                  <option value="0">{{ text_disabled }}</option>
                  {% else %}
                  <option value="1">{{ text_enabled }}</option>
                  <option value="0" selected="selected">{{ text_disabled }}</option>
                  {% endif %}
                </select>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>{{ text_trial }}</legend>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-trial-price">{{ entry_trial_price }}</label>
              <div class="col-sm-10">
                <input type="text" name="trial_price" value="{{ trial_price }}" placeholder="{{ entry_trial_price }}" id="input-trial-price" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-trial-duration">{{ entry_trial_duration }}</label>
              <div class="col-sm-10">
                <input type="text" name="trial_duration" value="{{ trial_duration }}" placeholder="{{ entry_trial_duration }}" id="input-trial-duration" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-trial-cycle">{{ entry_trial_cycle }}</label>
              <div class="col-sm-10">
                <input type="text" name="trial_cycle" value="{{ trial_cycle }}" placeholder="{{ entry_trial_cycle }}" id="input-trial-cycle" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-trial-frequency">{{ entry_trial_frequency }}</label>
              <div class="col-sm-10">
                <select name="trial_frequency" id="input-trial-frequency" class="form-control">
                  {% for frequency_option in frequencies %}
                  {% if trial_frequency == frequency_option.value %}
                  <option value="{{ frequency_option.value }}" selected="selected">{{ frequency_option.text }}</option>
                  {% else %}
                  <option value="{{ frequency_option.value }}">{{ frequency_option.text }}</option>
                  {% endif %}
                  {% endfor %}
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-trial-status">{{ entry_trial_status }}</label>
              <div class="col-sm-10">
                <select name="trial_status" id="input-trial-status" class="form-control">
                  {% if trial_status %}
                  <option value="0">{{ text_disabled }}</option>
                  <option value="1" selected="selected">{{ text_enabled }}</option>
                  {% else %}
                  <option value="0" selected="selected">{{ text_disabled }}</option>
                  <option value="1">{{ text_enabled }}</option>
                  {% endif %}
                </select>
              </div>
            </div>
          </fieldset>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order">{{ entry_sort_order }}</label>
            <div class="col-sm-10">
              <input type="text" name="sort_order" value="{{ sort_order }}" placeholder="{{ entry_sort_order }}" id="input-sort-order" class="form-control"/>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>
{{ footer }}
