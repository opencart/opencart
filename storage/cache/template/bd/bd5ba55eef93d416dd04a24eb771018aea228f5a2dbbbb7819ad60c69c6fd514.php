<?php

/* common/header.twig */
class __TwigTemplate_14f0a1305aa2bd6e896be4cab3194b5703206b140d936032f2b858955e118e67 extends Twig_Template
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
        echo "<!DOCTYPE html>
<html dir=\"";
        // line 2
        echo ($context["direction"] ?? null);
        echo "\" lang=\"";
        echo ($context["lang"] ?? null);
        echo "\">
<head>
  <meta charset=\"UTF-8\"/>
  <title>";
        // line 5
        echo ($context["title"] ?? null);
        echo "</title>
  <base href=\"";
        // line 6
        echo ($context["base"] ?? null);
        echo "\"/>";
        // line 7
        if (($context["description"] ?? null)) {
            // line 8
            echo "    <meta name=\"description\" content=\"";
            echo ($context["description"] ?? null);
            echo "\"/>";
        }
        // line 10
        if (($context["keywords"] ?? null)) {
            // line 11
            echo "    <meta name=\"keywords\" content=\"";
            echo ($context["keywords"] ?? null);
            echo "\"/>";
        }
        // line 13
        echo "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\"/>
  <link type=\"text/css\" href=\"view/stylesheet/bootstrap.css\" rel=\"stylesheet\"/>
  <link type=\"text/css\" href=\"view/stylesheet/stylesheet.css\" rel=\"stylesheet\" media=\"screen\"/>
  <link type=\"text/css\" href=\"view/javascript/font-awesome/css/fontawesome-all.css\" rel=\"stylesheet\"/>
  <link type=\"text/css\" href=\"view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css\" rel=\"stylesheet\" media=\"screen\"/>
  <script type=\"text/javascript\" src=\"view/javascript/jquery/jquery-3.3.1.min.js\"></script>
  <script type=\"text/javascript\" src=\"view/javascript/popper.min.js\"></script>
  <script type=\"text/javascript\" src=\"view/javascript/bootstrap/js/bootstrap.min.js\"></script>
  <script type=\"text/javascript\" src=\"view/javascript/jquery/datetimepicker/moment/moment.min.js\"></script>
  <script type=\"text/javascript\" src=\"view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js\"></script>
  <script type=\"text/javascript\" src=\"view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.js\"></script>
  <script type=\"text/javascript\" src=\"view/javascript/common.js\"></script>";
        // line 25
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["styles"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["style"]) {
            // line 26
            echo "    <link type=\"text/css\" href=\"";
            echo twig_get_attribute($this->env, $this->source, $context["style"], "href", []);
            echo "\" rel=\"";
            echo twig_get_attribute($this->env, $this->source, $context["style"], "rel", []);
            echo "\" media=\"";
            echo twig_get_attribute($this->env, $this->source, $context["style"], "media", []);
            echo "\"/>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['style'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 28
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["links"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["link"]) {
            // line 29
            echo "    <link href=\"";
            echo twig_get_attribute($this->env, $this->source, $context["link"], "href", []);
            echo "\" rel=\"";
            echo twig_get_attribute($this->env, $this->source, $context["link"], "rel", []);
            echo "\"/>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['link'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 31
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["scripts"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["script"]) {
            // line 32
            echo "    <script type=\"text/javascript\" src=\"";
            echo $context["script"];
            echo "\"></script>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['script'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 34
        echo "</head>
<body>
<div id=\"container\">
  <header id=\"header\" class=\"navbar navbar-expand navbar-light bg-light\">
    <div class=\"container-fluid\">
      <a href=\"";
        // line 39
        echo ($context["home"] ?? null);
        echo "\" class=\"navbar-brand d-none d-md-block border-right\"><img src=\"view/image/logo.png\" alt=\"";
        echo ($context["heading_title"] ?? null);
        echo "\" title=\"";
        echo ($context["heading_title"] ?? null);
        echo "\"/></a>";
        // line 40
        if (($context["logged"] ?? null)) {
            // line 41
            echo "        <a href=\"#\" id=\"button-menu\" class=\"d-inline-block d-md-none border-right\"><span class=\"fas fa-bars\"></span></a>
        <ul class=\"navbar-nav\">
          <li class=\"nav-item dropdown border-left\">
            <a href=\"#\" data-toggle=\"dropdown\" class=\"nav-link dropdown-toggle\"><img src=\"";
            // line 44
            echo ($context["image"] ?? null);
            echo "\" alt=\"";
            echo ($context["firstname"] ?? null);
            echo ($context["lastname"] ?? null);
            echo "\" title=\"";
            echo ($context["username"] ?? null);
            echo "\" id=\"user-profile\" class=\"rounded-circle\"/>&nbsp;&nbsp;";
            echo ($context["firstname"] ?? null);
            echo ($context["lastname"] ?? null);
            echo " <i class=\"fas fa-caret-down fa-fw\"></i></a>
            <div class=\"dropdown-menu dropdown-menu-right\">
              <a href=\"";
            // line 46
            echo ($context["profile"] ?? null);
            echo "\" class=\"dropdown-item\"><i class=\"fas fa-user-circle fa-fw\"></i>";
            echo ($context["text_profile"] ?? null);
            echo "</a>
              <div class=\"dropdown-divider\"></div>
              <h6 class=\"dropdown-header\">";
            // line 48
            echo ($context["text_store"] ?? null);
            echo "</h6>";
            // line 49
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["stores"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["store"]) {
                // line 50
                echo "                <a href=\"";
                echo twig_get_attribute($this->env, $this->source, $context["store"], "href", []);
                echo "\" target=\"_blank\" class=\"dropdown-item\">";
                echo twig_get_attribute($this->env, $this->source, $context["store"], "name", []);
                echo "</a>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['store'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 52
            echo "              <div class=\"dropdown-divider\"></div>
              <h6 class=\"dropdown-header\">";
            // line 53
            echo ($context["text_help"] ?? null);
            echo "</h6>
              <a href=\"https://www.opencart.com\" target=\"_blank\" class=\"dropdown-item\"><i class=\"fab fa-opencart fa-fw\"></i>";
            // line 54
            echo ($context["text_homepage"] ?? null);
            echo "</a> <a href=\"http://docs.opencart.com\" target=\"_blank\" class=\"dropdown-item\"><i class=\"fas fa-file-alt fa-fw\"></i>";
            echo ($context["text_documentation"] ?? null);
            echo "</a> <a href=\"http://forum.opencart.com\" target=\"_blank\" class=\"dropdown-item\"><i class=\"fas fa-comments fa-fw\"></i>";
            echo ($context["text_support"] ?? null);
            echo "</a>
            </div>
          </li>
          <li class=\"nav-item border-left\"><a href=\"";
            // line 57
            echo ($context["logout"] ?? null);
            echo "\" class=\"nav-link\"><i class=\"fas fa-sign-out-alt\"></i> <span class=\"d-none d-md-inline\">";
            echo ($context["text_logout"] ?? null);
            echo "</span></a></li>
        </ul>";
        }
        // line 60
        echo "    </div>
  </header>
";
    }

    public function getTemplateName()
    {
        return "common/header.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  192 => 60,  185 => 57,  175 => 54,  171 => 53,  168 => 52,  158 => 50,  154 => 49,  151 => 48,  144 => 46,  131 => 44,  126 => 41,  124 => 40,  117 => 39,  110 => 34,  102 => 32,  98 => 31,  88 => 29,  84 => 28,  72 => 26,  68 => 25,  55 => 13,  50 => 11,  48 => 10,  43 => 8,  41 => 7,  38 => 6,  34 => 5,  26 => 2,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "common/header.twig", "");
    }
}
