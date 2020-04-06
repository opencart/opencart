<?php

/* default/template/extension/module/category.twig */
class __TwigTemplate_b7177d06e9d3f30ead249820f74b5b2002952034d45399780417e29c5db50f39 extends Twig_Template
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
        echo "<div class=\"list-group mb-3\">";
        // line 2
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["categories"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["category"]) {
            // line 3
            if ((twig_get_attribute($this->env, $this->source, $context["category"], "category_id", []) == ($context["category_id"] ?? null))) {
                // line 4
                echo "      <a href=\"";
                echo twig_get_attribute($this->env, $this->source, $context["category"], "href", []);
                echo "\" class=\"list-group-item active\">";
                echo twig_get_attribute($this->env, $this->source, $context["category"], "name", []);
                echo "</a>";
                // line 5
                if (twig_get_attribute($this->env, $this->source, $context["category"], "children", [])) {
                    // line 6
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["category"], "children", []));
                    foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
                        // line 7
                        if ((twig_get_attribute($this->env, $this->source, $context["child"], "category_id", []) == ($context["child_id"] ?? null))) {
                            // line 8
                            echo "            <a href=\"";
                            echo twig_get_attribute($this->env, $this->source, $context["child"], "href", []);
                            echo "\" class=\"list-group-item active\">&nbsp;&nbsp;&nbsp;-";
                            echo twig_get_attribute($this->env, $this->source, $context["child"], "name", []);
                            echo "</a>";
                        } else {
                            // line 10
                            echo "            <a href=\"";
                            echo twig_get_attribute($this->env, $this->source, $context["child"], "href", []);
                            echo "\" class=\"list-group-item\">&nbsp;&nbsp;&nbsp;-";
                            echo twig_get_attribute($this->env, $this->source, $context["child"], "name", []);
                            echo "</a>";
                        }
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                }
            } else {
                // line 14
                echo " <a href=\"";
                echo twig_get_attribute($this->env, $this->source, $context["category"], "href", []);
                echo "\" class=\"list-group-item\">";
                echo twig_get_attribute($this->env, $this->source, $context["category"], "name", []);
                echo "</a>";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['category'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 17
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "default/template/extension/module/category.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  76 => 17,  65 => 14,  52 => 10,  45 => 8,  43 => 7,  39 => 6,  37 => 5,  31 => 4,  29 => 3,  25 => 2,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "default/template/extension/module/category.twig", "");
    }
}
