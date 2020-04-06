<?php

/* common/pagination.twig */
class __TwigTemplate_65f6906985b7c4a59e94d0e9e9721fe3de764d0e78066661b3a1aa23ef048c0c extends Twig_Template
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
        echo "<ul class=\"pagination\">";
        // line 2
        if (($context["first"] ?? null)) {
            // line 3
            echo "    <li class=\"page-item\"><a href=\"";
            echo ($context["first"] ?? null);
            echo "\" class=\"page-link\">|&lt;</a></li>";
        }
        // line 5
        if (($context["prev"] ?? null)) {
            // line 6
            echo "    <li class=\"page-item\"><a href=\"";
            echo ($context["prev"] ?? null);
            echo "\" class=\"page-link\">&lt;</a></li>";
        }
        // line 8
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["links"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["link"]) {
            // line 9
            if ((twig_get_attribute($this->env, $this->source, $context["link"], "page", []) == ($context["page"] ?? null))) {
                // line 10
                echo "      <li class=\"page-item active\"><span class=\"page-link\">";
                echo twig_get_attribute($this->env, $this->source, $context["link"], "page", []);
                echo "</span></li>";
            } else {
                // line 12
                echo "      <li class=\"page-item\"><a href=\"";
                echo twig_get_attribute($this->env, $this->source, $context["link"], "href", []);
                echo "\" class=\"page-link\">";
                echo twig_get_attribute($this->env, $this->source, $context["link"], "page", []);
                echo "</a></li>";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['link'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 15
        if (($context["next"] ?? null)) {
            // line 16
            echo "    <li class=\"page-item\"><a href=\"";
            echo ($context["next"] ?? null);
            echo "\" class=\"page-link\">&gt;</a></li>";
        }
        // line 18
        if (($context["last"] ?? null)) {
            // line 19
            echo "    <li class=\"page-item\"><a href=\"";
            echo ($context["last"] ?? null);
            echo "\" class=\"page-link\">&gt;|</a></li>";
        }
        // line 21
        echo "</ul>";
    }

    public function getTemplateName()
    {
        return "common/pagination.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  75 => 21,  70 => 19,  68 => 18,  63 => 16,  61 => 15,  50 => 12,  45 => 10,  43 => 9,  39 => 8,  34 => 6,  32 => 5,  27 => 3,  25 => 2,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "common/pagination.twig", "");
    }
}
