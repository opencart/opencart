<?php

/* localisation/language_form.twig */
class __TwigTemplate_f222a291b29c93edf6d4d050933b79e742b3c0fc9323d4d4626b47e780d1c4aa extends Twig_Template
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
        echo ($context["column_left"] ?? null);
        echo "
<div id=\"content\">
  <div class=\"page-header\">
    <div class=\"container-fluid\">
      <div class=\"float-right\">
        <button type=\"submit\" form=\"form-language\" data-toggle=\"tooltip\" title=\"";
        // line 6
        echo ($context["button_save"] ?? null);
        echo "\" class=\"btn btn-primary\"><i class=\"fas fa-save\"></i></button>
        <a href=\"";
        // line 7
        echo ($context["cancel"] ?? null);
        echo "\" data-toggle=\"tooltip\" title=\"";
        echo ($context["button_cancel"] ?? null);
        echo "\" class=\"btn btn-light\"><i class=\"fas fa-reply\"></i></a></div>
      <h1>";
        // line 8
        echo ($context["heading_title"] ?? null);
        echo "</h1>
      <ol class=\"breadcrumb\">";
        // line 10
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["breadcrumbs"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["breadcrumb"]) {
            // line 11
            echo "          <li class=\"breadcrumb-item\"><a href=\"";
            echo twig_get_attribute($this->env, $this->source, $context["breadcrumb"], "href", []);
            echo "\">";
            echo twig_get_attribute($this->env, $this->source, $context["breadcrumb"], "text", []);
            echo "</a></li>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['breadcrumb'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 13
        echo "      </ol>
    </div>
  </div>
  <div class=\"container-fluid\">";
        // line 17
        if (($context["error_warning"] ?? null)) {
            // line 18
            echo "      <div class=\"alert alert-danger alert-dismissible\"><i class=\"fas fa-exclamation-circle\"></i>";
            echo ($context["error_warning"] ?? null);
            echo "
        <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
      </div>";
        }
        // line 22
        echo "    <div class=\"card\">
      <div class=\"card-header\"><i class=\"fas fa-pencil-alt\"></i>";
        // line 23
        echo ($context["text_form"] ?? null);
        echo "</div>
      <div class=\"card-body\">
        <form action=\"";
        // line 25
        echo ($context["action"] ?? null);
        echo "\" method=\"post\" enctype=\"multipart/form-data\" id=\"form-language\">
          <div class=\"form-group row required\">
            <label for=\"input-name\" class=\"col-sm-2 col-form-label\">";
        // line 27
        echo ($context["entry_name"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <input type=\"text\" name=\"name\" value=\"";
        // line 29
        echo ($context["name"] ?? null);
        echo "\" placeholder=\"";
        echo ($context["entry_name"] ?? null);
        echo "\" id=\"input-name\" class=\"form-control\"/>";
        // line 30
        if (($context["error_name"] ?? null)) {
            // line 31
            echo "                <div class=\"invalid-tooltip\">";
            echo ($context["error_name"] ?? null);
            echo "</div>";
        }
        // line 33
        echo "            </div>
          </div>
          <div class=\"form-group row required\">
            <label for=\"input-code\" class=\"col-sm-2 col-form-label\">";
        // line 36
        echo ($context["entry_code"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <select name=\"code\" id=\"input-code\" class=\"form-control\">";
        // line 39
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["languages"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["language"]) {
            // line 40
            if (($context["language"] == ($context["code"] ?? null))) {
                // line 41
                echo "                    <option value=\"";
                echo $context["language"];
                echo "\" selected=\"selected\">";
                echo $context["language"];
                echo "</option>";
            } else {
                // line 43
                echo "                    <option value=\"";
                echo $context["language"];
                echo "\">";
                echo $context["language"];
                echo "</option>";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['language'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 46
        echo "              </select>";
        // line 47
        if (($context["error_code"] ?? null)) {
            // line 48
            echo "                <div class=\"invalid-tooltip\">";
            echo ($context["error_code"] ?? null);
            echo "</div>";
        }
        // line 50
        echo "            </div>
          </div>
          <div class=\"form-group row required\">
            <label for=\"input-locale\" class=\"col-sm-2 col-form-label\">";
        // line 53
        echo ($context["entry_locale"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <input type=\"text\" name=\"locale\" value=\"";
        // line 55
        echo ($context["locale"] ?? null);
        echo "\" placeholder=\"";
        echo ($context["entry_locale"] ?? null);
        echo "\" id=\"input-locale\" class=\"form-control\"/>";
        // line 56
        if (($context["error_locale"] ?? null)) {
            // line 57
            echo "                <div class=\"invalid-tooltip\">";
            echo ($context["error_locale"] ?? null);
            echo "</div>";
        }
        // line 59
        echo "              <small class=\"form-text text-muted\">";
        echo ($context["help_locale"] ?? null);
        echo "</small>
            </div>
          </div>
          <div class=\"form-group row\">
            <label for=\"input-status\" class=\"col-sm-2 col-form-label\">";
        // line 63
        echo ($context["entry_status"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <select name=\"status\" id=\"input-status\" class=\"form-control\">";
        // line 66
        if (($context["status"] ?? null)) {
            // line 67
            echo "                  <option value=\"1\" selected=\"selected\">";
            echo ($context["text_enabled"] ?? null);
            echo "</option>
                  <option value=\"0\">";
            // line 68
            echo ($context["text_disabled"] ?? null);
            echo "</option>";
        } else {
            // line 70
            echo "                  <option value=\"1\">";
            echo ($context["text_enabled"] ?? null);
            echo "</option>
                  <option value=\"0\" selected=\"selected\">";
            // line 71
            echo ($context["text_disabled"] ?? null);
            echo "</option>";
        }
        // line 73
        echo "              </select>
              <small class=\"form-text text-muted\">";
        // line 74
        echo ($context["help_status"] ?? null);
        echo "</small>
            </div>
          </div>
          <div class=\"form-group row\">
            <label for=\"input-sort-order\" class=\"col-sm-2 col-form-label\">";
        // line 78
        echo ($context["entry_sort_order"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <input type=\"text\" name=\"sort_order\" value=\"";
        // line 80
        echo ($context["sort_order"] ?? null);
        echo "\" placeholder=\"";
        echo ($context["entry_sort_order"] ?? null);
        echo "\" id=\"input-sort-order\" class=\"form-control\"/>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>";
        // line 88
        echo ($context["footer"] ?? null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "localisation/language_form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  229 => 88,  217 => 80,  212 => 78,  205 => 74,  202 => 73,  198 => 71,  193 => 70,  189 => 68,  184 => 67,  182 => 66,  177 => 63,  169 => 59,  164 => 57,  162 => 56,  157 => 55,  152 => 53,  147 => 50,  142 => 48,  140 => 47,  138 => 46,  127 => 43,  120 => 41,  118 => 40,  114 => 39,  109 => 36,  104 => 33,  99 => 31,  97 => 30,  92 => 29,  87 => 27,  82 => 25,  77 => 23,  74 => 22,  67 => 18,  65 => 17,  60 => 13,  50 => 11,  46 => 10,  42 => 8,  36 => 7,  32 => 6,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "localisation/language_form.twig", "");
    }
}
