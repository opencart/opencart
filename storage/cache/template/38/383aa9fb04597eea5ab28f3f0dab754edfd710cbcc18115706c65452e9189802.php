<?php

/* extension/extension/analytics.twig */
class __TwigTemplate_94a8d7307427d8b68db7a1a321835c7fccc55beef22994ac8b7d9ed868d4bb40 extends Twig_Template
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
        echo ($context["promotion"] ?? null);
        echo "
<fieldset>
  <legend>";
        // line 3
        echo ($context["heading_title"] ?? null);
        echo "</legend>";
        // line 4
        if (($context["error_warning"] ?? null)) {
            // line 5
            echo "    <div class=\"alert alert-danger alert-dismissible\"><i class=\"fas fa-exclamation-circle\"></i>";
            echo ($context["error_warning"] ?? null);
            echo "
      <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    </div>";
        }
        // line 9
        if (($context["success"] ?? null)) {
            // line 10
            echo "    <div class=\"alert alert-success alert-dismissible\"><i class=\"fas fa-check-circle\"></i>";
            echo ($context["success"] ?? null);
            echo "
      <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    </div>";
        }
        // line 14
        echo "  <div class=\"table-responsive\">
    <table class=\"table table-bordered table-hover\">
      <thead>
        <tr>
          <td class=\"text-left\">";
        // line 18
        echo ($context["column_name"] ?? null);
        echo "</td>
          <td class=\"text-left\">";
        // line 19
        echo ($context["column_status"] ?? null);
        echo "</td>
          <td class=\"text-right\">";
        // line 20
        echo ($context["column_action"] ?? null);
        echo "</td>
        </tr>
      </thead>
      <tbody>";
        // line 24
        if (($context["extensions"] ?? null)) {
            // line 25
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["extensions"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["extension"]) {
                // line 26
                echo "            <tr>
              <td class=\"text-left\" colspan=\"2\"><b>";
                // line 27
                echo twig_get_attribute($this->env, $this->source, $context["extension"], "name", []);
                echo "</b></td>
              <td class=\"text-right\">";
                // line 28
                if ( !twig_get_attribute($this->env, $this->source, $context["extension"], "installed", [])) {
                    // line 29
                    echo "                  <a href=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["extension"], "install", []);
                    echo "\" data-toggle=\"tooltip\" title=\"";
                    echo ($context["button_install"] ?? null);
                    echo "\" class=\"btn btn-success\"><i class=\"fas fa-plus-circle\"></i></a>";
                } else {
                    // line 31
                    echo "                  <a href=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["extension"], "uninstall", []);
                    echo "\" data-toggle=\"tooltip\" title=\"";
                    echo ($context["button_uninstall"] ?? null);
                    echo "\" class=\"btn btn-danger\"><i class=\"fas fa-minus-circle\"></i></a>";
                }
                // line 32
                echo "</td>
            </tr>";
                // line 34
                if (twig_get_attribute($this->env, $this->source, $context["extension"], "installed", [])) {
                    // line 35
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["extension"], "store", []));
                    foreach ($context['_seq'] as $context["_key"] => $context["store"]) {
                        // line 36
                        echo "                <tr>
                  <td class=\"text-left\">&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;";
                        // line 37
                        echo twig_get_attribute($this->env, $this->source, $context["store"], "name", []);
                        echo "</td>
                  <td class=\"text-left\">";
                        // line 38
                        echo twig_get_attribute($this->env, $this->source, $context["store"], "status", []);
                        echo "</td>
                  <td class=\"text-right\"><a href=\"";
                        // line 39
                        echo twig_get_attribute($this->env, $this->source, $context["store"], "edit", []);
                        echo "\" data-toggle=\"tooltip\" title=\"";
                        echo ($context["button_edit"] ?? null);
                        echo "\" class=\"btn btn-primary\"><i class=\"fas fa-pencil-alt\"></i></a></td>
                </tr>";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['store'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['extension'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        } else {
            // line 45
            echo "          <tr>
            <td class=\"text-center\" colspan=\"3\">";
            // line 46
            echo ($context["text_no_results"] ?? null);
            echo "</td>
          </tr>";
        }
        // line 49
        echo "      </tbody>
    </table>
  </div>
</fieldset>
";
    }

    public function getTemplateName()
    {
        return "extension/extension/analytics.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  142 => 49,  137 => 46,  134 => 45,  118 => 39,  114 => 38,  110 => 37,  107 => 36,  103 => 35,  101 => 34,  98 => 32,  91 => 31,  84 => 29,  82 => 28,  78 => 27,  75 => 26,  71 => 25,  69 => 24,  63 => 20,  59 => 19,  55 => 18,  49 => 14,  42 => 10,  40 => 9,  33 => 5,  31 => 4,  28 => 3,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "extension/extension/analytics.twig", "");
    }
}
