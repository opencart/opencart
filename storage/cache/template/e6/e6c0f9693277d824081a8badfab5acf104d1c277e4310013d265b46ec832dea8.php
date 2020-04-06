<?php

/* default/template/common/menu.twig */
class __TwigTemplate_7ca49fb1c7e76db5e2b3f8c5599d1c1ff79b19c47af0efd31e34b425145f7dfd extends Twig_Template
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
        if (($context["categories"] ?? null)) {
            // line 2
            echo "  <div class=\"container\">
    <nav id=\"menu\" class=\"navbar navbar-expand-lg navbar-light bg-primary\">
      <div id=\"category\" class=\"d-block d-sm-block d-lg-none\">";
            // line 4
            echo ($context["text_category"] ?? null);
            echo "</div>
      <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#narbar-menu\"><i class=\"fas fa-bars\"></i></button>
      <div class=\"collapse navbar-collapse\" id=\"narbar-menu\">
        <ul class=\"navbar-nav\">";
            // line 8
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["categories"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["category"]) {
                // line 9
                if (twig_get_attribute($this->env, $this->source, $context["category"], "children", [])) {
                    // line 10
                    echo "              <li class=\"nav-item dropdown\"><a href=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["category"], "href", []);
                    echo "\" class=\"nav-link dropdown-toggle\" data-toggle=\"dropdown\">";
                    echo twig_get_attribute($this->env, $this->source, $context["category"], "name", []);
                    echo "</a>
                <div class=\"dropdown-menu\">
                  <div class=\"dropdown-inner\">";
                    // line 13
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(twig_array_batch(twig_get_attribute($this->env, $this->source, $context["category"], "children", []), (twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, $context["category"], "children", [])) / twig_round(twig_get_attribute($this->env, $this->source, $context["category"], "column", []), 1, "ceil"))));
                    foreach ($context['_seq'] as $context["_key"] => $context["children"]) {
                        // line 14
                        echo "                      <ul class=\"list-unstyled\">";
                        // line 15
                        $context['_parent'] = $context;
                        $context['_seq'] = twig_ensure_traversable($context["children"]);
                        foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
                            // line 16
                            echo "                          <li><a href=\"";
                            echo twig_get_attribute($this->env, $this->source, $context["child"], "href", []);
                            echo "\" class=\"nav-link\">";
                            echo twig_get_attribute($this->env, $this->source, $context["child"], "name", []);
                            echo "</a></li>";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 18
                        echo "                      </ul>";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['children'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 20
                    echo "                  </div>
                  <a href=\"";
                    // line 21
                    echo twig_get_attribute($this->env, $this->source, $context["category"], "href", []);
                    echo "\" class=\"see-all\">";
                    echo ($context["text_all"] ?? null);
                    echo twig_get_attribute($this->env, $this->source, $context["category"], "name", []);
                    echo "</a>
                </div>
              </li>";
                } else {
                    // line 25
                    echo "              <li class=\"nav-item\"><a href=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["category"], "href", []);
                    echo "\" class=\"nav-link\">";
                    echo twig_get_attribute($this->env, $this->source, $context["category"], "name", []);
                    echo "</a></li>";
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['category'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 28
            echo "        </ul>
      </div>
    </nav>
  </div>";
        }
    }

    public function getTemplateName()
    {
        return "default/template/common/menu.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  98 => 28,  87 => 25,  78 => 21,  75 => 20,  69 => 18,  59 => 16,  55 => 15,  53 => 14,  49 => 13,  41 => 10,  39 => 9,  35 => 8,  29 => 4,  25 => 2,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "default/template/common/menu.twig", "");
    }
}
