{{ header }}{{ column_left }}
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-attribute" data-toggle="tooltip" title="{{ button_save }}" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="{{ cancel }}" data-toggle="tooltip" title="{{ button_cancel }}" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1>{{ heading_title }}</h1>
      <ul class="breadcrumb">
        {% for breadcrumb in breadcrumbs %}
        <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>
        {% endfor %}
      </ul>
    </div>
  </div>
  <div class="container-fluid">{% if error_warning %}
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> {{ error_warning }}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {% endif %}
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> {{ text_form }}</h3>
      </div>
      <div class="panel-body">
        <form action="{{ action }}" method="post" enctype="multipart/form-data" id="form-attribute" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label">{{ entry_name }}</label>
            <div class="col-sm-10">
              {% for language in languages %}
              <div class="input-group"><span class="input-group-addon"><img src="language/{{ language.code }}/{{ language.code }}.png" title="{{ language.name }}" /></span>
                <input type="text" name="attribute_description[{{ language.language_id }}][name]" value="{{ attribute_description[language.language_id] ? attribute_description[language.language_id].name }}" placeholder="{{ entry_name }}" class="form-control" />
              </div>
              {% if error_name[language.language_id] %}
              <div class="text-danger">{{ error_name[language.language_id] }}</div>
              {% endif %}
              {% endfor %}
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-attribute-group">{{ entry_attribute_group }}</label>
            <div class="col-sm-10">
              <select name="attribute_group_id" id="input-attribute-group" class="form-control">
                <option value="0"></option>
                {% for attribute_group in attribute_groups %}
                {% if attribute_group.attribute_group_id == attribute_group_id %}
                <option value="{{ attribute_group.attribute_group_id }}" selected="selected">{{ attribute_group.name }}</option>
                {% else %}
                <option value="{{ attribute_group.attribute_group_id }}">{{ attribute_group.name }}</option>
                {% endif %}
                {% endfor %}
              </select>
              {% if error_attribute_group %}
              <div class="text-danger">{{ error_attribute_group }}</div>
              {% endif %}
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order">{{ entry_sort_order }}</label>
            <div class="col-sm-10">
              <input type="text" name="sort_order" value="{{ sort_order }}" placeholder="{{ entry_sort_order }}" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
{{ footer }}
