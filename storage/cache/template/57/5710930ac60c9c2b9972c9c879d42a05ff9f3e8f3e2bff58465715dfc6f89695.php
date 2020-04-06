<?php

/* default/template/extension/module/featured.twig */
class __TwigTemplate_1c4df71ccb71d9ed6206532efb3a420f38bde2cb458d8ab4f8e249535df71797 extends Twig_Template
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
        echo "<h3>";
        echo ($context["heading_title"] ?? null);
        echo "</h3>
<div class=\"row\">";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["products"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
            // line 4
            echo "    <div class=\"product-layout col-lg-3 col-md-3 col-sm-6 col-12\">
      <div class=\"product-thumb transition\">
        <div class=\"image\"><a href=\"";
            // line 6
            echo twig_get_attribute($this->env, $this->source, $context["product"], "href", []);
            echo "\"><img src=\"";
            echo twig_get_attribute($this->env, $this->source, $context["product"], "thumb", []);
            echo "\" alt=\"";
            echo twig_get_attribute($this->env, $this->source, $context["product"], "name", []);
            echo "\" title=\"";
            echo twig_get_attribute($this->env, $this->source, $context["product"], "name", []);
            echo "\" class=\"img-fluid\"/></a></div>
        <div class=\"caption\">
          <h4><a href=\"";
            // line 8
            echo twig_get_attribute($this->env, $this->source, $context["product"], "href", []);
            echo "\">";
            echo twig_get_attribute($this->env, $this->source, $context["product"], "name", []);
            echo "</a></h4>
          <p>";
            // line 9
            echo twig_get_attribute($this->env, $this->source, $context["product"], "description", []);
            echo "</p>";
            // line 10
            if (twig_get_attribute($this->env, $this->source, $context["product"], "rating", [])) {
                // line 11
                echo "            <div class=\"rating\">";
                // line 12
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(5);
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 13
                    if ((twig_get_attribute($this->env, $this->source, $context["product"], "rating", []) < $context["i"])) {
                        // line 14
                        echo "                  <span class=\"fas fa-stack\"><i class=\"fas fa-star-o fa-stack-2x\"></i></span>";
                    } else {
                        // line 16
                        echo "                  <span class=\"fas fa-stack\"><i class=\"fas fa-star fa-stack-2x\"></i><i class=\"fas fa-star-o fa-stack-2x\"></i></span>";
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 19
                echo "            </div>";
            }
            // line 21
            if (twig_get_attribute($this->env, $this->source, $context["product"], "price", [])) {
                // line 22
                echo "            <p class=\"price\">";
                // line 23
                if ( !twig_get_attribute($this->env, $this->source, $context["product"], "special", [])) {
                    // line 24
                    echo twig_get_attribute($this->env, $this->source, $context["product"], "price", []);
                } else {
                    // line 26
                    echo "                <span class=\"price-new\">";
                    echo twig_get_attribute($this->env, $this->source, $context["product"], "special", []);
                    echo "</span> <span class=\"price-old\">";
                    echo twig_get_attribute($this->env, $this->source, $context["product"], "price", []);
                    echo "</span>";
                }
                // line 28
                if (twig_get_attribute($this->env, $this->source, $context["product"], "tax", [])) {
                    // line 29
                    echo "                <span class=\"price-tax\">";
                    echo ($context["text_tax"] ?? null);
                    echo twig_get_attribute($this->env, $this->source, $context["product"], "tax", []);
                    echo "</span>";
                }
                // line 31
                echo "            </p>";
            }
            // line 33
            echo "        </div>
        <div class=\"button-group\">
          <button type=\"button\" onclick=\"cart.add('";
            // line 35
            echo twig_get_attribute($this->env, $this->source, $context["product"], "product_id", []);
            echo "');\"><i class=\"fas fa-shopping-cart\"></i> <span class=\"d-none d-lg-inline\">";
            echo ($context["button_cart"] ?? null);
            echo "</span></button>
          <button type=\"button\" data-toggle=\"tooltip\" title=\"";
            // line 36
            echo ($context["button_wishlist"] ?? null);
            echo "\" onclick=\"wishlist.add('";
            echo twig_get_attribute($this->env, $this->source, $context["product"], "product_id", []);
            echo "');\"><i class=\"fas fa-heart\"></i></button>
          <button type=\"button\" data-toggle=\"tooltip\" title=\"";
            // line 37
            echo ($context["button_compare"] ?? null);
            echo "\" onclick=\"compare.add('";
            echo twig_get_attribute($this->env, $this->source, $context["product"], "product_id", []);
            echo "');\"><i class=\"fas fa-exchange-alt\"></i></button>
        </div>
      </div>
    </div>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['product'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 42
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "default/template/extension/module/featured.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  134 => 42,  122 => 37,  116 => 36,  110 => 35,  106 => 33,  103 => 31,  97 => 29,  95 => 28,  88 => 26,  85 => 24,  83 => 23,  81 => 22,  79 => 21,  76 => 19,  69 => 16,  66 => 14,  64 => 13,  60 => 12,  58 => 11,  56 => 10,  53 => 9,  47 => 8,  36 => 6,  32 => 4,  28 => 3,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "default/template/extension/module/featured.twig", "");
    }
}
