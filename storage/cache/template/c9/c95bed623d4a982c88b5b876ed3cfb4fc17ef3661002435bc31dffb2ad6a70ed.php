<?php

/* install/step_1.twig */
class __TwigTemplate_25f29d7a96988defbff78516ad8465919682153f67c294b339ce4f36c3d24136 extends Twig_Template
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
        <h1 class=\"pull-left\">1<small>/4</small></h1>
        <h3>";
        // line 7
        echo ($context["heading_title"] ?? null);
        echo "<br>
          <small>";
        // line 8
        echo ($context["text_step_1"] ?? null);
        echo "</small></h3>
      </div>
      <div class=\"col-sm-6\">
        <div id=\"logo\" class=\"pull-right hidden-xs\"><img src=\"view/image/logo.png\" alt=\"OpenCart\" title=\"OpenCart\" /></div>
      </div>
    </div>
  </header>
  <div class=\"row\">
    <div class=\"col-sm-9\">
      <form action=\"";
        // line 17
        echo ($context["action"] ?? null);
        echo "\" method=\"post\" enctype=\"multipart/form-data\">
        <div class=\"terms\">";
        // line 18
        echo ($context["text_terms"] ?? null);
        echo "</div>
        <div class=\"buttons\">
          <div class=\"pull-right\">
            <input type=\"submit\" value=\"";
        // line 21
        echo ($context["button_continue"] ?? null);
        echo "\" class=\"btn btn-primary\" />
          </div>
        </div>
      </form>
    </div>
    <div class=\"col-sm-3\">";
        // line 26
        echo ($context["column_left"] ?? null);
        echo "</div>
  </div>
</div>";
        // line 29
        echo ($context["footer"] ?? null);
    }

    public function getTemplateName()
    {
        return "install/step_1.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 29,  66 => 26,  58 => 21,  52 => 18,  48 => 17,  36 => 8,  32 => 7,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "install/step_1.twig", "");
    }
}
