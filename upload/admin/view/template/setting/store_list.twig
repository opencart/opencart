{{ header }}{{ column_left }}
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="float-right"><a href="{{ add }}" data-toggle="tooltip" title="{{ button_add }}" class="btn btn-primary"><i class="fas fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="{{ button_delete }}" class="btn btn-danger" onclick="confirm('{{ text_confirm }}') ? $('#form-store').submit() : false;"><i class="fas fa-trash-alt"></i></button>
      </div>
      <h1>{{ heading_title }}</h1>
      <ol class="breadcrumb">
        {% for breadcrumb in breadcrumbs %}
          <li class="breadcrumb-item"><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>
        {% endfor %}
      </ol>
    </div>
  </div>
  <div class="container-fluid">
    {% if error_warning %}
      <div class="alert alert-danger alert-dismissible"><i class="fas fa-exclamation-circle"></i> {{ error_warning }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    {% endif %}
    {% if success %}
      <div class="alert alert-success alert-dismissible"><i class="fas fa-check-circle"></i> {{ success }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    {% endif %}
    <div class="card">
      <div class="card-header"><i class="fas fa-list"></i> {{ text_list }}</div>
      <div class="card-body">
        <form action="{{ delete }}" method="post" enctype="multipart/form-data" id="form-store">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').trigger('click');"/></td>
                  <td class="text-left">{{ column_name }}</td>
                  <td class="text-left">{{ column_url }}</td>
                  <td class="text-right">{{ column_action }}</td>
                </tr>
              </thead>
              <tbody>
                {% if stores %}
                  {% for store in stores %}
                    <tr>
                      <td class="text-center">{% if store.store_id in selected %}
                          <input type="checkbox" name="selected[]" value="{{ store.store_id }}" checked="checked"/>
                        {% else %}
                          <input type="checkbox" name="selected[]" value="{{ store.store_id }}"/>
                        {% endif %}</td>
                      <td class="text-left">{{ store.name }}</td>
                      <td class="text-left">{{ store.url }}</td>
                      <td class="text-right"><a href="{{ store.edit }}" data-toggle="tooltip" title="{{ button_edit }}" class="btn btn-primary"><i class="fas fa-pencil-alt"></i></a></td>
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