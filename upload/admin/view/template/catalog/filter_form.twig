{{ header }}{{ column_left }}
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-filter" data-toggle="tooltip" title="{{ button_save }}" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="{{ action }}" method="post" enctype="multipart/form-data" id="form-filter" class="form-horizontal">
          <fieldset id="option-value">
            <legend>{{ text_group }}</legend>
            <div class="form-group required">
              <label class="col-sm-2 control-label">{{ entry_group }}</label>
              <div class="col-sm-10"> {% for language in languages %}
                <div class="input-group"><span class="input-group-addon"><img src="language/{{ language.code }}/{{ language.code }}.png" title="{{ language.name }}" /></span>
                  <input type="text" name="filter_group_description[{{ language.language_id }}][name]" value="{{ filter_group_description[language.language_id] ? filter_group_description[language.language_id].name }}" placeholder="{{ entry_group }}" class="form-control" />
                </div>
                {% if error_group[language.language_id] %}
                <div class="text-danger">{{ error_group[language.language_id] }}</div>
                {% endif %}
                {% endfor %} </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-sort-order">{{ entry_sort_order }}</label>
              <div class="col-sm-10">
                <input type="text" name="sort_order" value="{{ sort_order }}" placeholder="{{ entry_sort_order }}" id="input-sort-order" class="form-control" />
              </div>
            </div>
          </fieldset>
          <fieldset id="option-value">
            <legend>{{ text_value }}</legend>
            <table id="filter" class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left required">{{ entry_name }}</td>
                  <td class="text-right">{{ entry_sort_order }}</td>
                  <td></td>
                </tr>
              </thead>
              <tbody>
              
              {% set filter_row = 0 %}
              {% for filter in filters %}
              <tr id="filter-row{{ filter_row }}">
                <td class="text-left" style="width: 70%;"><input type="hidden" name="filter[{{ filter_row }}][filter_id]" value="{{ filter.filter_id }}" />
                  {% for language in languages %}
                  <div class="input-group"><span class="input-group-addon"><img src="language/{{ language.code }}/{{ language.code }}.png" title="{{ language.name }}" /></span>
                    <input type="text" name="filter[{{ filter_row }}][filter_description][{{ language.language_id }}][name]" value="{{ filter.filter_description[language.language_id] ? filter.filter_description[language.language_id].name }}" placeholder="{{ entry_name }}" class="form-control" />
                  </div>
                  {% if error_filter[filter_row][language.language_id] %}
                  <div class="text-danger">{{ error_filter[filter_row][language.language_id] }}</div>
                  {% endif %}
                  {% endfor %}</td>
                <td class="text-right"><input type="text" name="filter[{{ filter_row }}][sort_order]" value="{{ filter.sort_order }}" placeholder="{{ entry_sort_order }}" id="input-sort-order" class="form-control" /></td>
                <td class="text-right"><button type="button" onclick="$('#filter-row{{ filter_row }}').remove();" data-toggle="tooltip" title="{{ button_remove }}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              {% set filter_row = filter_row + 1 %}
              {% endfor %}
                </tbody>
              
              <tfoot>
                <tr>
                  <td colspan="2"></td>
                  <td class="text-right"><button type="button" onclick="addFilterRow();" data-toggle="tooltip" title="{{ button_filter_add }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                </tr>
              </tfoot>
            </table>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
var filter_row = {{ filter_row }};

function addFilterRow() {
	html  = '<tr id="filter-row' + filter_row + '">';
    html += '  <td class="text-left" style="width: 70%;"><input type="hidden" name="filter[' + filter_row + '][filter_id]" value="" />';
	{% for language in languages %}
	html += '  <div class="input-group">';
	html += '    <span class="input-group-addon"><img src="language/{{ language.code }}/{{ language.code }}.png" title="{{ language.name }}" /></span><input type="text" name="filter[' + filter_row + '][filter_description][{{ language.language_id }}][name]" value="" placeholder="{{ entry_name }}" class="form-control" />';
    html += '  </div>';
	{% endfor %}
	html += '  </td>';
	html += '  <td class="text-right"><input type="text" name="filter[' + filter_row + '][sort_order]" value="" placeholder="{{ entry_sort_order }}" id="input-sort-order" class="form-control" /></td>';
	html += '  <td class="text-right"><button type="button" onclick="$(\'#filter-row' + filter_row + '\').remove();" data-toggle="tooltip" title="{{ button_remove }}" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#filter tbody').append(html);

	filter_row++;
}
//--></script></div>
{{ footer }} 