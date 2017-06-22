{{ header }}{{ column_left }}
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-option" data-toggle="tooltip" title="{{ button_save }}" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="{{ cancel }}" data-toggle="tooltip" title="{{ button_cancel }}" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>{{ heading_title }}</h1>
      <ul class="breadcrumb">
        {% for breadcrumb in breadcrumbs %}
        <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>
        {% endfor %}
      </ul>
    </div>
  </div>
  <div class="container-fluid"> {% if error_warning %}
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {% endif %}
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> {{ text_form }}</h3>
      </div>
      <div class="panel-body">
        <form action="{{ action }}" method="post" enctype="multipart/form-data" id="form-option" class="form-horizontal">
          <fieldset>
            <legend>{{ text_option }}</legend>
            <div class="form-group required">
              <label class="col-sm-2 control-label">{{ entry_name }}</label>
              <div class="col-sm-10"> {% for language in languages %}
                <div class="input-group"><span class="input-group-addon"><img src="language/{{ language.code }}/{{ language.code }}.png" title="{{ language.name }}" /></span>
                  <input type="text" name="option_description[{{ language.language_id }}][name]" value="{{ option_description[language.language_id] ? option_description[language.language_id].name }}" placeholder="{{ entry_name }}" class="form-control" />
                </div>
                {% if error_name[language.language_id] %}
                <div class="text-danger">{{ error_name[language.language_id] }}</div>
                {% endif %}
                {% endfor %}</div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-type">{{ entry_type }}</label>
              <div class="col-sm-10">
                <select name="type" id="input-type" class="form-control">
                  <optgroup label="{{ text_choose }}">
                {% if type == 'select' %}
                
                
                  <option value="select" selected="selected">{{ text_select }}</option>
                  
                
                {% else %}
                
                
                  <option value="select">{{ text_select }}</option>
                  
                
                {% endif %}
                {% if type == 'radio' %}
                
                
                  <option value="radio" selected="selected">{{ text_radio }}</option>
                  
                
                {% else %}
                
                
                  <option value="radio">{{ text_radio }}</option>
                  
                
                {% endif %}
                {% if type == 'checkbox' %}
                
                
                  <option value="checkbox" selected="selected">{{ text_checkbox }}</option>
                  
                
                {% else %}
                
                
                  <option value="checkbox">{{ text_checkbox }}</option>
                  
                
                {% endif %}
                </optgroup>
                  <optgroup label="{{ text_input }}">
                {% if type == 'text' %}
                
                
                  <option value="text" selected="selected">{{ text_text }}</option>
                  
                
                {% else %}
                
                
                  <option value="text">{{ text_text }}</option>
                  
                
                {% endif %}
                {% if type == 'textarea' %}
                
                
                  <option value="textarea" selected="selected">{{ text_textarea }}</option>
                  
                
                {% else %}
                
                
                  <option value="textarea">{{ text_textarea }}</option>
                  
                
                {% endif %}
                </optgroup>
                  <optgroup label="{{ text_file }}">
                {% if type == 'file' %}
                
                
                  <option value="file" selected="selected">{{ text_file }}</option>
                  
                
                {% else %}
                
                
                  <option value="file">{{ text_file }}</option>
                  
                
                {% endif %}
                </optgroup>
                  <optgroup label="{{ text_date }}">
                {% if type == 'date' %}
                
                
                  <option value="date" selected="selected">{{ text_date }}</option>
                  
                
                {% else %}
                
                
                  <option value="date">{{ text_date }}</option>
                  
                
                {% endif %}
                {% if type == 'time' %}
                
                
                  <option value="time" selected="selected">{{ text_time }}</option>
                  
                
                {% else %}
                
                
                  <option value="time">{{ text_time }}</option>
                  
                
                {% endif %}
                {% if type == 'datetime' %}
                
                
                  <option value="datetime" selected="selected">{{ text_datetime }}</option>
                  
                
                {% else %}
                
                
                  <option value="datetime">{{ text_datetime }}</option>
                  
                
                {% endif %}
                </optgroup>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-sort-order">{{ entry_sort_order }}</label>
              <div class="col-sm-10">
                <input type="text" name="sort_order" value="{{ sort_order }}" placeholder="{{ entry_sort_order }}" id="input-sort-order" class="form-control" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>{{ text_value }}</legend>
            <table id="option-value" class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left required">{{ entry_option_value }}</td>
                  <td class="text-center">{{ entry_image }}</td>
                  <td class="text-right">{{ entry_sort_order }}</td>
                  <td></td>
                </tr>
              </thead>
              <tbody>
              
              {% set option_value_row = 0 %}
              {% for option_value in option_values %}
              <tr id="option-value-row{{ option_value_row }}">
                <td class="text-center"><input type="hidden" name="option_value[{{ option_value_row }}][option_value_id]" value="{{ option_value.option_value_id }}" />
                  {% for language in languages %}
                  <div class="input-group"><span class="input-group-addon"><img src="language/{{ language.code }}/{{ language.code }}.png" title="{{ language.name }}" /></span>
                    <input type="text" name="option_value[{{ option_value_row }}][option_value_description][{{ language.language_id }}][name]" value="{{ option_value.option_value_description[language.language_id] ? option_value.option_value_description[language.language_id].name }}" placeholder="{{ entry_option_value }}" class="form-control" />
                  </div>
                  {% if error_option_value[option_value_row][language.language_id] %}
                  <div class="text-danger">{{ error_option_value[option_value_row][language.language_id] }}</div>
                  {% endif %}
                  {% endfor %}</td>
                <td class="text-left"><a href="" id="thumb-image{{ option_value_row }}" data-toggle="image" class="img-thumbnail"><img src="{{ option_value.thumb }}" alt="" title="" data-placeholder="{{ placeholder }}" /></a>
                  <input type="hidden" name="option_value[{{ option_value_row }}][image]" value="{{ option_value.image }}" id="input-image{{ option_value_row }}" /></td>
                <td class="text-right"><input type="text" name="option_value[{{ option_value_row }}][sort_order]" value="{{ option_value.sort_order }}" class="form-control" /></td>
                <td class="text-right"><button type="button" onclick="$('#option-value-row{{ option_value_row }}').remove();" data-toggle="tooltip" title="{{ button_remove }}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              {% set option_value_row = option_value_row + 1 %}
              {% endfor %}
                </tbody>
              
              <tfoot>
                <tr>
                  <td colspan="3"></td>
                  <td class="text-right"><button type="button" onclick="addOptionValue();" data-toggle="tooltip" title="{{ button_option_value_add }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                </tr>
              </tfoot>
            </table>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('select[name=\'type\']').on('change', function() {
	if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox' || this.value == 'image') {
		$('#option-value').parent().show();
	} else {
		$('#option-value').parent().hide();
	}
});

$('select[name=\'type\']').trigger('change');

var option_value_row = {{ option_value_row }};

function addOptionValue() {
	html  = '<tr id="option-value-row' + option_value_row + '">';
    html += '  <td class="text-left"><input type="hidden" name="option_value[' + option_value_row + '][option_value_id]" value="" />';
	{% for language in languages %}
	html += '    <div class="input-group">';
	html += '      <span class="input-group-addon"><img src="language/{{ language.code }}/{{ language.code }}.png" title="{{ language.name }}" /></span><input type="text" name="option_value[' + option_value_row + '][option_value_description][{{ language.language_id }}][name]" value="" placeholder="{{ entry_option_value }}" class="form-control" />';
    html += '    </div>';
	{% endfor %}
	html += '  </td>';
    html += '  <td class="text-center"><a href="" id="thumb-image' + option_value_row + '" data-toggle="image" class="img-thumbnail"><img src="{{ placeholder }}" alt="" title="" data-placeholder="{{ placeholder }}" /></a><input type="hidden" name="option_value[' + option_value_row + '][image]" value="" id="input-image' + option_value_row + '" /></td>';
	html += '  <td class="text-right"><input type="text" name="option_value[' + option_value_row + '][sort_order]" value="" placeholder="{{ entry_sort_order }}" class="form-control" /></td>';
	html += '  <td class="text-right"><button type="button" onclick="$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" title="{{ button_remove }}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#option-value tbody').append(html);

	option_value_row++;
}
//--></script></div>
{{ footer }} 