<?php

/* upgrade/upgrade.twig */
class __TwigTemplate_3d636fa85d31f8377bf38fd6d3cb70ae1b8b8f73235b13c9403a48a55157accb extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo ($context["header"] ?? null);
        echo "
<div class=\"container\">
  <header>
    <div class=\"row\">
      <div class=\"col-sm-6\">
        <h1 class=\"pull-left\">1<small>/2</small></h1>
        <h3>";
        // line 7
        echo ($context["heading_title"] ?? null);
        echo "<br>
          <small>";
        // line 8
        echo ($context["text_upgrade"] ?? null);
        echo "</small></h3>
      </div>
      <div class=\"col-sm-6\">
        <div id=\"logo\" class=\"pull-right hidden-xs\"><img src=\"view/image/logo.png\" alt=\"OpenCart\" title=\"OpenCart\" /></div>
      </div>
    </div>
  </header>
  <div class=\"row\">
    <div class=\"col-sm-9\">
      <h3>";
        // line 17
        echo ($context["text_server"] ?? null);
        echo "</h3>
      <fieldset>
        <ol>
          <li>";
        // line 20
        echo ($context["text_error"] ?? null);
        echo "</li>
          <li>";
        // line 21
        echo ($context["text_clear"] ?? null);
        echo "</li>
          <li>";
        // line 22
        echo ($context["text_admin"] ?? null);
        echo "</li>
          <li>";
        // line 23
        echo ($context["text_user"] ?? null);
        echo "</li>
          <li>";
        // line 24
        echo ($context["text_setting"] ?? null);
        echo "</li>
          <li>";
        // line 25
        echo ($context["text_store"] ?? null);
        echo "</li>
        </ol>
      </fieldset>
      <h3>";
        // line 28
        echo ($context["text_steps"] ?? null);
        echo "</h3>
      <fieldset>
        <div class=\"form-group\">
          <label class=\"col-sm-2 control-label\">";
        // line 31
        echo ($context["entry_progress"] ?? null);
        echo "</label>
          <div class=\"col-sm-10\">
            <div class=\"progress\">
              <div id=\"progress-bar\" class=\"progress-bar\" style=\"width: 0%;\"></div>
            </div>
            <div id=\"progress-text\"></div>
          </div>
        </div>
      </fieldset>
      <div class=\"buttons\">
        <div class=\"text-right\">
          <input type=\"submit\" value=\"";
        // line 42
        echo ($context["button_continue"] ?? null);
        echo "\" data-loading=\"{ text_loading }}\" id=\"button-continue\" class=\"btn btn-primary\" />
        </div>
      </div>
    </div>
    <div class=\"col-sm-3\">";
        // line 46
        echo ($context["column_left"] ?? null);
        echo "</div>
  </div>
  <script type=\"text/javascript\"><!--
var step = 0;

\$('#button-continue').on('click', function() {
\t\$('#progress-bar').addClass('progress-bar-success').css('width', '0%').removeClass('progress-bar-danger');
\t\$('#progress-text').html('');
\t\$('#button-continue').button('loading');

\tstart('index.php?route=upgrade/upgrade/next');
});

function start(url) {
\tsetTimeout(function(){
\t\t\$.ajax({
\t\t\turl: url,
\t\t\ttype: 'post',
\t\t\tdataType: 'json',
\t\t\tsuccess: function(json) {
\t\t\t\tif (json['error']) {
\t\t\t\t\t\$('#progress-bar').addClass('progress-bar-danger');
\t\t\t\t\t\$('#progress-text').html('<div class=\"text-danger\">' + json['error'] + '</div>');

\t\t\t\t\t\$('#button-continue').prop('disabled', false);
\t\t\t\t\t\$('#button-continue').button('reset');
\t\t\t\t}

\t\t\t\tif (json['success']) {
\t\t\t\t\t\$('#progress-text').html('<span class=\"text-success\">' + json['success'] + '</span>');
\t\t\t\t\t\$('#progress-bar').css('width', ((step /";
        // line 76
        echo ($context["total"] ?? null);
        echo ") * 100) + '%');
\t\t\t\t}

\t\t\t\tif (json['next']) {
\t\t\t\t\tstart(json['next']);
\t\t\t\t} else if (!json['error']) {
\t\t\t\t\t\$('#button-continue').replaceWith('<a href=\"";
        // line 82
        echo ($context["store"] ?? null);
        echo "\" class=\"btn btn-primary\">";
        echo ($context["button_continue"] ?? null);
        echo "</a>');
\t\t\t\t\t\$('#button-continue').button('reset');
\t\t\t\t}

\t\t\t\tstep++;
\t\t\t},
\t\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\t\t\$('#progress-bar').addClass('progress-bar-danger');
\t\t\t\t\$('#progress-text').html('<div class=\"text-danger\">' + (thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText) + '</div>');
\t\t\t\t\$('#button-continue').button('reset');

\t\t\t}
\t\t});
\t}, 1000);
}
//--></script></div>";
        // line 98
        echo ($context["footer"] ?? null);
    }

    public function getTemplateName()
    {
        return "upgrade/upgrade.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  169 => 98,  149 => 82,  140 => 76,  107 => 46,  100 => 42,  86 => 31,  80 => 28,  74 => 25,  70 => 24,  66 => 23,  62 => 22,  58 => 21,  54 => 20,  48 => 17,  36 => 8,  32 => 7,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "upgrade/upgrade.twig", "");
    }
}
