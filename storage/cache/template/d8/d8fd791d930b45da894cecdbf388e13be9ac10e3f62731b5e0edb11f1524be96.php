<?php

/* design/theme_history.twig */
class __TwigTemplate_7a3c40d9945aef7f9ccc52ae618c3e7e3a8edd8686d8a702cb4b94503dde5f2d extends Twig_Template
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
        echo "<div class=\"table-responsive\">
  <table class=\"table table-bordered\">
    <thead>
      <tr>
        <td class=\"text-left\">";
        // line 5
        echo ($context["column_store"] ?? null);
        echo "</td>
        <td class=\"text-left\">";
        // line 6
        echo ($context["column_route"] ?? null);
        echo "</td>
        <td class=\"text-left\">";
        // line 7
        echo ($context["column_theme"] ?? null);
        echo "</td>
        <td class=\"text-left\">";
        // line 8
        echo ($context["column_date_added"] ?? null);
        echo "</td>
        <td class=\"text-right\">";
        // line 9
        echo ($context["column_action"] ?? null);
        echo "</td>
      </tr>
    </thead>
    <tbody>";
        // line 13
        if (($context["histories"] ?? null)) {
            // line 14
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["histories"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["history"]) {
                // line 15
                echo "          <tr>
            <td class=\"text-left\">";
                // line 16
                echo twig_get_attribute($this->env, $this->source, $context["history"], "store", []);
                echo "
              <input type=\"hidden\" name=\"store_id\" value=\"";
                // line 17
                echo twig_get_attribute($this->env, $this->source, $context["history"], "store_id", []);
                echo "\"/></td>
            <td class=\"text-left\">";
                // line 18
                echo twig_get_attribute($this->env, $this->source, $context["history"], "route", []);
                echo "
              <input type=\"hidden\" name=\"path\" value=\"";
                // line 19
                echo twig_get_attribute($this->env, $this->source, $context["history"], "route", []);
                echo "\"/></td>
            <td class=\"text-left\">";
                // line 20
                echo twig_get_attribute($this->env, $this->source, $context["history"], "theme", []);
                echo "</td>
            <td class=\"text-left\">";
                // line 21
                echo twig_get_attribute($this->env, $this->source, $context["history"], "date_added", []);
                echo "</td>
            <td class=\"text-right\"><a href=\"";
                // line 22
                echo twig_get_attribute($this->env, $this->source, $context["history"], "edit", []);
                echo "\" data-toggle=\"tooltip\" title=\"";
                echo ($context["button_edit"] ?? null);
                echo "\" class=\"btn btn-primary\"><i class=\"fas fa-pencil-alt\"></i></a> <a href=\"";
                echo twig_get_attribute($this->env, $this->source, $context["history"], "delete", []);
                echo "\" data-loading-text=\"";
                echo ($context["text_loading"] ?? null);
                echo "\" data-toggle=\"tooltip\" title=\"";
                echo ($context["button_delete"] ?? null);
                echo "\" class=\"btn btn-danger\"><i class=\"fas fa-trash-alt\"></i></a></td>
          </tr>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['history'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        } else {
            // line 26
            echo "        <tr>
          <td class=\"text-center\" colspan=\"5\">";
            // line 27
            echo ($context["text_no_results"] ?? null);
            echo "</td>
        </tr>";
        }
        // line 30
        echo "    </tbody>
  </table>
</div>
<div class=\"row\">
  <div class=\"col-sm-6 text-left\">";
        // line 34
        echo ($context["pagination"] ?? null);
        echo "</div>
  <div class=\"col-sm-6 text-right\">";
        // line 35
        echo ($context["results"] ?? null);
        echo "</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "design/theme_history.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  119 => 35,  115 => 34,  109 => 30,  104 => 27,  101 => 26,  84 => 22,  80 => 21,  76 => 20,  72 => 19,  68 => 18,  64 => 17,  60 => 16,  57 => 15,  53 => 14,  51 => 13,  45 => 9,  41 => 8,  37 => 7,  33 => 6,  29 => 5,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "design/theme_history.twig", "");
    }
}
