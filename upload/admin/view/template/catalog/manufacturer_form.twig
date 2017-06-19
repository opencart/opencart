{{ header }}{{ column_left }}
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-manufacturer" data-toggle="tooltip" title="{{ button_save }}" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="{{ action }}" method="post" enctype="multipart/form-data" id="form-manufacturer" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab">{{ tab_general }}</a></li>
            <li><a href="#tab-seo" data-toggle="tab">{{ tab_seo }}</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name">{{ entry_name }}</label>
                <div class="col-sm-10">
                  <input type="text" name="name" value="{{ name }}" placeholder="{{ entry_name }}" id="input-name" class="form-control" />
                  {% if error_name %}
                  <div class="text-danger">{{ error_name }}</div>
                  {% endif %}</div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">{{ entry_store }}</label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;"> {% for store in stores %}
                    <div class="checkbox">
                      <label>{% if store.store_id in manufacturer_store %}
                        <input type="checkbox" name="manufacturer_store[]" value="{{ store.store_id }}" checked="checked" />
                        {{ store.name }}
                        {% else %}
                        <input type="checkbox" name="manufacturer_store[]" value="{{ store.store_id }}" />
                        {{ store.name }}
                        {% endif %}</label>
                    </div>
                    {% endfor %}</div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-image">{{ entry_image }}</label>
                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="{{ thumb }}" alt="" title="" data-placeholder="{{ placeholder }}" /></a>
                  <input type="hidden" name="image" value="{{ image }}" id="input-image" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order">{{ entry_sort_order }}</label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="{{ sort_order }}" placeholder="{{ entry_sort_order }}" id="input-sort-order" class="form-control" />
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-seo">
              <div class="alert alert-info"><i class="fa fa-info-circle"></i> {{ text_keyword }}</div>
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left">{{ entry_store }}</td>
                      <td class="text-left">{{ entry_keyword }}</td>
                    </tr>
                  </thead>
                  <tbody>
                  {% for store in stores %}
                  <tr>
                    <td class="text-left">{{ store.name }}</td>
                    <td class="text-left">{% for language in languages %}
                      <div class="input-group"><span class="input-group-addon"><img src="language/{{ language.code }}/{{ language.code }}.png" title="{{ language.name }}" /></span>
                        <input type="text" name="manufacturer_seo_url[{{ store.store_id }}][{{ language.language_id }}]" value="{% if manufacturer_seo_url[store.store_id][language.language_id] %}{{ manufacturer_seo_url[store.store_id][language.language_id] }}{% endif %}" placeholder="{{ entry_keyword }}" class="form-control" />
                      </div>
                      {% if error_keyword[store.store_id][language.language_id] %}
                      <div class="text-danger">{{ error_keyword[store.store_id][language.language_id] }}</div>
                      {% endif %} 
                      {% endfor %}</td>
                  </tr>
                  {% endfor %}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
{{ footer }}