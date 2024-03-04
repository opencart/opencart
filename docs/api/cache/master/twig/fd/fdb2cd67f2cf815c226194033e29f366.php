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

/* layout/base.twig */
class __TwigTemplate_75d24fc6b286ce8c759472bf288c7d58 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'head' => [$this, 'block_head'],
            'html' => [$this, 'block_html'],
            'body_class' => [$this, 'block_body_class'],
            'page_id' => [$this, 'block_page_id'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"";
        // line 2
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 2, $this->source); })()), "config", ["language"], "method", false, false, false, 2), "html", null, true);
        echo "\">
<head>
    <meta charset=\"UTF-8\" />
    <meta name=\"robots\" content=\"index, follow, all\" />
    <title>";
        // line 6
        $this->displayBlock('title', $context, $blocks);
        echo "</title>

    ";
        // line 8
        $this->displayBlock('head', $context, $blocks);
        // line 21
        echo "
";
        // line 22
        if (twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 22, $this->source); })()), "config", ["favicon"], "method", false, false, false, 22)) {
            // line 23
            echo "        <link rel=\"shortcut icon\" href=\"";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 23, $this->source); })()), "config", ["favicon"], "method", false, false, false, 23), "html", null, true);
            echo "\" />";
        }
        // line 25
        echo "
    ";
        // line 26
        if (twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 26, $this->source); })()), "getBaseUrl", [], "method", false, false, false, 26)) {
            // line 27
            echo "    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 27, $this->source); })()), "versions", [], "any", false, false, false, 27));
            foreach ($context['_seq'] as $context["_key"] => $context["version"]) {
                // line 28
                echo "<link rel=\"search\"
    ";
                // line 29
                echo "      type=\"application/opensearchdescription+xml\"
    ";
                // line 30
                echo "      href=\"";
                echo twig_escape_filter($this->env, twig_replace_filter(twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 30, $this->source); })()), "getBaseUrl", [], "method", false, false, false, 30), ["%version%" => $context["version"]]), "html", null, true);
                echo "/opensearch.xml\"
    ";
                // line 31
                echo "      title=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 31, $this->source); })()), "config", ["title"], "method", false, false, false, 31), "html", null, true);
                echo " (";
                echo twig_escape_filter($this->env, $context["version"], "html", null, true);
                echo ")\" />
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['version'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        // line 34
        echo "</head>

";
        // line 36
        $this->displayBlock('html', $context, $blocks);
        // line 41
        echo "
</html>
";
    }

    // line 6
    public function block_title($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 6, $this->source); })()), "config", ["title"], "method", false, false, false, 6), "html", null, true);
    }

    // line 8
    public function block_head($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 9
        echo "        <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "css/bootstrap.min.css"), "html", null, true);
        echo "\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "css/bootstrap-theme.min.css"), "html", null, true);
        echo "\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "css/doctum.css"), "html", null, true);
        echo "\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "fonts/doctum-font.css"), "html", null, true);
        echo "\">
        <script src=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "js/jquery-3.5.1.slim.min.js"), "html", null, true);
        echo "\"></script>
        <script async defer src=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "doctum.js"), "html", null, true);
        echo "\"></script>
        <script async defer src=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "js/bootstrap.min.js"), "html", null, true);
        echo "\"></script>
        <script async defer src=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "js/autocomplete.min.js"), "html", null, true);
        echo "\"></script>
        <meta name=\"MobileOptimized\" content=\"width\">
        <meta name=\"HandheldFriendly\" content=\"true\">
        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1,maximum-scale=1\">";
    }

    // line 36
    public function block_html($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 37
        echo "    <body id=\"";
        $this->displayBlock('body_class', $context, $blocks);
        echo "\" data-name=\"";
        $this->displayBlock('page_id', $context, $blocks);
        echo "\" data-root-path=\"";
        echo twig_escape_filter($this->env, (isset($context["root_path"]) || array_key_exists("root_path", $context) ? $context["root_path"] : (function () { throw new RuntimeError('Variable "root_path" does not exist.', 37, $this->source); })()), "html", null, true);
        echo "\" data-search-index-url=\"";
        echo twig_escape_filter($this->env, $this->extensions['Doctum\Renderer\TwigExtension']->pathForStaticFile($context, "doctum-search.json"), "html", null, true);
        echo "\">
        ";
        // line 38
        $this->displayBlock('content', $context, $blocks);
        // line 39
        echo "    </body>
";
    }

    // line 37
    public function block_body_class($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "";
    }

    public function block_page_id($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "";
    }

    // line 38
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        echo "";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "layout/base.twig";
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
        return array (  198 => 38,  185 => 37,  180 => 39,  178 => 38,  167 => 37,  163 => 36,  155 => 16,  151 => 15,  147 => 14,  143 => 13,  139 => 12,  135 => 11,  131 => 10,  126 => 9,  122 => 8,  115 => 6,  109 => 41,  107 => 36,  103 => 34,  91 => 31,  86 => 30,  83 => 29,  80 => 28,  75 => 27,  73 => 26,  70 => 25,  65 => 23,  63 => 22,  60 => 21,  58 => 8,  53 => 6,  46 => 2,  43 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE html>
<html lang=\"{{ project.config('language') }}\">
<head>
    <meta charset=\"UTF-8\" />
    <meta name=\"robots\" content=\"index, follow, all\" />
    <title>{% block title project.config('title') %}</title>

    {% block head %}
        <link rel=\"stylesheet\" type=\"text/css\" href=\"{{ path('css/bootstrap.min.css') }}\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"{{ path('css/bootstrap-theme.min.css') }}\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"{{ path('css/doctum.css') }}\">
        <link rel=\"stylesheet\" type=\"text/css\" href=\"{{ path('fonts/doctum-font.css') }}\">
        <script src=\"{{ path('js/jquery-3.5.1.slim.min.js') }}\"></script>
        <script async defer src=\"{{ path('doctum.js') }}\"></script>
        <script async defer src=\"{{ path('js/bootstrap.min.js') }}\"></script>
        <script async defer src=\"{{ path('js/autocomplete.min.js') }}\"></script>
        <meta name=\"MobileOptimized\" content=\"width\">
        <meta name=\"HandheldFriendly\" content=\"true\">
        <meta name=\"viewport\" content=\"width=device-width,initial-scale=1,maximum-scale=1\">
    {%- endblock %}

{##}{% if project.config('favicon') %}
        <link rel=\"shortcut icon\" href=\"{{ project.config('favicon') }}\" />
    {%- endif %}

    {% if project.getBaseUrl() %}
    {#  #}{%- for version in project.versions -%}
    {#  #}<link rel=\"search\"
    {#  #}      type=\"application/opensearchdescription+xml\"
    {#  #}      href=\"{{ project.getBaseUrl()|replace({'%version%': version}) }}/opensearch.xml\"
    {#  #}      title=\"{{ project.config('title') }} ({{ version }})\" />
    {#  #}{% endfor -%}
    {% endif %}
</head>

{% block html %}
    <body id=\"{% block body_class '' %}\" data-name=\"{% block page_id '' %}\" data-root-path=\"{{ root_path }}\" data-search-index-url=\"{{ path('doctum-search.json') }}\">
        {% block content '' %}
    </body>
{% endblock %}

</html>
", "layout/base.twig", "C:\\xampp\\htdocs\\opencart-master\\tools\\doctum\\src\\Resources\\themes\\default\\layout\\base.twig");
    }
}
