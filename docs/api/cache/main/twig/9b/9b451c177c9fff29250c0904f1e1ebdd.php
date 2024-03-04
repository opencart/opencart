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

/* layout/layout.twig */
class __TwigTemplate_f16c60290156f27e82b025fa67e5af4f extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content' => [$this, 'block_content'],
            'below_menu' => [$this, 'block_below_menu'],
            'page_content' => [$this, 'block_page_content'],
            'menu' => [$this, 'block_menu'],
            'leftnav' => [$this, 'block_leftnav'],
            'control_panel' => [$this, 'block_control_panel'],
            'footer' => [$this, 'block_footer'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("layout/base.twig", "layout/layout.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 4
        echo "    <div id=\"content\">
        <div id=\"left-column\">
            ";
        // line 6
        $this->displayBlock("control_panel", $context, $blocks);
        echo "
            ";
        // line 7
        $this->displayBlock("leftnav", $context, $blocks);
        echo "
        </div>
        <div id=\"right-column\">
            ";
        // line 10
        $this->displayBlock("menu", $context, $blocks);
        echo "
            ";
        // line 11
        $this->displayBlock('below_menu', $context, $blocks);
        // line 12
        echo "            <div id=\"page-content\">";
        // line 13
        $this->displayBlock('page_content', $context, $blocks);
        // line 14
        echo "</div>";
        // line 15
        $this->displayBlock("footer", $context, $blocks);
        // line 16
        echo "</div>
    </div>
";
    }

    // line 11
    public function block_below_menu($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "";
    }

    // line 13
    public function block_page_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "";
    }

    // line 20
    public function block_menu($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 21
        echo "    <nav id=\"site-nav\" class=\"navbar navbar-default\" role=\"navigation\">
        <div class=\"container-fluid\">
            <div class=\"navbar-header\">
                <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#navbar-elements\">
                    <span class=\"sr-only\">";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("Toggle navigation");
        // line 25
        echo "</span>
                    <span class=\"icon-bar\"></span>
                    <span class=\"icon-bar\"></span>
                    <span class=\"icon-bar\"></span>
                </button>
                <a class=\"navbar-brand\" href=\"";
        // line 30
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "index.html"), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 30, $this->source); })()), "config", ["title"], "method", false, false, false, 30), "html", null, true);
        echo "</a>
            </div>
            <div class=\"collapse navbar-collapse\" id=\"navbar-elements\">
                <ul class=\"nav navbar-nav\">
                    ";
        // line 34
        if ((twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 34, $this->source); })()), "versions", [], "any", false, false, false, 34)) > 1)) {
            // line 35
            echo "                    <li role=\"presentation\" class=\"dropdown visible-xs-block visible-sm-block\">
                        <a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\"
                            role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("Versions");
            // line 38
            echo "&nbsp;<span class=\"caret\"></span>
                        </a>
                        <ul class=\"dropdown-menu\">
                        ";
            // line 41
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 41, $this->source); })()), "versions", [], "any", false, false, false, 41));
            foreach ($context['_seq'] as $context["_key"] => $context["version"]) {
                // line 42
                echo "<li ";
                echo ((($context["version"] == twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 42, $this->source); })()), "version", [], "any", false, false, false, 42))) ? ("class=\"active\"") : (""));
                echo "><a href=\"";
                echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, (("../" . twig_urlencode_filter($context["version"])) . "/index.html")), "html", null, true);
                echo "\" data-version=\"";
                echo twig_escape_filter($this->env, $context["version"], "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["version"], "longname", [], "any", false, false, false, 42), "html", null, true);
                echo "</a></li>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['version'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 44
            echo "</ul>
                    </li>
                    ";
        }
        // line 47
        echo "<li><a href=\"";
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "classes.html"), "html", null, true);
        echo "\">";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("Classes");
        echo "</a></li>
                    ";
        // line 48
        if ((isset($context["has_namespaces"]) || array_key_exists("has_namespaces", $context) ? $context["has_namespaces"] : (function () { throw new RuntimeError('Variable "has_namespaces" does not exist.', 48, $this->source); })())) {
            // line 49
            echo "<li><a href=\"";
            echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "namespaces.html"), "html", null, true);
            echo "\">";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("Namespaces");
            echo "</a></li>
                    ";
        }
        // line 51
        echo "<li><a href=\"";
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "interfaces.html"), "html", null, true);
        echo "\">";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("Interfaces");
        echo "</a></li>
                    <li><a href=\"";
        // line 52
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "traits.html"), "html", null, true);
        echo "\">";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("Traits");
        echo "</a></li>
                    <li><a href=\"";
        // line 53
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "doc-index.html"), "html", null, true);
        echo "\">";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("Index");
        echo "</a></li>
                    <li><a href=\"";
        // line 54
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "search.html"), "html", null, true);
        echo "\">";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("Search");
        echo "</a></li>
                </ul>
            </div>
        </div>
    </nav>
";
    }

    // line 61
    public function block_leftnav($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 62
        echo "    <div id=\"api-tree\"></div>
";
    }

    // line 65
    public function block_control_panel($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 66
        echo "    <div id=\"control-panel\">
        ";
        // line 67
        if ((twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 67, $this->source); })()), "versions", [], "any", false, false, false, 67)) > 1)) {
            // line 68
            echo "            <form action=\"#\">
                <select class=\"form-control\" id=\"version-switcher\" name=\"version\">
                    ";
            // line 70
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 70, $this->source); })()), "versions", [], "any", false, false, false, 70));
            foreach ($context['_seq'] as $context["_key"] => $context["version"]) {
                // line 71
                echo "                        <option
                            value=\"";
                // line 72
                echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, (("../" . twig_urlencode_filter($context["version"])) . "/index.html")), "html", null, true);
                echo "\"
                            data-version=\"";
                // line 73
                echo twig_escape_filter($this->env, $context["version"], "html", null, true);
                echo "\" ";
                echo ((($context["version"] == twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 73, $this->source); })()), "version", [], "any", false, false, false, 73))) ? ("selected") : (""));
                echo ">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["version"], "longname", [], "any", false, false, false, 73), "html", null, true);
                echo "</option>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['version'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 75
            echo "                </select>
            </form>
        ";
        }
        // line 78
        echo "        <div class=\"search-bar hidden\" id=\"search-progress-bar-container\">
            <div class=\"progress\">
                <div class=\"progress-bar\" role=\"progressbar\" id=\"search-progress-bar\"
                    aria-valuenow=\"0\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 0%\"></div>
            </div>
        </div>
        <form id=\"search-form\" action=\"";
        // line 84
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "search.html"), "html", null, true);
        echo "\">
            <span class=\"icon icon-search\"></span>
            <input name=\"search\"
                   id=\"doctum-search-auto-complete\"
                   class=\"typeahead form-control\"
                   type=\"search\"
                   placeholder=\"";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("Search");
        // line 90
        echo "\"
                   spellcheck=\"false\"
                   autocorrect=\"off\"
                   autocomplete=\"off\"
                   autocapitalize=\"off\">
            <div class=\"auto-complete-results\" id=\"auto-complete-results\"></div>
        </form>
    </div>
";
    }

    // line 100
    public function block_footer($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 101
        echo "<div id=\"footer\">
        ";
        // line 102
        echo twig_sprintf(\Wdes\phpI18nL10n\Launcher::gettext("Generated by %sDoctum, a API Documentation generator and fork of Sami%s."), "<a href=\"https://github.com/code-lts/doctum\">", "</a>");
        // line 105
        if (twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 105, $this->source); })()), "hasFooterLink", [], "method", false, false, false, 105)) {
            // line 106
            $context["link"] = twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 106, $this->source); })()), "getFooterLink", [], "method", false, false, false, 106);
            // line 107
            echo "            <br/>";
            // line 108
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["link"]) || array_key_exists("link", $context) ? $context["link"] : (function () { throw new RuntimeError('Variable "link" does not exist.', 108, $this->source); })()), "before_text", [], "any", false, false, false, 108), "html", null, true);
            // line 109
            if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["link"]) || array_key_exists("link", $context) ? $context["link"] : (function () { throw new RuntimeError('Variable "link" does not exist.', 109, $this->source); })()), "href", [], "any", false, false, false, 109))) {
                // line 110
                echo " ";
                echo "<a href=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["link"]) || array_key_exists("link", $context) ? $context["link"] : (function () { throw new RuntimeError('Variable "link" does not exist.', 110, $this->source); })()), "href", [], "any", false, false, false, 110), "html", null, true);
                echo "\" rel=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["link"]) || array_key_exists("link", $context) ? $context["link"] : (function () { throw new RuntimeError('Variable "link" does not exist.', 110, $this->source); })()), "rel", [], "any", false, false, false, 110), "html", null, true);
                echo "\" target=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["link"]) || array_key_exists("link", $context) ? $context["link"] : (function () { throw new RuntimeError('Variable "link" does not exist.', 110, $this->source); })()), "target", [], "any", false, false, false, 110), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["link"]) || array_key_exists("link", $context) ? $context["link"] : (function () { throw new RuntimeError('Variable "link" does not exist.', 110, $this->source); })()), "link_text", [], "any", false, false, false, 110), "html", null, true);
                echo "</a>";
                echo " ";
            }
            // line 112
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["link"]) || array_key_exists("link", $context) ? $context["link"] : (function () { throw new RuntimeError('Variable "link" does not exist.', 112, $this->source); })()), "after_text", [], "any", false, false, false, 112), "html", null, true);
        }
        // line 114
        echo "</div>";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "layout/layout.twig";
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
        return array (  330 => 114,  327 => 112,  314 => 110,  312 => 109,  310 => 108,  308 => 107,  306 => 106,  304 => 105,  302 => 102,  299 => 101,  295 => 100,  283 => 90,  273 => 84,  265 => 78,  260 => 75,  248 => 73,  244 => 72,  241 => 71,  237 => 70,  233 => 68,  231 => 67,  228 => 66,  224 => 65,  219 => 62,  215 => 61,  203 => 54,  197 => 53,  191 => 52,  184 => 51,  176 => 49,  174 => 48,  167 => 47,  162 => 44,  147 => 42,  143 => 41,  138 => 38,  133 => 35,  131 => 34,  122 => 30,  115 => 25,  108 => 21,  104 => 20,  97 => 13,  90 => 11,  84 => 16,  82 => 15,  80 => 14,  78 => 13,  76 => 12,  74 => 11,  70 => 10,  64 => 7,  60 => 6,  56 => 4,  52 => 3,  41 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"layout/base.twig\" %}

{% block content %}
    <div id=\"content\">
        <div id=\"left-column\">
            {{ block('control_panel') }}
            {{ block('leftnav') }}
        </div>
        <div id=\"right-column\">
            {{ block('menu') }}
            {% block below_menu '' %}
            <div id=\"page-content\">
                {%- block page_content '' -%}
            </div>
            {{- block('footer') -}}
        </div>
    </div>
{% endblock %}

{% block menu %}
    <nav id=\"site-nav\" class=\"navbar navbar-default\" role=\"navigation\">
        <div class=\"container-fluid\">
            <div class=\"navbar-header\">
                <button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\"#navbar-elements\">
                    <span class=\"sr-only\">{% trans 'Toggle navigation' %}</span>
                    <span class=\"icon-bar\"></span>
                    <span class=\"icon-bar\"></span>
                    <span class=\"icon-bar\"></span>
                </button>
                <a class=\"navbar-brand\" href=\"{{ path('index.html') }}\">{{ project.config('title') }}</a>
            </div>
            <div class=\"collapse navbar-collapse\" id=\"navbar-elements\">
                <ul class=\"nav navbar-nav\">
                    {% if project.versions|length > 1 %}
                    <li role=\"presentation\" class=\"dropdown visible-xs-block visible-sm-block\">
                        <a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\"
                            role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">
                        {%- trans 'Versions' %}&nbsp;<span class=\"caret\"></span>
                        </a>
                        <ul class=\"dropdown-menu\">
                        {% for version in project.versions -%}
                            <li {{ version == project.version ? 'class=\"active\"' : ''}}><a href=\"{{ path('../' ~ version|url_encode ~ '/index.html') }}\" data-version=\"{{ version }}\">{{ version.longname }}</a></li>
                        {% endfor -%}
                        </ul>
                    </li>
                    {% endif -%}
                    <li><a href=\"{{ path('classes.html') }}\">{% trans 'Classes' %}</a></li>
                    {% if has_namespaces -%}
                    {#  #}<li><a href=\"{{ path('namespaces.html') }}\">{% trans 'Namespaces' %}</a></li>
                    {% endif -%}
                    <li><a href=\"{{ path('interfaces.html') }}\">{% trans 'Interfaces' %}</a></li>
                    <li><a href=\"{{ path('traits.html') }}\">{% trans 'Traits' %}</a></li>
                    <li><a href=\"{{ path('doc-index.html') }}\">{% trans 'Index' %}</a></li>
                    <li><a href=\"{{ path('search.html') }}\">{% trans 'Search' %}</a></li>
                </ul>
            </div>
        </div>
    </nav>
{% endblock %}

{% block leftnav %}
    <div id=\"api-tree\"></div>
{% endblock %}

{% block control_panel %}
    <div id=\"control-panel\">
        {% if project.versions|length > 1 %}
            <form action=\"#\">
                <select class=\"form-control\" id=\"version-switcher\" name=\"version\">
                    {% for version in project.versions %}
                        <option
                            value=\"{{ path('../' ~ version|url_encode ~ '/index.html') }}\"
                            data-version=\"{{ version }}\" {{ version == project.version ? 'selected' : ''}}>{{ version.longname }}</option>
                    {% endfor %}
                </select>
            </form>
        {% endif %}
        <div class=\"search-bar hidden\" id=\"search-progress-bar-container\">
            <div class=\"progress\">
                <div class=\"progress-bar\" role=\"progressbar\" id=\"search-progress-bar\"
                    aria-valuenow=\"0\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 0%\"></div>
            </div>
        </div>
        <form id=\"search-form\" action=\"{{ path('search.html') }}\">
            <span class=\"icon icon-search\"></span>
            <input name=\"search\"
                   id=\"doctum-search-auto-complete\"
                   class=\"typeahead form-control\"
                   type=\"search\"
                   placeholder=\"{% trans 'Search' %}\"
                   spellcheck=\"false\"
                   autocorrect=\"off\"
                   autocomplete=\"off\"
                   autocapitalize=\"off\">
            <div class=\"auto-complete-results\" id=\"auto-complete-results\"></div>
        </form>
    </div>
{% endblock %}

{%- block footer -%}
    <div id=\"footer\">
        {{ 'Generated by %sDoctum, a API Documentation generator and fork of Sami%s.'|trans|format(
            '<a href=\"https://github.com/code-lts/doctum\">', '</a>'
        )|raw }}
        {%- if project.hasFooterLink() -%}
            {% set link = project.getFooterLink() %}
            <br/>
            {{- link.before_text }}
            {%- if link.href is not empty -%}
                {{ \" \" }}<a href=\"{{ link.href }}\" rel=\"{{ link.rel }}\" target=\"{{ link.target }}\">{{ link.link_text }}</a>{{ \" \" }}
            {%- endif -%}
            {{ link.after_text -}}
        {%- endif -%}
    </div>
{%- endblock -%}
", "layout/layout.twig", "C:\\xampp\\htdocs\\opencart-master\\tools\\doctum\\src\\Resources\\themes\\default\\layout\\layout.twig");
    }
}
