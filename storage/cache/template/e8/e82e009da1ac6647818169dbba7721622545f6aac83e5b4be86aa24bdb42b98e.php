<?php

/* extension/dashboard/chart_info.twig */
class __TwigTemplate_da3986f593cfa402e10ee8c76e5e68073113e1fff5bc936161122fda5e90e020 extends Twig_Template
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
        echo "<div class=\"card mb-3\">
  <div class=\"card-header\">
    <div class=\"float-right\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"fas fa-calendar-alt\"></i> <i class=\"fas fa-caret-down\"></i></a>
      <div id=\"range\" class=\"dropdown-menu dropdown-menu-right\">
        <a href=\"day\" class=\"dropdown-item\">";
        // line 5
        echo ($context["text_day"] ?? null);
        echo "</a>
        <a href=\"week\" class=\"dropdown-item\">";
        // line 6
        echo ($context["text_week"] ?? null);
        echo "</a>
        <a href=\"month\" class=\"dropdown-item active\">";
        // line 7
        echo ($context["text_month"] ?? null);
        echo "</a>
        <a href=\"year\" class=\"dropdown-item\">";
        // line 8
        echo ($context["text_year"] ?? null);
        echo "</a>
      </div>
    </div>
    <i class=\"fas fa-chart-bar\"></i>";
        // line 11
        echo ($context["heading_title"] ?? null);
        echo "
  </div>
  <div class=\"card-body\">
    <div id=\"chart-sale\" style=\"width: 100%; height: 260px;\"></div>
  </div>
</div>
<script type=\"text/javascript\" src=\"view/javascript/jquery/flot/jquery.flot.js\"></script>
<script type=\"text/javascript\" src=\"view/javascript/jquery/flot/jquery.flot.resize.min.js\"></script>
<script type=\"text/javascript\"><!--
\$('#range a').on('click', function(e) {
\te.preventDefault();

\t\$(this).parent().find('a').removeClass('active');

\t\$(this).addClass('active');

\t\$.ajax({
\t\ttype: 'get',
\t\turl: 'index.php?route=extension/dashboard/chart/chart&user_token=";
        // line 29
        echo ($context["user_token"] ?? null);
        echo "&range=' + \$(this).attr('href'),
\t\tdataType: 'json',
\t\tsuccess: function(json) {
\t\t\tif (typeof json['order'] == 'undefined') {
\t\t\t\treturn false;
\t\t\t}

\t\t\tvar option = {
\t\t\t\tshadowSize: 0,
\t\t\t\tcolors: ['#9FD5F1', '#1065D2'],
\t\t\t\tbars: {
\t\t\t\t\tshow: true,
\t\t\t\t\tfill: true,
\t\t\t\t\tlineWidth: 1
\t\t\t\t},
\t\t\t\tgrid: {
\t\t\t\t\tbackgroundColor: '#FFFFFF',
\t\t\t\t\thoverable: true
\t\t\t\t},
\t\t\t\tpoints: {
\t\t\t\t\tshow: false
\t\t\t\t},
\t\t\t\txaxis: {
\t\t\t\t\tshow: true,
\t\t\t\t\tticks: json['xaxis']
\t\t\t\t}
\t\t\t}

\t\t\t\$.plot('#chart-sale', [json['order'], json['customer']], option);

\t\t\t\$('#chart-sale').bind('plothover', function(event, pos, item) {
\t\t\t\t\$('.tooltip').remove();

\t\t\t\tif (item) {
\t\t\t\t\t\$('<div id=\"tooltip\" class=\"tooltip top show\"><div class=\"tooltip-arrow\"></div><div class=\"tooltip-inner\">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

\t\t\t\t\t\$('#tooltip').css({
\t\t\t\t\t\tposition: 'absolute',
\t\t\t\t\t\tleft: item.pageX - (\$('#tooltip').outerWidth() / 2),
\t\t\t\t\t\ttop: item.pageY - \$('#tooltip').outerHeight(),
\t\t\t\t\t\tpointer: 'cusror'
\t\t\t\t\t}).fadeIn('slow');

\t\t\t\t\t\$('#chart-sale').css('cursor', 'pointer');
\t\t\t\t} else {
\t\t\t\t\t\$('#chart-sale').css('cursor', 'auto');
\t\t\t\t}
\t\t\t});
\t\t},
\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t}
\t});
});

\$('#range a.active').trigger('click');
//--></script>";
    }

    public function getTemplateName()
    {
        return "extension/dashboard/chart_info.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  68 => 29,  47 => 11,  41 => 8,  37 => 7,  33 => 6,  29 => 5,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "extension/dashboard/chart_info.twig", "");
    }
}
