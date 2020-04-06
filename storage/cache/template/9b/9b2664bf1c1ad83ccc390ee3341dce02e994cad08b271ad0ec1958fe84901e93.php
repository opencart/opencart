<?php

/* user/user_form.twig */
class __TwigTemplate_c7c9ec49e63182a39f2a20f8063a3dd12f0b749a84382fec41f5f96830af33fe extends Twig_Template
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
        <button type=\"submit\" form=\"form-user\" data-toggle=\"tooltip\" title=\"";
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
        echo "\" method=\"post\" enctype=\"multipart/form-data\" id=\"form-user\">
          <div class=\"form-group row required\">
            <label for=\"input-username\" class=\"col-sm-2 col-form-label\">";
        // line 27
        echo ($context["entry_username"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <input type=\"text\" name=\"username\" value=\"";
        // line 29
        echo ($context["username"] ?? null);
        echo "\" placeholder=\"";
        echo ($context["entry_username"] ?? null);
        echo "\" id=\"input-username\" class=\"form-control\"/>";
        // line 30
        if (($context["error_username"] ?? null)) {
            // line 31
            echo "                <div class=\"invalid-tooltip\">";
            echo ($context["error_username"] ?? null);
            echo "</div>";
        }
        // line 33
        echo "            </div>
          </div>
          <div class=\"form-group row\">
            <label for=\"input-user-group\" class=\"col-sm-2 col-form-label\">";
        // line 36
        echo ($context["entry_user_group"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <select name=\"user_group_id\" id=\"input-user-group\" class=\"form-control\">";
        // line 39
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["user_groups"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["user_group"]) {
            // line 40
            if ((twig_get_attribute($this->env, $this->source, $context["user_group"], "user_group_id", []) == ($context["user_group_id"] ?? null))) {
                // line 41
                echo "                    <option value=\"";
                echo twig_get_attribute($this->env, $this->source, $context["user_group"], "user_group_id", []);
                echo "\" selected=\"selected\">";
                echo twig_get_attribute($this->env, $this->source, $context["user_group"], "name", []);
                echo "</option>";
            } else {
                // line 43
                echo "                    <option value=\"";
                echo twig_get_attribute($this->env, $this->source, $context["user_group"], "user_group_id", []);
                echo "\">";
                echo twig_get_attribute($this->env, $this->source, $context["user_group"], "name", []);
                echo "</option>";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['user_group'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 46
        echo "              </select>
            </div>
          </div>
          <div class=\"form-group row required\">
            <label for=\"input-firstname\" class=\"col-sm-2 col-form-label\">";
        // line 50
        echo ($context["entry_firstname"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <input type=\"text\" name=\"firstname\" value=\"";
        // line 52
        echo ($context["firstname"] ?? null);
        echo "\" placeholder=\"";
        echo ($context["entry_firstname"] ?? null);
        echo "\" id=\"input-firstname\" class=\"form-control\"/>";
        // line 53
        if (($context["error_firstname"] ?? null)) {
            // line 54
            echo "                <div class=\"invalid-tooltip\">";
            echo ($context["error_firstname"] ?? null);
            echo "</div>";
        }
        // line 56
        echo "            </div>
          </div>
          <div class=\"form-group row required\">
            <label for=\"input-lastname\" class=\"col-sm-2 col-form-label\">";
        // line 59
        echo ($context["entry_lastname"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <input type=\"text\" name=\"lastname\" value=\"";
        // line 61
        echo ($context["lastname"] ?? null);
        echo "\" placeholder=\"";
        echo ($context["entry_lastname"] ?? null);
        echo "\" id=\"input-lastname\" class=\"form-control\"/>";
        // line 62
        if (($context["error_lastname"] ?? null)) {
            // line 63
            echo "                <div class=\"invalid-tooltip\">";
            echo ($context["error_lastname"] ?? null);
            echo "</div>";
        }
        // line 65
        echo "            </div>
          </div>
          <div class=\"form-group row required\">
            <label for=\"input-email\" class=\"col-sm-2 col-form-label\">";
        // line 68
        echo ($context["entry_email"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <input type=\"text\" name=\"email\" value=\"";
        // line 70
        echo ($context["email"] ?? null);
        echo "\" placeholder=\"";
        echo ($context["entry_email"] ?? null);
        echo "\" id=\"input-email\" class=\"form-control\"/>";
        // line 71
        if (($context["error_email"] ?? null)) {
            // line 72
            echo "                <div class=\"invalid-tooltip\">";
            echo ($context["error_email"] ?? null);
            echo "</div>";
        }
        // line 74
        echo "            </div>
          </div>
          <div class=\"form-group row\">
            <label for=\"input-image\" class=\"col-sm-2 col-form-label\">";
        // line 77
        echo ($context["entry_image"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <div class=\"card image\">
                <img src=\"";
        // line 80
        echo ($context["thumb"] ?? null);
        echo "\" alt=\"\" title=\"\" id=\"thumb-image\" data-placeholder=\"";
        echo ($context["placeholder"] ?? null);
        echo "\" class=\"card-img-top\"/> <input type=\"hidden\" name=\"image\" value=\"";
        echo ($context["image"] ?? null);
        echo "\" id=\"input-image\"/>
                <div class=\"card-body\">
                  <button type=\"button\" data-toggle=\"image\" data-target=\"#input-image\" data-thumb=\"#thumb-image\" class=\"btn btn-primary btn-sm btn-block\"><i class=\"fas fa-pencil-alt\"></i>";
        // line 82
        echo ($context["button_edit"] ?? null);
        echo "</button>
                  <button type=\"button\" data-toggle=\"clear\" data-target=\"#input-image\" data-thumb=\"#thumb-image\" class=\"btn btn-warning btn-sm btn-block\"><i class=\"fas fa-trash-alt\"></i>";
        // line 83
        echo ($context["button_clear"] ?? null);
        echo "</button>
                </div>
              </div>
            </div>
          </div>
          <div class=\"form-group row required\">
            <label for=\"input-password\" class=\"col-sm-2 col-form-label\">";
        // line 89
        echo ($context["entry_password"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <input type=\"password\" name=\"password\" value=\"";
        // line 91
        echo ($context["password"] ?? null);
        echo "\" placeholder=\"";
        echo ($context["entry_password"] ?? null);
        echo "\" id=\"input-password\" class=\"form-control\" autocomplete=\"off\"/>";
        // line 92
        if (($context["error_password"] ?? null)) {
            // line 93
            echo "                <div class=\"invalid-tooltip\">";
            echo ($context["error_password"] ?? null);
            echo "</div>";
        }
        // line 95
        echo "            </div>
          </div>
          <div class=\"form-group row required\">
            <label for=\"input-confirm\" class=\"col-sm-2 col-form-label\">";
        // line 98
        echo ($context["entry_confirm"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <input type=\"password\" name=\"confirm\" value=\"";
        // line 100
        echo ($context["confirm"] ?? null);
        echo "\" placeholder=\"";
        echo ($context["entry_confirm"] ?? null);
        echo "\" id=\"input-confirm\" class=\"form-control\"/>";
        // line 101
        if (($context["error_confirm"] ?? null)) {
            // line 102
            echo "                <div class=\"invalid-tooltip\">";
            echo ($context["error_confirm"] ?? null);
            echo "</div>";
        }
        // line 104
        echo "            </div>
          </div>
          <div class=\"form-group row\">
            <label for=\"input-status\" class=\"col-sm-2 col-form-label\">";
        // line 107
        echo ($context["entry_status"] ?? null);
        echo "</label>
            <div class=\"col-sm-10\">
              <select name=\"status\" id=\"input-status\" class=\"form-control\">";
        // line 110
        if (($context["status"] ?? null)) {
            // line 111
            echo "                  <option value=\"0\">";
            echo ($context["text_disabled"] ?? null);
            echo "</option>
                  <option value=\"1\" selected=\"selected\">";
            // line 112
            echo ($context["text_enabled"] ?? null);
            echo "</option>";
        } else {
            // line 114
            echo "                  <option value=\"0\" selected=\"selected\">";
            echo ($context["text_disabled"] ?? null);
            echo "</option>
                  <option value=\"1\">";
            // line 115
            echo ($context["text_enabled"] ?? null);
            echo "</option>";
        }
        // line 117
        echo "              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>";
        // line 125
        echo ($context["footer"] ?? null);
        echo " ";
    }

    public function getTemplateName()
    {
        return "user/user_form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  316 => 125,  307 => 117,  303 => 115,  298 => 114,  294 => 112,  289 => 111,  287 => 110,  282 => 107,  277 => 104,  272 => 102,  270 => 101,  265 => 100,  260 => 98,  255 => 95,  250 => 93,  248 => 92,  243 => 91,  238 => 89,  229 => 83,  225 => 82,  216 => 80,  210 => 77,  205 => 74,  200 => 72,  198 => 71,  193 => 70,  188 => 68,  183 => 65,  178 => 63,  176 => 62,  171 => 61,  166 => 59,  161 => 56,  156 => 54,  154 => 53,  149 => 52,  144 => 50,  138 => 46,  127 => 43,  120 => 41,  118 => 40,  114 => 39,  109 => 36,  104 => 33,  99 => 31,  97 => 30,  92 => 29,  87 => 27,  82 => 25,  77 => 23,  74 => 22,  67 => 18,  65 => 17,  60 => 13,  50 => 11,  46 => 10,  42 => 8,  36 => 7,  32 => 6,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "user/user_form.twig", "");
    }
}
