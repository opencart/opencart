<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* macros.twig */
class __TwigTemplate_27ad50c49759dc7e9fb42882f712f89b extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "
";
        // line 7
        echo "
";
        // line 11
        echo "
";
        // line 21
        echo "
";
        // line 27
        echo "
";
        // line 33
        echo "
";
        // line 49
        echo "
";
        // line 55
        echo "
";
        // line 69
        echo "
";
        // line 81
        echo "
";
        // line 93
        echo "
";
        // line 115
        echo "
";
        // line 127
        echo "
";
        // line 131
        echo "
";
        // line 147
        echo "
";
        // line 161
        echo "
";
        // line 173
        echo "
";
        // line 179
        echo "
";
    }

    // line 2
    public function macro_class_category_name($__categoryId__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "categoryId" => $__categoryId__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 3
            if (((isset($context["categoryId"]) || array_key_exists("categoryId", $context) ? $context["categoryId"] : (function () { throw new RuntimeError('Variable "categoryId" does not exist.', 3, $this->source); })()) == 1)) {
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("class");
            }
            // line 4
            if (((isset($context["categoryId"]) || array_key_exists("categoryId", $context) ? $context["categoryId"] : (function () { throw new RuntimeError('Variable "categoryId" does not exist.', 4, $this->source); })()) == 2)) {
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("interface");
            }
            // line 5
            if (((isset($context["categoryId"]) || array_key_exists("categoryId", $context) ? $context["categoryId"] : (function () { throw new RuntimeError('Variable "categoryId" does not exist.', 5, $this->source); })()) == 3)) {
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("trait");
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 8
    public function macro_namespace_link($__namespace__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "namespace" => $__namespace__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 9
            echo "<a href=\"";
            echo $this->extensions['Doctum\Renderer\TwigExtension']->pathForNamespace($context, (isset($context["namespace"]) || array_key_exists("namespace", $context) ? $context["namespace"] : (function () { throw new RuntimeError('Variable "namespace" does not exist.', 9, $this->source); })()));
            echo "\">";
            ((((isset($context["namespace"]) || array_key_exists("namespace", $context) ? $context["namespace"] : (function () { throw new RuntimeError('Variable "namespace" does not exist.', 9, $this->source); })()) == "")) ? (print (twig_escape_filter($this->env, Doctum\Tree::getGlobalNamespaceName(), "html", null, true))) : (print ((isset($context["namespace"]) || array_key_exists("namespace", $context) ? $context["namespace"] : (function () { throw new RuntimeError('Variable "namespace" does not exist.', 9, $this->source); })()))));
            echo "</a>";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 12
    public function macro_class_link($__class__ = null, $__absolute__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "class" => $__class__,
            "absolute" => $__absolute__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 13
            if (twig_get_attribute($this->env, $this->source, (isset($context["class"]) || array_key_exists("class", $context) ? $context["class"] : (function () { throw new RuntimeError('Variable "class" does not exist.', 13, $this->source); })()), "isProjectClass", [], "method", false, false, false, 13)) {
                // line 14
                echo "<a href=\"";
                echo $this->extensions['Doctum\Renderer\TwigExtension']->pathForClass($context, (isset($context["class"]) || array_key_exists("class", $context) ? $context["class"] : (function () { throw new RuntimeError('Variable "class" does not exist.', 14, $this->source); })()));
                echo "\">";
            } elseif (twig_get_attribute($this->env, $this->source,             // line 15
(isset($context["class"]) || array_key_exists("class", $context) ? $context["class"] : (function () { throw new RuntimeError('Variable "class" does not exist.', 15, $this->source); })()), "isPhpClass", [], "method", false, false, false, 15)) {
                // line 16
                echo "<a target=\"_blank\" rel=\"noopener\" href=\"https://www.php.net/";
                echo (isset($context["class"]) || array_key_exists("class", $context) ? $context["class"] : (function () { throw new RuntimeError('Variable "class" does not exist.', 16, $this->source); })());
                echo "\">";
            }
            // line 18
            echo $this->env->getFunction('abbr_class')->getCallable()((isset($context["class"]) || array_key_exists("class", $context) ? $context["class"] : (function () { throw new RuntimeError('Variable "class" does not exist.', 18, $this->source); })()), ((array_key_exists("absolute", $context)) ? (_twig_default_filter((isset($context["absolute"]) || array_key_exists("absolute", $context) ? $context["absolute"] : (function () { throw new RuntimeError('Variable "absolute" does not exist.', 18, $this->source); })()), false)) : (false)));
            // line 19
            if ((twig_get_attribute($this->env, $this->source, (isset($context["class"]) || array_key_exists("class", $context) ? $context["class"] : (function () { throw new RuntimeError('Variable "class" does not exist.', 19, $this->source); })()), "isProjectClass", [], "method", false, false, false, 19) || twig_get_attribute($this->env, $this->source, (isset($context["class"]) || array_key_exists("class", $context) ? $context["class"] : (function () { throw new RuntimeError('Variable "class" does not exist.', 19, $this->source); })()), "isPhpClass", [], "method", false, false, false, 19))) {
                echo "</a>";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 22
    public function macro_method_link($__method__ = null, $__absolute__ = null, $__classonly__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "method" => $__method__,
            "absolute" => $__absolute__,
            "classonly" => $__classonly__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 23
            echo "<a href=\"";
            echo $this->extensions['Doctum\Renderer\TwigExtension']->pathForMethod($context, (isset($context["method"]) || array_key_exists("method", $context) ? $context["method"] : (function () { throw new RuntimeError('Variable "method" does not exist.', 23, $this->source); })()));
            echo "\">
";
            // line 24
            echo $this->env->getFunction('abbr_class')->getCallable()(twig_get_attribute($this->env, $this->source, (isset($context["method"]) || array_key_exists("method", $context) ? $context["method"] : (function () { throw new RuntimeError('Variable "method" does not exist.', 24, $this->source); })()), "class", [], "any", false, false, false, 24));
            if ( !((array_key_exists("classonly", $context)) ? (_twig_default_filter((isset($context["classonly"]) || array_key_exists("classonly", $context) ? $context["classonly"] : (function () { throw new RuntimeError('Variable "classonly" does not exist.', 24, $this->source); })()), false)) : (false))) {
                echo "::";
                echo twig_get_attribute($this->env, $this->source, (isset($context["method"]) || array_key_exists("method", $context) ? $context["method"] : (function () { throw new RuntimeError('Variable "method" does not exist.', 24, $this->source); })()), "name", [], "any", false, false, false, 24);
            }
            // line 25
            echo "</a>";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 28
    public function macro_property_link($__property__ = null, $__absolute__ = null, $__classonly__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "property" => $__property__,
            "absolute" => $__absolute__,
            "classonly" => $__classonly__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 29
            echo "<a href=\"";
            echo $this->extensions['Doctum\Renderer\TwigExtension']->pathForProperty($context, (isset($context["property"]) || array_key_exists("property", $context) ? $context["property"] : (function () { throw new RuntimeError('Variable "property" does not exist.', 29, $this->source); })()));
            echo "\">
";
            // line 30
            echo $this->env->getFunction('abbr_class')->getCallable()(twig_get_attribute($this->env, $this->source, (isset($context["property"]) || array_key_exists("property", $context) ? $context["property"] : (function () { throw new RuntimeError('Variable "property" does not exist.', 30, $this->source); })()), "class", [], "any", false, false, false, 30));
            if ( !((array_key_exists("classonly", $context)) ? (_twig_default_filter((isset($context["classonly"]) || array_key_exists("classonly", $context) ? $context["classonly"] : (function () { throw new RuntimeError('Variable "classonly" does not exist.', 30, $this->source); })()), false)) : (false))) {
                echo "#";
                echo twig_get_attribute($this->env, $this->source, (isset($context["property"]) || array_key_exists("property", $context) ? $context["property"] : (function () { throw new RuntimeError('Variable "property" does not exist.', 30, $this->source); })()), "name", [], "any", false, false, false, 30);
            }
            // line 31
            echo "</a>";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 34
    public function macro_hint_link($__hints__ = null, $__isIntersectionType__ = false, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "hints" => $__hints__,
            "isIntersectionType" => $__isIntersectionType__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 35
            $macros["__internal_parse_1"] = $this;
            // line 37
            if ((isset($context["hints"]) || array_key_exists("hints", $context) ? $context["hints"] : (function () { throw new RuntimeError('Variable "hints" does not exist.', 37, $this->source); })())) {
                // line 38
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["hints"]) || array_key_exists("hints", $context) ? $context["hints"] : (function () { throw new RuntimeError('Variable "hints" does not exist.', 38, $this->source); })()));
                $context['loop'] = [
                  'parent' => $context['_parent'],
                  'index0' => 0,
                  'index'  => 1,
                  'first'  => true,
                ];
                if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                    $length = count($context['_seq']);
                    $context['loop']['revindex0'] = $length - 1;
                    $context['loop']['revindex'] = $length;
                    $context['loop']['length'] = $length;
                    $context['loop']['last'] = 1 === $length;
                }
                foreach ($context['_seq'] as $context["_key"] => $context["hint"]) {
                    // line 39
                    if (twig_get_attribute($this->env, $this->source, $context["hint"], "class", [], "any", false, false, false, 39)) {
                        // line 40
                        echo twig_call_macro($macros["__internal_parse_1"], "macro_class_link", [twig_get_attribute($this->env, $this->source, $context["hint"], "name", [], "any", false, false, false, 40)], 40, $context, $this->getSourceContext());
                    } elseif (twig_get_attribute($this->env, $this->source,                     // line 41
$context["hint"], "name", [], "any", false, false, false, 41)) {
                        // line 42
                        echo $this->env->getFunction('abbr_class')->getCallable()(twig_get_attribute($this->env, $this->source, $context["hint"], "name", [], "any", false, false, false, 42));
                    }
                    // line 44
                    if (twig_get_attribute($this->env, $this->source, $context["hint"], "array", [], "any", false, false, false, 44)) {
                        echo "[]";
                    }
                    // line 45
                    if ( !twig_get_attribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 45)) {
                        if ((isset($context["isIntersectionType"]) || array_key_exists("isIntersectionType", $context) ? $context["isIntersectionType"] : (function () { throw new RuntimeError('Variable "isIntersectionType" does not exist.', 45, $this->source); })())) {
                            echo "&";
                        } else {
                            echo "|";
                        }
                    }
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                    if (isset($context['loop']['length'])) {
                        --$context['loop']['revindex0'];
                        --$context['loop']['revindex'];
                        $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['hint'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 50
    public function macro_source_link($__project__ = null, $__class__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "project" => $__project__,
            "class" => $__class__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 51
            if (twig_get_attribute($this->env, $this->source, (isset($context["class"]) || array_key_exists("class", $context) ? $context["class"] : (function () { throw new RuntimeError('Variable "class" does not exist.', 51, $this->source); })()), "sourcepath", [], "any", false, false, false, 51)) {
                // line 52
                echo "        (<a href=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["class"]) || array_key_exists("class", $context) ? $context["class"] : (function () { throw new RuntimeError('Variable "class" does not exist.', 52, $this->source); })()), "sourcepath", [], "any", false, false, false, 52), "html", null, true);
                echo "\">";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("View source");
                echo "</a>)";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 56
    public function macro_method_source_link($__method__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "method" => $__method__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 57
            if (twig_get_attribute($this->env, $this->source, (isset($context["method"]) || array_key_exists("method", $context) ? $context["method"] : (function () { throw new RuntimeError('Variable "method" does not exist.', 57, $this->source); })()), "sourcepath", [], "any", false, false, false, 57)) {
                // line 59
                echo "<a href=\"";
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["method"]) || array_key_exists("method", $context) ? $context["method"] : (function () { throw new RuntimeError('Variable "method" does not exist.', 59, $this->source); })()), "sourcepath", [], "any", false, false, false, 59), "html", null, true);
                echo "\">";
                echo twig_sprintf(\Wdes\phpI18nL10n\Launcher::gettext("at line %s"), twig_get_attribute($this->env, $this->source,                 // line 60
(isset($context["method"]) || array_key_exists("method", $context) ? $context["method"] : (function () { throw new RuntimeError('Variable "method" does not exist.', 60, $this->source); })()), "line", [], "any", false, false, false, 60));
                // line 61
                echo "</a>";
            } else {
                // line 64
                echo twig_sprintf(\Wdes\phpI18nL10n\Launcher::gettext("at line %s"), twig_get_attribute($this->env, $this->source,                 // line 65
(isset($context["method"]) || array_key_exists("method", $context) ? $context["method"] : (function () { throw new RuntimeError('Variable "method" does not exist.', 65, $this->source); })()), "line", [], "any", false, false, false, 65));
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 70
    public function macro_method_parameters_signature($__method__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "method" => $__method__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 71
            $macros["__internal_parse_2"] = $this->loadTemplate("macros.twig", "macros.twig", 71)->unwrap();
            // line 72
            echo "(";
            // line 73
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["method"]) || array_key_exists("method", $context) ? $context["method"] : (function () { throw new RuntimeError('Variable "method" does not exist.', 73, $this->source); })()), "parameters", [], "any", false, false, false, 73));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["parameter"]) {
                // line 74
                if (twig_get_attribute($this->env, $this->source, $context["parameter"], "hashint", [], "any", false, false, false, 74)) {
                    echo twig_call_macro($macros["__internal_parse_2"], "macro_hint_link", [twig_get_attribute($this->env, $this->source, $context["parameter"], "hint", [], "any", false, false, false, 74), twig_get_attribute($this->env, $this->source, $context["parameter"], "isIntersectionType", [], "method", false, false, false, 74)], 74, $context, $this->getSourceContext());
                    echo " ";
                }
                // line 75
                if (twig_get_attribute($this->env, $this->source, $context["parameter"], "variadic", [], "any", false, false, false, 75)) {
                    echo "...";
                }
                echo "\$";
                echo twig_get_attribute($this->env, $this->source, $context["parameter"], "name", [], "any", false, false, false, 75);
                // line 76
                if ( !(null === twig_get_attribute($this->env, $this->source, $context["parameter"], "default", [], "any", false, false, false, 76))) {
                    echo " = ";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["parameter"], "default", [], "any", false, false, false, 76), "html", null, true);
                }
                // line 77
                if ( !twig_get_attribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 77)) {
                    echo ", ";
                }
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parameter'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 79
            echo ")";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 82
    public function macro_function_parameters_signature($__method__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "method" => $__method__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 83
            $macros["__internal_parse_3"] = $this->loadTemplate("macros.twig", "macros.twig", 83)->unwrap();
            // line 84
            echo "(";
            // line 85
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["method"]) || array_key_exists("method", $context) ? $context["method"] : (function () { throw new RuntimeError('Variable "method" does not exist.', 85, $this->source); })()), "parameters", [], "any", false, false, false, 85));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["parameter"]) {
                // line 86
                if (twig_get_attribute($this->env, $this->source, $context["parameter"], "hashint", [], "any", false, false, false, 86)) {
                    echo twig_call_macro($macros["__internal_parse_3"], "macro_hint_link", [twig_get_attribute($this->env, $this->source, $context["parameter"], "hint", [], "any", false, false, false, 86), twig_get_attribute($this->env, $this->source, $context["parameter"], "isIntersectionType", [], "method", false, false, false, 86)], 86, $context, $this->getSourceContext());
                    echo " ";
                }
                // line 87
                if (twig_get_attribute($this->env, $this->source, $context["parameter"], "variadic", [], "any", false, false, false, 87)) {
                    echo "...";
                }
                echo "\$";
                echo twig_get_attribute($this->env, $this->source, $context["parameter"], "name", [], "any", false, false, false, 87);
                // line 88
                if ( !(null === twig_get_attribute($this->env, $this->source, $context["parameter"], "default", [], "any", false, false, false, 88))) {
                    echo " = ";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["parameter"], "default", [], "any", false, false, false, 88), "html", null, true);
                }
                // line 89
                if ( !twig_get_attribute($this->env, $this->source, $context["loop"], "last", [], "any", false, false, false, 89)) {
                    echo ", ";
                }
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parameter'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 91
            echo ")";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 94
    public function macro_render_classes($__classes__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "classes" => $__classes__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 95
            $macros["__internal_parse_4"] = $this;
            // line 96
            echo "
    <div class=\"container-fluid underlined\">
        ";
            // line 98
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["classes"]) || array_key_exists("classes", $context) ? $context["classes"] : (function () { throw new RuntimeError('Variable "classes" does not exist.', 98, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 99
                echo "            <div class=\"row\">
                <div class=\"col-md-6\">
                    ";
                // line 101
                if (twig_get_attribute($this->env, $this->source, $context["class"], "isInterface", [], "any", false, false, false, 101)) {
                    // line 102
                    echo "                        <em>";
                    echo twig_call_macro($macros["__internal_parse_4"], "macro_class_link", [$context["class"], true], 102, $context, $this->getSourceContext());
                    echo "</em>
                    ";
                } else {
                    // line 104
                    echo twig_call_macro($macros["__internal_parse_4"], "macro_class_link", [$context["class"], true], 104, $context, $this->getSourceContext());
                }
                // line 106
                echo twig_call_macro($macros["__internal_parse_4"], "macro_deprecated", [$context["class"]], 106, $context, $this->getSourceContext());
                // line 107
                echo "</div>
                <div class=\"col-md-6\">";
                // line 109
                echo $this->extensions['Doctum\Renderer\TwigExtension']->markdownToHtml($this->extensions['Doctum\Renderer\TwigExtension']->parseDesc(twig_get_attribute($this->env, $this->source, $context["class"], "shortdesc", [], "any", false, false, false, 109), $context["class"]));
                // line 110
                echo "</div>
            </div>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 113
            echo "    </div>";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 116
    public function macro_breadcrumbs($__namespace__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "namespace" => $__namespace__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 117
            echo "    ";
            $context["current_ns"] = "";
            // line 118
            echo "    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_split_filter($this->env, (isset($context["namespace"]) || array_key_exists("namespace", $context) ? $context["namespace"] : (function () { throw new RuntimeError('Variable "namespace" does not exist.', 118, $this->source); })()), "\\"));
            foreach ($context['_seq'] as $context["_key"] => $context["ns"]) {
                // line 119
                if ((isset($context["current_ns"]) || array_key_exists("current_ns", $context) ? $context["current_ns"] : (function () { throw new RuntimeError('Variable "current_ns" does not exist.', 119, $this->source); })())) {
                    // line 120
                    $context["current_ns"] = (((isset($context["current_ns"]) || array_key_exists("current_ns", $context) ? $context["current_ns"] : (function () { throw new RuntimeError('Variable "current_ns" does not exist.', 120, $this->source); })()) . "\\") . $context["ns"]);
                } else {
                    // line 122
                    $context["current_ns"] = $context["ns"];
                }
                // line 124
                echo "<li><a href=\"";
                echo $this->extensions['Doctum\Renderer\TwigExtension']->pathForNamespace($context, (isset($context["current_ns"]) || array_key_exists("current_ns", $context) ? $context["current_ns"] : (function () { throw new RuntimeError('Variable "current_ns" does not exist.', 124, $this->source); })()));
                echo "\">";
                echo $context["ns"];
                echo "</a></li><li class=\"backslash\">\\</li>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['ns'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 128
    public function macro_deprecated($__reflection__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "reflection" => $__reflection__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 129
            echo "    ";
            if (twig_get_attribute($this->env, $this->source, (isset($context["reflection"]) || array_key_exists("reflection", $context) ? $context["reflection"] : (function () { throw new RuntimeError('Variable "reflection" does not exist.', 129, $this->source); })()), "deprecated", [], "any", false, false, false, 129)) {
                echo "<small><span class=\"label label-danger\">";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("deprecated");
                echo "</span></small>";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 132
    public function macro_deprecations($__reflection__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "reflection" => $__reflection__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 133
            echo "    ";
            $macros["__internal_parse_5"] = $this;
            // line 134
            echo "
    ";
            // line 135
            if (twig_get_attribute($this->env, $this->source, (isset($context["reflection"]) || array_key_exists("reflection", $context) ? $context["reflection"] : (function () { throw new RuntimeError('Variable "reflection" does not exist.', 135, $this->source); })()), "deprecated", [], "any", false, false, false, 135)) {
                // line 136
                echo "        <p>
            ";
                // line 137
                echo twig_call_macro($macros["__internal_parse_5"], "macro_deprecated", [(isset($context["reflection"]) || array_key_exists("reflection", $context) ? $context["reflection"] : (function () { throw new RuntimeError('Variable "reflection" does not exist.', 137, $this->source); })())], 137, $context, $this->getSourceContext());
                echo "
            ";
                // line 138
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["reflection"]) || array_key_exists("reflection", $context) ? $context["reflection"] : (function () { throw new RuntimeError('Variable "reflection" does not exist.', 138, $this->source); })()), "deprecated", [], "any", false, false, false, 138));
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 139
                    echo "                <tr>
                    <td>";
                    // line 140
                    echo twig_get_attribute($this->env, $this->source, $context["tag"], 0, [], "array", false, false, false, 140);
                    echo "</td>
                    <td>";
                    // line 141
                    echo twig_join_filter(twig_slice($this->env, $context["tag"], 1, null), " ");
                    echo "</td>
                </tr>
            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 144
                echo "        </p>
    ";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 148
    public function macro_internals($__reflection__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "reflection" => $__reflection__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 149
            echo "    ";
            if (twig_get_attribute($this->env, $this->source, (isset($context["reflection"]) || array_key_exists("reflection", $context) ? $context["reflection"] : (function () { throw new RuntimeError('Variable "reflection" does not exist.', 149, $this->source); })()), "isInternal", [], "method", false, false, false, 149)) {
                // line 150
                echo "        ";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["reflection"]) || array_key_exists("reflection", $context) ? $context["reflection"] : (function () { throw new RuntimeError('Variable "reflection" does not exist.', 150, $this->source); })()), "getInternal", [], "method", false, false, false, 150));
                foreach ($context['_seq'] as $context["_key"] => $context["internalTag"]) {
                    // line 151
                    echo "        <table>
            <tr>
                <td><span class=\"label label-warning\">";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("internal");
                    // line 153
                    echo "</span></td>
                <td>&nbsp;";
                    // line 154
                    echo twig_get_attribute($this->env, $this->source, $context["internalTag"], 0, [], "array", false, false, false, 154);
                    echo " ";
                    echo twig_join_filter(twig_slice($this->env, $context["internalTag"], 1, null), " ");
                    echo "</td>
            </tr>
        </table>
        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['internalTag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 158
                echo "        &nbsp;
    ";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 162
    public function macro_categories($__reflection__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "reflection" => $__reflection__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 163
            echo "    ";
            if (twig_get_attribute($this->env, $this->source, (isset($context["reflection"]) || array_key_exists("reflection", $context) ? $context["reflection"] : (function () { throw new RuntimeError('Variable "reflection" does not exist.', 163, $this->source); })()), "hasCategories", [], "method", false, false, false, 163)) {
                // line 164
                echo "        <p>
            ";
                // line 165
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["reflection"]) || array_key_exists("reflection", $context) ? $context["reflection"] : (function () { throw new RuntimeError('Variable "reflection" does not exist.', 165, $this->source); })()), "getCategories", [], "method", false, false, false, 165));
                foreach ($context['_seq'] as $context["_key"] => $context["categoryTag"]) {
                    // line 166
                    echo "                ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($context["categoryTag"]);
                    foreach ($context['_seq'] as $context["_key"] => $context["category"]) {
                        // line 167
                        echo "                    <span class=\"label label-default\">";
                        echo twig_escape_filter($this->env, $context["category"], "html", null, true);
                        echo "</span>
                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['category'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 169
                    echo "            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['categoryTag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 170
                echo "        </p>
    ";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 174
    public function macro_todo($__reflection__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "reflection" => $__reflection__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 175
            echo "        ";
            if ((twig_get_attribute($this->env, $this->source, (isset($context["project"]) || array_key_exists("project", $context) ? $context["project"] : (function () { throw new RuntimeError('Variable "project" does not exist.', 175, $this->source); })()), "config", ["insert_todos"], "method", false, false, false, 175) == true)) {
                // line 176
                echo "            ";
                if (twig_get_attribute($this->env, $this->source, (isset($context["reflection"]) || array_key_exists("reflection", $context) ? $context["reflection"] : (function () { throw new RuntimeError('Variable "reflection" does not exist.', 176, $this->source); })()), "todo", [], "any", false, false, false, 176)) {
                    echo "<small><span class=\"label label-info\">";
echo \Wdes\phpI18nL10n\Launcher::getPlugin()->gettext("todo");
                    echo "</span></small>";
                }
                // line 177
                echo "        ";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 180
    public function macro_todos($__reflection__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "reflection" => $__reflection__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 181
            echo "        ";
            $macros["__internal_parse_6"] = $this;
            // line 182
            echo "
        ";
            // line 183
            if (twig_get_attribute($this->env, $this->source, (isset($context["reflection"]) || array_key_exists("reflection", $context) ? $context["reflection"] : (function () { throw new RuntimeError('Variable "reflection" does not exist.', 183, $this->source); })()), "todo", [], "any", false, false, false, 183)) {
                // line 184
                echo "            <p>
                ";
                // line 185
                echo twig_call_macro($macros["__internal_parse_6"], "macro_todo", [(isset($context["reflection"]) || array_key_exists("reflection", $context) ? $context["reflection"] : (function () { throw new RuntimeError('Variable "reflection" does not exist.', 185, $this->source); })())], 185, $context, $this->getSourceContext());
                echo "
                ";
                // line 186
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["reflection"]) || array_key_exists("reflection", $context) ? $context["reflection"] : (function () { throw new RuntimeError('Variable "reflection" does not exist.', 186, $this->source); })()), "todo", [], "any", false, false, false, 186));
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 187
                    echo "                    <tr>
                        <td>";
                    // line 188
                    echo twig_get_attribute($this->env, $this->source, $context["tag"], 0, [], "array", false, false, false, 188);
                    echo "</td>
                        <td>";
                    // line 189
                    echo twig_join_filter(twig_slice($this->env, $context["tag"], 1, null), " ");
                    echo "</td>
                        </tr>
                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 192
                echo "            </p>
        ";
            }

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName()
    {
        return "macros.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable()
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo()
    {
        return array (  933 => 192,  924 => 189,  920 => 188,  917 => 187,  913 => 186,  909 => 185,  906 => 184,  904 => 183,  901 => 182,  898 => 181,  885 => 180,  875 => 177,  868 => 176,  865 => 175,  852 => 174,  841 => 170,  835 => 169,  826 => 167,  821 => 166,  817 => 165,  814 => 164,  811 => 163,  798 => 162,  787 => 158,  775 => 154,  772 => 153,  767 => 151,  762 => 150,  759 => 149,  746 => 148,  735 => 144,  726 => 141,  722 => 140,  719 => 139,  715 => 138,  711 => 137,  708 => 136,  706 => 135,  703 => 134,  700 => 133,  687 => 132,  673 => 129,  660 => 128,  643 => 124,  640 => 122,  637 => 120,  635 => 119,  630 => 118,  627 => 117,  614 => 116,  605 => 113,  597 => 110,  595 => 109,  592 => 107,  590 => 106,  587 => 104,  581 => 102,  579 => 101,  575 => 99,  571 => 98,  567 => 96,  565 => 95,  552 => 94,  543 => 91,  527 => 89,  522 => 88,  516 => 87,  511 => 86,  494 => 85,  492 => 84,  490 => 83,  477 => 82,  468 => 79,  452 => 77,  447 => 76,  441 => 75,  436 => 74,  419 => 73,  417 => 72,  415 => 71,  402 => 70,  392 => 65,  391 => 64,  388 => 61,  386 => 60,  382 => 59,  380 => 57,  367 => 56,  353 => 52,  351 => 51,  337 => 50,  309 => 45,  305 => 44,  302 => 42,  300 => 41,  298 => 40,  296 => 39,  279 => 38,  277 => 37,  275 => 35,  261 => 34,  252 => 31,  246 => 30,  241 => 29,  226 => 28,  217 => 25,  211 => 24,  206 => 23,  191 => 22,  180 => 19,  178 => 18,  173 => 16,  171 => 15,  167 => 14,  165 => 13,  151 => 12,  138 => 9,  125 => 8,  114 => 5,  110 => 4,  106 => 3,  93 => 2,  88 => 179,  85 => 173,  82 => 161,  79 => 147,  76 => 131,  73 => 127,  70 => 115,  67 => 93,  64 => 81,  61 => 69,  58 => 55,  55 => 49,  52 => 33,  49 => 27,  46 => 21,  43 => 11,  40 => 7,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("
{% macro class_category_name(categoryId) -%}
{% if categoryId == 1 %}{% trans 'class' %}{% endif %}
{% if categoryId == 2 %}{% trans 'interface' %}{% endif %}
{% if categoryId == 3 %}{% trans 'trait' %}{% endif %}
{%- endmacro %}

{% macro namespace_link(namespace) -%}
    <a href=\"{{ namespace_path(namespace) }}\">{{ namespace == '' ? global_namespace_name() : namespace|raw }}</a>
{%- endmacro %}

{% macro class_link(class, absolute) -%}
    {%- if class.isProjectClass() -%}
        <a href=\"{{ class_path(class) }}\">
    {%- elseif class.isPhpClass() -%}
        <a target=\"_blank\" rel=\"noopener\" href=\"https://www.php.net/{{ class|raw }}\">
    {%- endif %}
    {{- abbr_class(class, absolute|default(false)) }}
    {%- if class.isProjectClass() or class.isPhpClass() %}</a>{% endif %}
{%- endmacro %}

{% macro method_link(method, absolute, classonly) -%}
{#  #}<a href=\"{{ method_path(method) }}\">
{#    #}{{- abbr_class(method.class) }}{% if not classonly|default(false) %}::{{ method.name|raw }}{% endif -%}
{#  #}</a>
{%- endmacro %}

{% macro property_link(property, absolute, classonly) -%}
{#  #}<a href=\"{{ property_path(property) }}\">
{#    #}{{- abbr_class(property.class) }}{% if not classonly|default(false) %}#{{ property.name|raw }}{% endif -%}
{#  #}</a>
{%- endmacro %}

{% macro hint_link(hints, isIntersectionType = false) -%}
    {%- from _self import class_link %}

    {%- if hints %}
        {%- for hint in hints %}
            {%- if hint.class %}
                {{- class_link(hint.name) }}
            {%- elseif hint.name %}
                {{- abbr_class(hint.name) }}
            {%- endif %}
            {%- if hint.array %}[]{% endif %}
            {%- if not loop.last %}{%- if isIntersectionType %}&{% else %}|{% endif %}{% endif %}
        {%- endfor %}
    {%- endif %}
{%- endmacro %}

{% macro source_link(project, class) -%}
    {% if class.sourcepath %}
        (<a href=\"{{ class.sourcepath }}\">{% trans 'View source'%}</a>)
    {%- endif %}
{%- endmacro %}

{% macro method_source_link(method) -%}
    {% if method.sourcepath %}
        {#- l10n: Method at line %s -#}
        <a href=\"{{ method.sourcepath }}\">{{'at line %s'|trans|format(
            method.line
        )|raw }}</a>
    {%- else %}
        {#- l10n: Method at line %s -#}
        {{- 'at line %s'|trans|format(
            method.line
        )|raw -}}
    {%- endif %}
{%- endmacro %}

{% macro method_parameters_signature(method) -%}
    {%- from \"macros.twig\" import hint_link -%}
    (
        {%- for parameter in method.parameters %}
            {%- if parameter.hashint %}{{ hint_link(parameter.hint, parameter.isIntersectionType()) }} {% endif -%}
            {%- if parameter.variadic %}...{% endif %}\${{ parameter.name|raw }}
            {%- if parameter.default is not null %} = {{ parameter.default }}{% endif %}
            {%- if not loop.last %}, {% endif %}
        {%- endfor -%}
    )
{%- endmacro %}

{% macro function_parameters_signature(method) -%}
    {%- from \"macros.twig\" import hint_link -%}
    (
        {%- for parameter in method.parameters %}
            {%- if parameter.hashint %}{{ hint_link(parameter.hint, parameter.isIntersectionType()) }} {% endif -%}
            {%- if parameter.variadic %}...{% endif %}\${{ parameter.name|raw }}
            {%- if parameter.default is not null %} = {{ parameter.default }}{% endif %}
            {%- if not loop.last %}, {% endif %}
        {%- endfor -%}
    )
{%- endmacro %}

{% macro render_classes(classes) -%}
    {% from _self import class_link, deprecated %}

    <div class=\"container-fluid underlined\">
        {% for class in classes %}
            <div class=\"row\">
                <div class=\"col-md-6\">
                    {% if class.isInterface %}
                        <em>{{- class_link(class, true) -}}</em>
                    {% else %}
                        {{- class_link(class, true) -}}
                    {% endif %}
                    {{- deprecated(class) -}}
                </div>
                <div class=\"col-md-6\">
                    {{- class.shortdesc|desc(class)|md_to_html -}}
                </div>
            </div>
        {% endfor %}
    </div>
{%- endmacro %}

{% macro breadcrumbs(namespace) %}
    {% set current_ns = '' %}
    {% for ns in namespace|split('\\\\') %}
        {%- if current_ns -%}
            {% set current_ns = current_ns ~ '\\\\' ~ ns %}
        {%- else -%}
            {% set current_ns = ns %}
        {%- endif -%}
        <li><a href=\"{{ namespace_path(current_ns) }}\">{{ ns|raw }}</a></li><li class=\"backslash\">\\</li>
    {%- endfor %}
{% endmacro %}

{% macro deprecated(reflection) %}
    {% if reflection.deprecated %}<small><span class=\"label label-danger\">{% trans 'deprecated' %}</span></small>{% endif %}
{% endmacro %}

{% macro deprecations(reflection) %}
    {% from _self import deprecated %}

    {% if reflection.deprecated %}
        <p>
            {{ deprecated(reflection )}}
            {% for tag in reflection.deprecated %}
                <tr>
                    <td>{{ tag[0]|raw }}</td>
                    <td>{{ tag[1:]|join(' ')|raw }}</td>
                </tr>
            {% endfor %}
        </p>
    {% endif %}
{% endmacro %}

{% macro internals(reflection) %}
    {% if reflection.isInternal() %}
        {% for internalTag in reflection.getInternal() %}
        <table>
            <tr>
                <td><span class=\"label label-warning\">{% trans 'internal' %}</span></td>
                <td>&nbsp;{{ internalTag[0]|raw }} {{ internalTag[1:]|join(' ')|raw }}</td>
            </tr>
        </table>
        {% endfor %}
        &nbsp;
    {% endif %}
{% endmacro %}

{% macro categories(reflection) %}
    {% if reflection.hasCategories() %}
        <p>
            {% for categoryTag in reflection.getCategories() %}
                {% for category in categoryTag %}
                    <span class=\"label label-default\">{{ category }}</span>
                {% endfor %}
            {% endfor %}
        </p>
    {% endif %}
{% endmacro %}

{% macro todo(reflection) %}
        {% if project.config('insert_todos') == true %}
            {% if reflection.todo %}<small><span class=\"label label-info\">{% trans 'todo' %}</span></small>{% endif %}
        {% endif %}
{% endmacro %}

{% macro todos(reflection) %}
        {% from _self import todo %}

        {% if reflection.todo %}
            <p>
                {{ todo(reflection )}}
                {% for tag in reflection.todo %}
                    <tr>
                        <td>{{ tag[0]|raw }}</td>
                        <td>{{ tag[1:]|join(' ')|raw }}</td>
                        </tr>
                {% endfor %}
            </p>
        {% endif %}
{% endmacro %}
", "macros.twig", "C:\\xampp\\htdocs\\opencart-master\\tools\\doctum\\src\\Resources\\themes\\default\\macros.twig");
    }
}
