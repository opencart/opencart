<?php

/* extension/dashboard/recent_info.twig */
class __TwigTemplate_146123d5eae8ecd4ca9938b2284b51f02213bdad5974546adc7578f328cabe1c extends Twig_Template
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
        echo "<div class=\"card mb-3\">
  <div class=\"card-header\"><i class=\"fas fa-shopping-cart\"></i>";
        // line 2
        echo ($context["heading_title"] ?? null);
        echo "</div>
  <div class=\"table-responsive\">
    <table class=\"table\">
      <thead>
        <tr>
          <td class=\"text-right\">";
        // line 7
        echo ($context["column_order_id"] ?? null);
        echo "</td>
          <td>";
        // line 8
        echo ($context["column_customer"] ?? null);
        echo "</td>
          <td>";
        // line 9
        echo ($context["column_status"] ?? null);
        echo "</td>
          <td>";
        // line 10
        echo ($context["column_date_added"] ?? null);
        echo "</td>
          <td class=\"text-right\">";
        // line 11
        echo ($context["column_total"] ?? null);
        echo "</td>
          <td class=\"text-right\">";
        // line 12
        echo ($context["column_action"] ?? null);
        echo "</td>
        </tr>
      </thead>
      <tbody>";
        // line 16
        if (($context["orders"] ?? null)) {
            // line 17
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["orders"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["order"]) {
                // line 18
                echo "            <tr>
              <td class=\"text-right\">";
                // line 19
                echo twig_get_attribute($this->env, $this->source, $context["order"], "order_id", []);
                echo "</td>
              <td>";
                // line 20
                echo twig_get_attribute($this->env, $this->source, $context["order"], "customer", []);
                echo "</td>
              <td>";
                // line 21
                echo twig_get_attribute($this->env, $this->source, $context["order"], "status", []);
                echo "</td>
              <td>";
                // line 22
                echo twig_get_attribute($this->env, $this->source, $context["order"], "date_added", []);
                echo "</td>
              <td class=\"text-right\">";
                // line 23
                echo twig_get_attribute($this->env, $this->source, $context["order"], "total", []);
                echo "</td>
              <td class=\"text-right\"><a href=\"";
                // line 24
                echo twig_get_attribute($this->env, $this->source, $context["order"], "view", []);
                echo "\" data-toggle=\"tooltip\" title=\"";
                echo ($context["button_view"] ?? null);
                echo "\" class=\"btn btn-info\"><i class=\"fas fa-eye\"></i></a></td>
            </tr>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['order'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        } else {
            // line 28
            echo "          <tr>
            <td class=\"text-center\" colspan=\"6\">";
            // line 29
            echo ($context["text_no_results"] ?? null);
            echo "</td>
          </tr>";
        }
        // line 32
        echo "      </tbody>
    </table>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "extension/dashboard/recent_info.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  108 => 32,  103 => 29,  100 => 28,  89 => 24,  85 => 23,  81 => 22,  77 => 21,  73 => 20,  69 => 19,  66 => 18,  62 => 17,  60 => 16,  54 => 12,  50 => 11,  46 => 10,  42 => 9,  38 => 8,  34 => 7,  26 => 2,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "extension/dashboard/recent_info.twig", "");
    }
}
