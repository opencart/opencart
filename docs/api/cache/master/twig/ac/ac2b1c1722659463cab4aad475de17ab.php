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

/* opensearch.twig */
class __TwigTemplate_72090ba0cdc0c3b1f1a8a5afd7dcd468 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        if (twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 1, $this->source); })()), "getBaseUrl", [], "method", false, false, false, 1)) {
            // line 2
            echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\" xmlns:referrer=\"http://a9.com/-/opensearch/extensions/referrer/\">
    <ShortName>";
            // line 4
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 4, $this->source); })()), "config", ["title"], "method", false, false, false, 4), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 4, $this->source); })()), "version", [], "any", false, false, false, 4), "html", null, true);
            echo ")</ShortName>
    <Description>";
            // line 5
            echo twig_escape_filter($this->env, \Wdes\phpI18nL10n\Launcher::gettext("Searches"), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 5, $this->source); })()), "config", ["title"], "method", false, false, false, 5), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 5, $this->source); })()), "version", [], "any", false, false, false, 5), "html", null, true);
            echo ")</Description>
    <Tags>";
            // line 6
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 6, $this->source); })()), "config", ["title"], "method", false, false, false, 6), "html", null, true);
            echo "</Tags>
    ";
            // line 7
            if (twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 7, $this->source); })()), "config", ["favicon"], "method", false, false, false, 7)) {
                // line 8
                echo "<Image height=\"16\" width=\"16\" type=\"image/x-icon\">";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 8, $this->source); })()), "config", ["favicon"], "method", false, false, false, 8), "html", null, true);
                echo "</Image>
    ";
            }
            // line 10
            echo "<Url type=\"text/html\" method=\"GET\" template=\"";
            echo twig_escape_filter($this->env, (twig_replace_filter(twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 10, $this->source); })()), "getBaseUrl", [], "method", false, false, false, 10), ["%version%" => twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 10, $this->source); })()), "version", [], "any", false, false, false, 10)]) . "/search.html?search={searchTerms}&utm_source={referrer:source?}"), "html", null, true);
            echo "\"/>
    <InputEncoding>UTF-8</InputEncoding>
    <AdultContent>false</AdultContent>
</OpenSearchDescription>
";
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "opensearch.twig";
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
        return array (  69 => 10,  63 => 8,  61 => 7,  57 => 6,  49 => 5,  43 => 4,  39 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% if project.getBaseUrl() -%}
<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\" xmlns:referrer=\"http://a9.com/-/opensearch/extensions/referrer/\">
    <ShortName>{{ project.config('title') }} ({{ project.version }})</ShortName>
    <Description>{{ 'Searches'|trans }} {{ project.config('title') }} ({{ project.version }})</Description>
    <Tags>{{ project.config('title') }}</Tags>
    {% if project.config('favicon') -%}
        <Image height=\"16\" width=\"16\" type=\"image/x-icon\">{{ project.config('favicon') }}</Image>
    {% endif -%}
    <Url type=\"text/html\" method=\"GET\" template=\"{{ project.getBaseUrl()|replace({'%version%': project.version}) ~ '/search.html?search={searchTerms}&utm_source={referrer:source?}' }}\"/>
    <InputEncoding>UTF-8</InputEncoding>
    <AdultContent>false</AdultContent>
</OpenSearchDescription>
{% endif %}
", "opensearch.twig", "C:\\xampp\\htdocs\\opencart-master\\tools\\doctum\\src\\Resources\\themes\\default\\opensearch.twig");
    }
}
