{% if modules %}
<aside id="column-left" class="col-sm-3 hidden-xs">
  {% for module in modules %}
  {{ module }}
  {% endfor %}
</aside>
{% endif %}
