<?php

/* default/template/common/cart.twig */
class __TwigTemplate_368a4b555f69f32392f9892a31e961ed2412fd3b1e1cd72bf8a63f309217ce6a extends Twig_Template
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
        echo "<div id=\"cart\">
  <button type=\"button\" data-toggle=\"dropdown\" data-loading-text=\"";
        // line 2
        echo ($context["text_loading"] ?? null);
        echo "\" class=\"btn btn-inverse btn-block btn-lg dropdown-toggle\"><i class=\"fas fa-shopping-cart\"></i> <span id=\"cart-total\">";
        echo ($context["text_items"] ?? null);
        echo "</span></button>

  <ul class=\"dropdown-menu dropdown-menu-right\">";
        // line 5
        if ((($context["products"] ?? null) || ($context["vouchers"] ?? null))) {
            // line 6
            echo "    <li>
      <table class=\"table table-sm table-striped\">";
            // line 8
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["products"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
                // line 9
                echo "        <tr>
          <td class=\"text-center\">";
                // line 10
                if (twig_get_attribute($this->env, $this->source, $context["product"], "thumb", [])) {
                    echo " <a href=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["product"], "href", []);
                    echo "\"><img src=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["product"], "thumb", []);
                    echo "\" alt=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["product"], "name", []);
                    echo "\" title=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["product"], "name", []);
                    echo "\" class=\"img-thumbnail\" /></a>";
                }
                echo "</td>
          <td class=\"text-left\"><a href=\"";
                // line 11
                echo twig_get_attribute($this->env, $this->source, $context["product"], "href", []);
                echo "\">";
                echo twig_get_attribute($this->env, $this->source, $context["product"], "name", []);
                echo "</a>";
                // line 12
                if (twig_get_attribute($this->env, $this->source, $context["product"], "option", [])) {
                    // line 13
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["product"], "option", []));
                    foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
                        echo "<br />
            - <small>";
                        // line 14
                        echo twig_get_attribute($this->env, $this->source, $context["option"], "name", []);
                        echo twig_get_attribute($this->env, $this->source, $context["option"], "value", []);
                        echo "</small>";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                }
                // line 16
                if (twig_get_attribute($this->env, $this->source, $context["product"], "recurring", [])) {
                    echo "<br />
            - <small>";
                    // line 17
                    echo ($context["text_recurring"] ?? null);
                    echo twig_get_attribute($this->env, $this->source, $context["product"], "recurring", []);
                    echo "</small>";
                }
                echo "</td>
          <td class=\"text-right\">x";
                // line 18
                echo twig_get_attribute($this->env, $this->source, $context["product"], "quantity", []);
                echo "</td>
          <td class=\"text-right\">";
                // line 19
                echo twig_get_attribute($this->env, $this->source, $context["product"], "total", []);
                echo "</td>
          <td class=\"text-center\"><button type=\"button\" onclick=\"cart.remove('";
                // line 20
                echo twig_get_attribute($this->env, $this->source, $context["product"], "cart_id", []);
                echo "');\" data-toggle=\"tooltip\" title=\"";
                echo ($context["button_remove"] ?? null);
                echo "\" class=\"btn btn-danger btn-sm\"><i class=\"fas fa-times\"></i></button></td>
        </tr>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['product'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 23
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["vouchers"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["voucher"]) {
                // line 24
                echo "        <tr>
          <td class=\"text-center\"></td>
          <td class=\"text-left\">";
                // line 26
                echo twig_get_attribute($this->env, $this->source, $context["voucher"], "description", []);
                echo "</td>
          <td class=\"text-right\">x&nbsp;1</td>
          <td class=\"text-right\">";
                // line 28
                echo twig_get_attribute($this->env, $this->source, $context["voucher"], "amount", []);
                echo "</td>
          <td class=\"text-center text-danger\"><button type=\"button\" onclick=\"voucher.remove('";
                // line 29
                echo twig_get_attribute($this->env, $this->source, $context["voucher"], "key", []);
                echo "');\" data-toggle=\"tooltip\" title=\"";
                echo ($context["button_remove"] ?? null);
                echo "\" class=\"btn btn-danger btn-sm\"><i class=\"fas fa-times\"></i></button></td>
        </tr>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['voucher'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 32
            echo "      </table>
    </li>
    <li>
      <div>
        <table class=\"table table-sm table-bordered\">";
            // line 37
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["totals"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["total"]) {
                // line 38
                echo "          <tr>
            <td class=\"text-right\"><strong>";
                // line 39
                echo twig_get_attribute($this->env, $this->source, $context["total"], "title", []);
                echo "</strong></td>
            <td class=\"text-right\">";
                // line 40
                echo twig_get_attribute($this->env, $this->source, $context["total"], "text", []);
                echo "</td>
          </tr>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['total'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 43
            echo "        </table>
        <p class=\"text-right\"><a href=\"";
            // line 44
            echo ($context["cart"] ?? null);
            echo "\"><strong><i class=\"fas fa-shopping-cart\"></i>";
            echo ($context["text_cart"] ?? null);
            echo "</strong></a>&nbsp;&nbsp;&nbsp;<a href=\"";
            echo ($context["checkout"] ?? null);
            echo "\"><strong><i class=\"fas fa-share\"></i>";
            echo ($context["text_checkout"] ?? null);
            echo "</strong></a></p>
      </div>
    </li>";
        } else {
            // line 48
            echo "    <li>
      <p class=\"text-center\">";
            // line 49
            echo ($context["text_no_results"] ?? null);
            echo "</p>
    </li>";
        }
        // line 52
        echo "  </ul>
</div>
";
    }

    public function getTemplateName()
    {
        return "default/template/common/cart.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  185 => 52,  180 => 49,  177 => 48,  165 => 44,  162 => 43,  154 => 40,  150 => 39,  147 => 38,  143 => 37,  137 => 32,  127 => 29,  123 => 28,  118 => 26,  114 => 24,  110 => 23,  100 => 20,  96 => 19,  92 => 18,  85 => 17,  81 => 16,  72 => 14,  66 => 13,  64 => 12,  59 => 11,  45 => 10,  42 => 9,  38 => 8,  35 => 6,  33 => 5,  26 => 2,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "default/template/common/cart.twig", "");
    }
}
