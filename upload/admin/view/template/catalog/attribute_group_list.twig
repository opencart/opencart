{{ header }}{{ column_left }}
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="{{ add }}" data-toggle="tooltip" title="{{ button_add }}" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="{{ button_delete }}" class="btn btn-danger" onclick="confirm('{{ text_confirm }}') ? $('#form-attribute-group').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
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
    {% if success %}
    <div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> {{ success }}
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    {% endif %}
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> {{ text_list }}</h3>
      </div>
      <div class="panel-body">
        <form action="{{ delete }}" method="post" enctype="multipart/form-data" id="form-attribute-group">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left">{% if sort == 'agd.name' %}
                    <a href="{{ sort_name }}" class="{{ order|lower }}">{{ column_name }}</a>
                    {% else %}
                    <a href="{{ sort_name }}">{{ column_name }}</a>
                    {% endif %}</td>
                  <td class="text-right">{% if sort == 'ag.sort_order' %}
                    <a href="{{ sort_sort_order }}" class="{{ order|lower }}">{{ column_sort_order }}</a>
                    {% else %}
                    <a href="{{ sort_sort_order }}">{{ column_sort_order }}</a>
                    {% endif %}</td>
                  <td class="text-right">{{ column_action }}</td>
                </tr>
              </thead>
              <tbody>
                {% if attribute_groups %}
                {% for attribute_group in attribute_groups %}
                <tr>
                  <td class="text-center">{% if attribute_group.attribute_group_id in selected %}
                    <input type="checkbox" name="selected[]" value="{{ attribute_group.attribute_group_id }}" checked="checked" />
                    {% else %}
                    <input type="checkbox" name="selected[]" value="{{ attribute_group.attribute_group_id }}" />
                    {% endif %}</td>
                  <td class="text-left">{{ attribute_group.name }}</td>
                  <td class="text-right">{{ attribute_group.sort_order }}</td>
                  <td class="text-right"><a href="{{ attribute_group.edit }}" data-toggle="tooltip" title="{{ button_edit }}" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                {% endfor %}
                {% else %}
                <tr>
                  <td class="text-center" colspan="4">{{ text_no_results }}</td>
                </tr>
                {% endif %}
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left">{{ pagination }}</div>
          <div class="col-sm-6 text-right">{{ results }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
{{ footer }}
