<?php

/* default/template/product/review.twig */
class __TwigTemplate_2f07bb56c541abcddc9dd4dce154effe73fa8827201441c489fd11b4235d7299 extends Twig_Template
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
        if (($context["reviews"] ?? null)) {
            // line 2
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["reviews"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["review"]) {
                // line 3
                echo "    <table class=\"table table-striped table-bordered\">
      <tr>
        <td style=\"width: 50%;\"><strong>";
                // line 5
                echo twig_get_attribute($this->env, $this->source, $context["review"], "author", []);
                echo "</strong></td>
        <td class=\"text-right\">";
                // line 6
                echo twig_get_attribute($this->env, $this->source, $context["review"], "date_added", []);
                echo "</td>
      </tr>
      <tr>
        <td colspan=\"2\"><p>";
                // line 9
                echo twig_get_attribute($this->env, $this->source, $context["review"], "text", []);
                echo "</p>";
                // line 10
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(range(1, 5));
                foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                    // line 11
                    if ((twig_get_attribute($this->env, $this->source, $context["review"], "rating", []) < $context["i"])) {
                        echo " <span class=\"fas fa-stack\"><i class=\"fas fa-star-o fa-stack-2x\"></i></span>";
                    } else {
                        echo " <span class=\"fas fa-stack\"><i class=\"fas fa-star fa-stack-2x\"></i><i class=\"fas fa-star-o fa-stack-2x\"></i></span>";
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 12
                echo "</td>
      </tr>
    </table>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['review'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 16
            echo "  <div class=\"text-right\">";
            echo ($context["pagination"] ?? null);
            echo "</div>";
        } else {
            // line 18
            echo "  <p class=\"text-center\">";
            echo ($context["text_no_reviews"] ?? null);
            echo "</p>";
        }
        // line 19
        echo " ";
    }

    public function getTemplateName()
    {
        return "default/template/product/review.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 19,  73 => 18,  68 => 16,  60 => 12,  50 => 11,  46 => 10,  43 => 9,  37 => 6,  33 => 5,  29 => 3,  25 => 2,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "default/template/product/review.twig", "");
    }
}
