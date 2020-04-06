<?php

/* common/column_left.twig */
class __TwigTemplate_5b13e3a436a9041e6555ff64899fcd4c4221e61b28cca176992401c9327f374e extends Twig_Template
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
        echo "<nav id=\"column-left\">
  <div id=\"navigation\"><span class=\"fas fa-bars\"></span>";
        // line 2
        echo ($context["text_navigation"] ?? null);
        echo "</div>
  <ul id=\"menu\">";
        // line 4
        $context["i"] = 0;
        // line 5
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["menus"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["menu"]) {
            // line 6
            echo "      <li id=\"";
            echo twig_get_attribute($this->env, $this->source, $context["menu"], "id", []);
            echo "\">";
            if (twig_get_attribute($this->env, $this->source, $context["menu"], "href", [])) {
                echo "<a href=\"";
                echo twig_get_attribute($this->env, $this->source, $context["menu"], "href", []);
                echo "\"><i class=\"fas";
                echo twig_get_attribute($this->env, $this->source, $context["menu"], "icon", []);
                echo " fw\"></i>";
                echo twig_get_attribute($this->env, $this->source, $context["menu"], "name", []);
                echo "</a>";
            } else {
                echo "<a href=\"#collapse";
                echo ($context["i"] ?? null);
                echo "\" data-toggle=\"collapse\" class=\"parent collapsed\"><i class=\"fas";
                echo twig_get_attribute($this->env, $this->source, $context["menu"], "icon", []);
                echo " fw\"></i>";
                echo twig_get_attribute($this->env, $this->source, $context["menu"], "name", []);
                echo "</a>";
            }
            // line 7
            if (twig_get_attribute($this->env, $this->source, $context["menu"], "children", [])) {
                // line 8
                echo "          <ul id=\"collapse";
                echo ($context["i"] ?? null);
                echo "\" class=\"collapse\">";
                // line 9
                $context["j"] = 0;
                // line 10
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["menu"], "children", []));
                foreach ($context['_seq'] as $context["_key"] => $context["children_1"]) {
                    // line 11
                    echo "              <li>";
                    if (twig_get_attribute($this->env, $this->source, $context["children_1"], "href", [])) {
                        // line 12
                        echo "                  <a href=\"";
                        echo twig_get_attribute($this->env, $this->source, $context["children_1"], "href", []);
                        echo "\">";
                        echo twig_get_attribute($this->env, $this->source, $context["children_1"], "name", []);
                        echo "</a>";
                    } else {
                        // line 14
                        echo "                  <a href=\"#collapse";
                        echo ($context["i"] ?? null);
                        echo "-";
                        echo ($context["j"] ?? null);
                        echo "\" data-toggle=\"collapse\" class=\"parent collapsed\">";
                        echo twig_get_attribute($this->env, $this->source, $context["children_1"], "name", []);
                        echo "</a>";
                    }
                    // line 16
                    if (twig_get_attribute($this->env, $this->source, $context["children_1"], "children", [])) {
                        // line 17
                        echo "                  <ul id=\"collapse";
                        echo ($context["i"] ?? null);
                        echo "-";
                        echo ($context["j"] ?? null);
                        echo "\" class=\"collapse\">";
                        // line 18
                        $context["k"] = 0;
                        // line 19
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["children_1"], "children", []));
                        foreach ($context['_seq'] as $context["_key"] => $context["children_2"]) {
                            // line 20
                            echo "                      <li>";
                            if (twig_get_attribute($this->env, $this->source, $context["children_2"], "href", [])) {
                                // line 21
                                echo "                          <a href=\"";
                                echo twig_get_attribute($this->env, $this->source, $context["children_2"], "href", []);
                                echo "\">";
                                echo twig_get_attribute($this->env, $this->source, $context["children_2"], "name", []);
                                echo "</a>";
                            } else {
                                // line 23
                                echo "                          <a href=\"#collapse-";
                                echo ($context["i"] ?? null);
                                echo "-";
                                echo ($context["j"] ?? null);
                                echo "-";
                                echo ($context["k"] ?? null);
                                echo "\" data-toggle=\"collapse\" class=\"parent collapsed\">";
                                echo twig_get_attribute($this->env, $this->source, $context["children_2"], "name", []);
                                echo "</a>";
                            }
                            // line 25
                            if (twig_get_attribute($this->env, $this->source, $context["children_2"], "children", [])) {
                                // line 26
                                echo "                          <ul id=\"collapse-";
                                echo ($context["i"] ?? null);
                                echo "-";
                                echo ($context["j"] ?? null);
                                echo "-";
                                echo ($context["k"] ?? null);
                                echo "\" class=\"collapse\">";
                                // line 27
                                $context['_parent'] = $context;
                                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["children_2"], "children", []));
                                foreach ($context['_seq'] as $context["_key"] => $context["children_3"]) {
                                    // line 28
                                    echo "                              <li><a href=\"";
                                    echo twig_get_attribute($this->env, $this->source, $context["children_3"], "href", []);
                                    echo "\">";
                                    echo twig_get_attribute($this->env, $this->source, $context["children_3"], "name", []);
                                    echo "</a></li>";
                                }
                                $_parent = $context['_parent'];
                                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['children_3'], $context['_parent'], $context['loop']);
                                $context = array_intersect_key($context, $_parent) + $_parent;
                                // line 30
                                echo "                          </ul>";
                            }
                            // line 31
                            echo "</li>";
                            // line 32
                            $context["k"] = (($context["k"] ?? null) + 1);
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['children_2'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 34
                        echo "                  </ul>";
                    }
                    // line 35
                    echo " </li>";
                    // line 36
                    $context["j"] = (($context["j"] ?? null) + 1);
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['children_1'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 38
                echo "          </ul>";
            }
            // line 40
            echo "      </li>";
            // line 41
            $context["i"] = (($context["i"] ?? null) + 1);
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['menu'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 43
        echo "  </ul>
  <div id=\"stats\">
    <ul>
      <li>
        <div>";
        // line 47
        echo ($context["text_complete_status"] ?? null);
        echo " <span class=\"float-right\">";
        echo ($context["complete_status"] ?? null);
        echo "%</span></div>
        <div class=\"progress\">
          <div class=\"progress-bar progress-bar-success\" role=\"progressbar\" aria-valuenow=\"";
        // line 49
        echo ($context["complete_status"] ?? null);
        echo "\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:";
        echo ($context["complete_status"] ?? null);
        echo "%\"><span class=\"sr-only\">";
        echo ($context["complete_status"] ?? null);
        echo "%</span></div>
        </div>
      </li>
      <li>
        <div>";
        // line 53
        echo ($context["text_processing_status"] ?? null);
        echo " <span class=\"float-right\">";
        echo ($context["processing_status"] ?? null);
        echo "%</span></div>
        <div class=\"progress\">
          <div class=\"progress-bar progress-bar-warning\" role=\"progressbar\" aria-valuenow=\"";
        // line 55
        echo ($context["processing_status"] ?? null);
        echo "\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:";
        echo ($context["processing_status"] ?? null);
        echo "%\"><span class=\"sr-only\">";
        echo ($context["processing_status"] ?? null);
        echo "%</span></div>
        </div>
      </li>
      <li>
        <div>";
        // line 59
        echo ($context["text_other_status"] ?? null);
        echo " <span class=\"float-right\">";
        echo ($context["other_status"] ?? null);
        echo "%</span></div>
        <div class=\"progress\">
          <div class=\"progress-bar progress-bar-danger\" role=\"progressbar\" aria-valuenow=\"";
        // line 61
        echo ($context["other_status"] ?? null);
        echo "\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:";
        echo ($context["other_status"] ?? null);
        echo "%\"><span class=\"sr-only\">";
        echo ($context["other_status"] ?? null);
        echo "%</span></div>
        </div>
      </li>
    </ul>
  </div>
</nav>";
    }

    public function getTemplateName()
    {
        return "common/column_left.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  229 => 61,  222 => 59,  211 => 55,  204 => 53,  193 => 49,  186 => 47,  180 => 43,  174 => 41,  172 => 40,  169 => 38,  163 => 36,  161 => 35,  158 => 34,  152 => 32,  150 => 31,  147 => 30,  137 => 28,  133 => 27,  125 => 26,  123 => 25,  112 => 23,  105 => 21,  102 => 20,  98 => 19,  96 => 18,  90 => 17,  88 => 16,  79 => 14,  72 => 12,  69 => 11,  65 => 10,  63 => 9,  59 => 8,  57 => 7,  36 => 6,  32 => 5,  30 => 4,  26 => 2,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "common/column_left.twig", "");
    }
}
