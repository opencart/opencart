<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* classes.twig */
class __TwigTemplate_7367b84aef56efeaf06985e6f2790f95 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'body_class' => [$this, 'block_body_class'],
            'page_content' => [$this, 'block_page_content'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout/layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 2
        $macros["__internal_parse_8"] = $this->macros["__internal_parse_8"] = $this->loadTemplate("macros.twig", "classes.twig", 2)->unwrap();
        // line 1
        $this->parent = $this->loadTemplate("layout/layout.twig", "classes.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("All Classes");
        echo " | ";
        $this->displayParentBlock("title", $context, $blocks);
    }

    // line 4
    public function block_body_class($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "classes";
    }

    // line 6
    public function block_page_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 7
        echo "    <div class=\"page-header\">
        <h1>";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("Classes");
        // line 8
        echo "</h1>
    </div>

    ";
        // line 11
        echo twig_call_macro($macros["__internal_parse_8"], "macro_render_classes", [(isset($context["classes"]) || array_key_exists("classes", $context) ? $context["classes"] : (function () { throw new RuntimeError('Variable "classes" does not exist.', 11, $this->source); })())], 11, $context, $this->getSourceContext());
        echo "
";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "classes.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  80 => 11,  75 => 8,  71 => 7,  67 => 6,  60 => 4,  51 => 3,  46 => 1,  44 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"layout/layout.twig\" %}
{% from \"macros.twig\" import render_classes %}
{% block title %}{% trans 'All Classes' %} | {{ parent() }}{% endblock %}
{% block body_class 'classes' %}

{% block page_content %}
    <div class=\"page-header\">
        <h1>{% trans 'Classes' %}</h1>
    </div>

    {{ render_classes(classes) }}
{% endblock %}
", "classes.twig", "C:\\xampp\\htdocs\\opencart-master\\tools\\doctum\\src\\Resources\\themes\\default\\classes.twig");
    }
}
