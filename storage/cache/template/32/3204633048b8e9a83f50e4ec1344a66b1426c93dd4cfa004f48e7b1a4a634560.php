<?php

/* common/column_left.twig */
class __TwigTemplate_7809f8c6d58eb699a07192a9af32c870559deb43d06b54633e8935f242a077d7 extends Twig_Template
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
        echo ($context["menus"] ?? null);
        // line 6
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["menus"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["menu"]) {
            // line 7
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
            // line 8
            if (twig_get_attribute($this->env, $this->source, $context["menu"], "children", [])) {
                // line 9
                echo "          <ul id=\"collapse";
                echo ($context["i"] ?? null);
                echo "\" class=\"collapse\">";
                // line 10
                $context["j"] = 0;
                // line 11
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["menu"], "children", []));
                foreach ($context['_seq'] as $context["_key"] => $context["children_1"]) {
                    // line 12
                    echo "              <li>";
                    if (twig_get_attribute($this->env, $this->source, $context["children_1"], "href", [])) {
                        // line 13
                        echo "                  <a href=\"";
                        echo twig_get_attribute($this->env, $this->source, $context["children_1"], "href", []);
                        echo "\">";
                        echo twig_get_attribute($this->env, $this->source, $context["children_1"], "name", []);
                        echo "</a>";
                    } else {
                        // line 15
                        echo "                  <a href=\"#collapse";
                        echo ($context["i"] ?? null);
                        echo "-";
                        echo ($context["j"] ?? null);
                        echo "\" data-toggle=\"collapse\" class=\"parent collapsed\">";
                        echo twig_get_attribute($this->env, $this->source, $context["children_1"], "name", []);
                        echo "</a>";
                    }
                    // line 17
                    if (twig_get_attribute($this->env, $this->source, $context["children_1"], "children", [])) {
                        // line 18
                        echo "                  <ul id=\"collapse";
                        echo ($context["i"] ?? null);
                        echo "-";
                        echo ($context["j"] ?? null);
                        echo "\" class=\"collapse\">";
                        // line 19
                        $context["k"] = 0;
                        // line 20
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["children_1"], "children", []));
                        foreach ($context['_seq'] as $context["_key"] => $context["children_2"]) {
                            // line 21
                            echo "                      <li>";
                            if (twig_get_attribute($this->env, $this->source, $context["children_2"], "href", [])) {
                                // line 22
                                echo "                          <a href=\"";
                                echo twig_get_attribute($this->env, $this->source, $context["children_2"], "href", []);
                                echo "\">";
                                echo twig_get_attribute($this->env, $this->source, $context["children_2"], "name", []);
                                echo "</a>";
                            } else {
                                // line 24
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
                            // line 26
                            if (twig_get_attribute($this->env, $this->source, $context["children_2"], "children", [])) {
                                // line 27
                                echo "                          <ul id=\"collapse-";
                                echo ($context["i"] ?? null);
                                echo "-";
                                echo ($context["j"] ?? null);
                                echo "-";
                                echo ($context["k"] ?? null);
                                echo "\" class=\"collapse\">";
                                // line 28
                                $context['_parent'] = $context;
                                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["children_2"], "children", []));
                                foreach ($context['_seq'] as $context["_key"] => $context["children_3"]) {
                                    // line 29
                                    echo "                              <li><a href=\"";
                                    echo twig_get_attribute($this->env, $this->source, $context["children_3"], "href", []);
                                    echo "\">";
                                    echo twig_get_attribute($this->env, $this->source, $context["children_3"], "name", []);
                                    echo "</a></li>";
                                }
                                $_parent = $context['_parent'];
                                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['children_3'], $context['_parent'], $context['loop']);
                                $context = array_intersect_key($context, $_parent) + $_parent;
                                // line 31
                                echo "                          </ul>";
                            }
                            // line 32
                            echo "</li>";
                            // line 33
                            $context["k"] = (($context["k"] ?? null) + 1);
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['children_2'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 35
                        echo "                  </ul>";
                    }
                    // line 36
                    echo " </li>";
                    // line 37
                    $context["j"] = (($context["j"] ?? null) + 1);
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['children_1'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 39
                echo "          </ul>";
            }
            // line 41
            echo "      </li>";
            // line 42
            $context["i"] = (($context["i"] ?? null) + 1);
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['menu'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 44
        echo "  </ul>
  <div id=\"stats\">
    <ul>
      <li>
        <div>";
        // line 48
        echo ($context["text_complete_status"] ?? null);
        echo " <span class=\"float-right\">";
        echo ($context["complete_status"] ?? null);
        echo "%</span></div>
        <div class=\"progress\">
          <div class=\"progress-bar progress-bar-success\" role=\"progressbar\" aria-valuenow=\"";
        // line 50
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
        // line 54
        echo ($context["text_processing_status"] ?? null);
        echo " <span class=\"float-right\">";
        echo ($context["processing_status"] ?? null);
        echo "%</span></div>
        <div class=\"progress\">
          <div class=\"progress-bar progress-bar-warning\" role=\"progressbar\" aria-valuenow=\"";
        // line 56
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
        // line 60
        echo ($context["text_other_status"] ?? null);
        echo " <span class=\"float-right\">";
        echo ($context["other_status"] ?? null);
        echo "%</span></div>
        <div class=\"progress\">
          <div class=\"progress-bar progress-bar-danger\" role=\"progressbar\" aria-valuenow=\"";
        // line 62
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
        return array (  231 => 62,  224 => 60,  213 => 56,  206 => 54,  195 => 50,  188 => 48,  182 => 44,  176 => 42,  174 => 41,  171 => 39,  165 => 37,  163 => 36,  160 => 35,  154 => 33,  152 => 32,  149 => 31,  139 => 29,  135 => 28,  127 => 27,  125 => 26,  114 => 24,  107 => 22,  104 => 21,  100 => 20,  98 => 19,  92 => 18,  90 => 17,  81 => 15,  74 => 13,  71 => 12,  67 => 11,  65 => 10,  61 => 9,  59 => 8,  38 => 7,  34 => 6,  32 => 5,  30 => 4,  26 => 2,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "common/column_left.twig", "");
    }
}
