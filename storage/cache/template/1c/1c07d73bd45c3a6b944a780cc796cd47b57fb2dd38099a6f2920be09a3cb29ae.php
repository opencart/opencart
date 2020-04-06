<?php

/* default/template/product/product.twig */
class __TwigTemplate_db95a27f86fe08e8e6356108ef3ebad56e20d6bdd7322f97d5e0fd0b1839c7ba extends Twig_Template
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
        echo ($context["header"] ?? null);
        echo "
<div id=\"product-product\" class=\"container\">
  <ul class=\"breadcrumb\">";
        // line 4
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["breadcrumbs"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["breadcrumb"]) {
            // line 5
            echo "      <li class=\"breadcrumb-item\"><a href=\"";
            echo twig_get_attribute($this->env, $this->source, $context["breadcrumb"], "href", []);
            echo "\">";
            echo twig_get_attribute($this->env, $this->source, $context["breadcrumb"], "text", []);
            echo "</a></li>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['breadcrumb'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 7
        echo "  </ul>
  <div class=\"row\">";
        // line 8
        echo ($context["column_left"] ?? null);
        echo "
    <div id=\"content\" class=\"col\">";
        // line 9
        echo ($context["content_top"] ?? null);
        echo "
      <div class=\"row\">";
        // line 11
        if ((($context["column_left"] ?? null) || ($context["column_right"] ?? null))) {
            // line 12
            $context["class"] = "col-sm-6";
        } else {
            // line 14
            $context["class"] = "col-sm-8";
        }
        // line 16
        echo "        <div class=\"";
        echo ($context["class"] ?? null);
        echo "\">";
        // line 17
        if ((($context["thumb"] ?? null) || ($context["images"] ?? null))) {
            // line 18
            echo "            <ul class=\"thumbnails\">";
            // line 19
            if (($context["thumb"] ?? null)) {
                // line 20
                echo "                <li class=\"text-center\"><a href=\"";
                echo ($context["popup"] ?? null);
                echo "\" title=\"";
                echo ($context["heading_title"] ?? null);
                echo "\"><img src=\"";
                echo ($context["thumb"] ?? null);
                echo "\" title=\"";
                echo ($context["heading_title"] ?? null);
                echo "\" alt=\"";
                echo ($context["heading_title"] ?? null);
                echo "\" class=\"img-fluid\"/></a></li>";
            }
            // line 22
            if (($context["images"] ?? null)) {
                // line 23
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["images"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["image"]) {
                    // line 24
                    echo "                  <li class=\"image-additional\"><a href=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["image"], "popup", []);
                    echo "\" title=\"";
                    echo ($context["heading_title"] ?? null);
                    echo "\"><img src=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["image"], "thumb", []);
                    echo "\" title=\"";
                    echo ($context["heading_title"] ?? null);
                    echo "\" alt=\"";
                    echo ($context["heading_title"] ?? null);
                    echo "\" class=\"img-fluid\"/></a></li>";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['image'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
            }
            // line 27
            echo "            </ul>";
        }
        // line 29
        echo "          <ul class=\"nav nav-tabs\">
            <li class=\"nav-item\">
              <a id=\"description-tab\" class=\"nav-link active\" data-toggle=\"tab\" href=\"#tab-description\" role=\"tab\" aria-controls=\"tab-description\" aria-selected=\"true\">";
        // line 32
        echo ($context["tab_description"] ?? null);
        echo "</a></li>";
        // line 33
        if (($context["attribute_groups"] ?? null)) {
            // line 34
            echo "              <li class=\"nav-item\">
                <a id=\"specification-tab\" class=\"nav-link\" data-toggle=\"tab\" href=\"#tab-specification\" role=\"tab\" aria-controls=\"tab-specification\" aria-selected=\"true\">";
            // line 36
            echo ($context["tab_attribute"] ?? null);
            echo "</a></li>";
        }
        // line 38
        if (($context["review_status"] ?? null)) {
            // line 39
            echo "              <li class=\"nav-item\">
                <a id=\"review-tab\" class=\"nav-link\" data-toggle=\"tab\" href=\"#tab-review\" role=\"tab\" aria-controls=\"tab-review\" aria-selected=\"true\">";
            // line 41
            echo ($context["tab_review"] ?? null);
            echo "</a></li>";
        }
        // line 43
        echo "          </ul>
          <div class=\"tab-content\">
            <div id=\"tab-description\" class=\"tab-pane fade show active mb-4\" role=\"tabpanel\" aria-labelledby=\"description-tab\">";
        // line 46
        echo ($context["description"] ?? null);
        echo "
            </div>";
        // line 49
        if (($context["attribute_groups"] ?? null)) {
            // line 50
            echo "              <div id=\"tab-specification\" class=\"tab-pane fade\" role=\"tabpanel\" aria-labelledby=\"specification-tab\">
                <div class=\"table-responsive\">
                  <table class=\"table table-bordered\">";
            // line 53
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["attribute_groups"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["attribute_group"]) {
                // line 54
                echo "                      <thead>
                        <tr>
                          <td colspan=\"2\"><strong>";
                // line 56
                echo twig_get_attribute($this->env, $this->source, $context["attribute_group"], "name", []);
                echo "</strong></td>
                        </tr>
                      </thead>
                      <tbody>";
                // line 60
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["attribute_group"], "attribute", []));
                foreach ($context['_seq'] as $context["_key"] => $context["attribute"]) {
                    // line 61
                    echo "                          <tr>
                            <td>";
                    // line 62
                    echo twig_get_attribute($this->env, $this->source, $context["attribute"], "name", []);
                    echo "</td>
                            <td>";
                    // line 63
                    echo twig_get_attribute($this->env, $this->source, $context["attribute"], "text", []);
                    echo "</td>
                          </tr>";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['attribute'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 66
                echo "                      </tbody>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['attribute_group'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 68
            echo "                  </table>
                </div>
              </div>";
        }
        // line 72
        if (($context["review_status"] ?? null)) {
            // line 73
            echo "              <div id=\"tab-review\" class=\"tab-pane fade\" role=\"tabpanel\" aria-labelledby=\"review-tab\">
                <form id=\"form-review\">
                  <div id=\"review\"></div>
                  <h2>";
            // line 76
            echo ($context["text_write"] ?? null);
            echo "</h2>";
            // line 77
            if (($context["review_guest"] ?? null)) {
                // line 78
                echo "                    <div class=\"form-group required\">
                      <label for=\"input-name\" class=\"col-form-label\">";
                // line 79
                echo ($context["entry_name"] ?? null);
                echo "</label> <input type=\"text\" name=\"name\" value=\"";
                echo ($context["customer_name"] ?? null);
                echo "\" id=\"input-name\" class=\"form-control\"/>
                    </div>
                    <div class=\"form-group required\">
                      <label for=\"input-review\" class=\"col-form-label\">";
                // line 82
                echo ($context["entry_review"] ?? null);
                echo "</label> <textarea name=\"text\" rows=\"5\" id=\"input-review\" class=\"form-control\"></textarea>
                      <div class=\"help-block\">";
                // line 83
                echo ($context["text_note"] ?? null);
                echo "</div>
                    </div>
                    <div class=\"form-group row required\">
                      <div class=\"col-sm-12\">
                        <label class=\"col-form-label\">";
                // line 87
                echo ($context["entry_rating"] ?? null);
                echo "</label>
                        &nbsp;&nbsp;&nbsp;";
                // line 88
                echo ($context["entry_bad"] ?? null);
                echo "&nbsp;
                        <input type=\"radio\" name=\"rating\" value=\"1\"/>
                        &nbsp;
                        <input type=\"radio\" name=\"rating\" value=\"2\"/>
                        &nbsp;
                        <input type=\"radio\" name=\"rating\" value=\"3\"/>
                        &nbsp;
                        <input type=\"radio\" name=\"rating\" value=\"4\"/>
                        &nbsp;
                        <input type=\"radio\" name=\"rating\" value=\"5\"/>
                        &nbsp;";
                // line 98
                echo ($context["entry_good"] ?? null);
                echo "</div>
                    </div>";
                // line 100
                echo ($context["captcha"] ?? null);
                echo "
                    <div class=\"d-inline-block pt-2 pd-2 w-100\">
                      <div class=\"float-right\">
                        <button type=\"button\" id=\"button-review\" data-loading-text=\"";
                // line 103
                echo ($context["text_loading"] ?? null);
                echo "\" class=\"btn btn-primary\">";
                echo ($context["button_continue"] ?? null);
                echo "</button>
                      </div>
                    </div>";
            } else {
                // line 107
                echo ($context["text_login"] ?? null);
            }
            // line 109
            echo "                </form>
              </div>";
        }
        // line 111
        echo "</div>
        </div>";
        // line 113
        if ((($context["column_left"] ?? null) || ($context["column_right"] ?? null))) {
            // line 114
            $context["class"] = "col-sm-6";
        } else {
            // line 116
            $context["class"] = "col-sm-4";
        }
        // line 118
        echo "        <div class=\"";
        echo ($context["class"] ?? null);
        echo "\">
          <div class=\"btn-group\">
            <button type=\"button\" data-toggle=\"tooltip\" class=\"btn btn-light\" title=\"";
        // line 120
        echo ($context["button_wishlist"] ?? null);
        echo "\" onclick=\"wishlist.add('";
        echo ($context["product_id"] ?? null);
        echo "');\"><i class=\"fas fa-heart\"></i></button>
            <button type=\"button\" data-toggle=\"tooltip\" class=\"btn btn-light\" title=\"";
        // line 121
        echo ($context["button_compare"] ?? null);
        echo "\" onclick=\"compare.add('";
        echo ($context["product_id"] ?? null);
        echo "');\"><i class=\"fas fa-exchange-alt\"></i></button>
          </div>
          <h1>";
        // line 123
        echo ($context["heading_title"] ?? null);
        echo "</h1>
          <ul class=\"list-unstyled\">";
        // line 125
        if (($context["manufacturer"] ?? null)) {
            // line 126
            echo "              <li>";
            echo ($context["text_manufacturer"] ?? null);
            echo " <a href=\"";
            echo ($context["manufacturers"] ?? null);
            echo "\">";
            echo ($context["manufacturer"] ?? null);
            echo "</a></li>";
        }
        // line 128
        echo "            <li>";
        echo ($context["text_model"] ?? null);
        echo ($context["model"] ?? null);
        echo "</li>";
        // line 129
        if (($context["reward"] ?? null)) {
            // line 130
            echo "              <li>";
            echo ($context["text_reward"] ?? null);
            echo ($context["reward"] ?? null);
            echo "</li>";
        }
        // line 132
        echo "            <li>";
        echo ($context["text_stock"] ?? null);
        echo ($context["stock"] ?? null);
        echo "</li>
          </ul>";
        // line 134
        if (($context["price"] ?? null)) {
            // line 135
            echo "            <ul class=\"list-unstyled\">";
            // line 136
            if ( !($context["special"] ?? null)) {
                // line 137
                echo "                <li>
                  <h2>";
                // line 138
                echo ($context["price"] ?? null);
                echo "</h2>
                </li>";
            } else {
                // line 141
                echo "                <li><span style=\"text-decoration: line-through;\">";
                echo ($context["price"] ?? null);
                echo "</span></li>
                <li>
                  <h2>";
                // line 143
                echo ($context["special"] ?? null);
                echo "</h2>
                </li>";
            }
            // line 146
            if (($context["tax"] ?? null)) {
                // line 147
                echo "                <li>";
                echo ($context["text_tax"] ?? null);
                echo ($context["tax"] ?? null);
                echo "</li>";
            }
            // line 149
            if (($context["points"] ?? null)) {
                // line 150
                echo "                <li>";
                echo ($context["text_points"] ?? null);
                echo ($context["points"] ?? null);
                echo "</li>";
            }
            // line 152
            if (($context["discounts"] ?? null)) {
                // line 153
                echo "                <li>
                  <hr>
                </li>";
                // line 156
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["discounts"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["discount"]) {
                    // line 157
                    echo "                  <li>";
                    echo twig_get_attribute($this->env, $this->source, $context["discount"], "quantity", []);
                    echo ($context["text_discount"] ?? null);
                    echo twig_get_attribute($this->env, $this->source, $context["discount"], "price", []);
                    echo "</li>";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['discount'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
            }
            // line 160
            echo "            </ul>";
        }
        // line 162
        echo "          <div id=\"product\">";
        if (($context["options"] ?? null)) {
            // line 163
            echo "              <hr>
              <h3>";
            // line 164
            echo ($context["text_option"] ?? null);
            echo "</h3>";
            // line 165
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["options"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
                // line 166
                if ((twig_get_attribute($this->env, $this->source, $context["option"], "type", []) == "select")) {
                    // line 167
                    echo "                  <div class=\"form-group";
                    if (twig_get_attribute($this->env, $this->source, $context["option"], "required", [])) {
                        echo " required";
                    }
                    echo "\">
                    <label class=\"col-form-label\" for=\"input-option";
                    // line 168
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\">";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "name", []);
                    echo "</label> <select name=\"option[";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "]\" id=\"input-option";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\" class=\"form-control\">
                      <option value=\"\">";
                    // line 169
                    echo ($context["text_select"] ?? null);
                    echo "</option>";
                    // line 170
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["option"], "product_option_value", []));
                    foreach ($context['_seq'] as $context["_key"] => $context["option_value"]) {
                        // line 171
                        echo "                        <option value=\"";
                        echo twig_get_attribute($this->env, $this->source, $context["option_value"], "product_option_value_id", []);
                        echo "\">";
                        echo twig_get_attribute($this->env, $this->source, $context["option_value"], "name", []);
                        // line 172
                        if (twig_get_attribute($this->env, $this->source, $context["option_value"], "price", [])) {
                            // line 173
                            echo "                            (";
                            echo twig_get_attribute($this->env, $this->source, $context["option_value"], "price_prefix", []);
                            echo twig_get_attribute($this->env, $this->source, $context["option_value"], "price", []);
                            echo ")";
                        }
                        // line 174
                        echo " </option>";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option_value'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 176
                    echo "                    </select>
                  </div>";
                }
                // line 179
                if ((twig_get_attribute($this->env, $this->source, $context["option"], "type", []) == "radio")) {
                    // line 180
                    echo "                  <div class=\"form-group";
                    if (twig_get_attribute($this->env, $this->source, $context["option"], "required", [])) {
                        echo " required";
                    }
                    echo "\">
                    <label class=\"col-form-label\">";
                    // line 181
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "name", []);
                    echo "</label>
                    <div id=\"input-option";
                    // line 182
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\">";
                    // line 183
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["option"], "product_option_value", []));
                    foreach ($context['_seq'] as $context["_key"] => $context["option_value"]) {
                        // line 184
                        echo "                        <div class=\"form-check\">
                          <label><input type=\"radio\" name=\"option[";
                        // line 185
                        echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                        echo "]\" value=\"";
                        echo twig_get_attribute($this->env, $this->source, $context["option_value"], "product_option_value_id", []);
                        echo "\" class=\"form-check-input\"/>";
                        // line 186
                        if (twig_get_attribute($this->env, $this->source, $context["option_value"], "image", [])) {
                            echo "<img src=\"";
                            echo twig_get_attribute($this->env, $this->source, $context["option_value"], "image", []);
                            echo "\" alt=\"";
                            echo twig_get_attribute($this->env, $this->source, $context["option_value"], "name", []);
                            if (twig_get_attribute($this->env, $this->source, $context["option_value"], "price", [])) {
                                echo twig_get_attribute($this->env, $this->source, $context["option_value"], "price_prefix", []);
                                echo twig_get_attribute($this->env, $this->source, $context["option_value"], "price", []);
                            }
                            echo "\" class=\"img-thumbnail\"/>";
                        }
                        // line 187
                        echo twig_get_attribute($this->env, $this->source, $context["option_value"], "name", []);
                        // line 188
                        if (twig_get_attribute($this->env, $this->source, $context["option_value"], "price", [])) {
                            // line 189
                            echo "                              (";
                            echo twig_get_attribute($this->env, $this->source, $context["option_value"], "price_prefix", []);
                            echo twig_get_attribute($this->env, $this->source, $context["option_value"], "price", []);
                            echo ")";
                        }
                        // line 191
                        echo "                          </label>
                        </div>";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option_value'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 194
                    echo "                    </div>
                  </div>";
                }
                // line 197
                if ((twig_get_attribute($this->env, $this->source, $context["option"], "type", []) == "checkbox")) {
                    // line 198
                    echo "                  <div class=\"form-group";
                    if (twig_get_attribute($this->env, $this->source, $context["option"], "required", [])) {
                        echo " required";
                    }
                    echo "\">
                    <label class=\"col-form-label\">";
                    // line 199
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "name", []);
                    echo "</label>
                    <div id=\"input-option";
                    // line 200
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\">";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["option"], "product_option_value", []));
                    foreach ($context['_seq'] as $context["_key"] => $context["option_value"]) {
                        // line 201
                        echo "                        <div class=\"form-check\">
                          <label><input type=\"checkbox\" name=\"option[";
                        // line 202
                        echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                        echo "][]\" value=\"";
                        echo twig_get_attribute($this->env, $this->source, $context["option_value"], "product_option_value_id", []);
                        echo "\" class=\"form-check-input\"/>";
                        // line 203
                        if (twig_get_attribute($this->env, $this->source, $context["option_value"], "image", [])) {
                            echo " <img src=\"";
                            echo twig_get_attribute($this->env, $this->source, $context["option_value"], "image", []);
                            echo "\" alt=\"";
                            echo twig_get_attribute($this->env, $this->source, $context["option_value"], "name", []);
                            if (twig_get_attribute($this->env, $this->source, $context["option_value"], "price", [])) {
                                echo twig_get_attribute($this->env, $this->source, $context["option_value"], "price_prefix", []);
                                echo twig_get_attribute($this->env, $this->source, $context["option_value"], "price", []);
                            }
                            echo "\" class=\"img-thumbnail\"/>";
                        }
                        // line 204
                        echo twig_get_attribute($this->env, $this->source, $context["option_value"], "name", []);
                        // line 205
                        if (twig_get_attribute($this->env, $this->source, $context["option_value"], "price", [])) {
                            // line 206
                            echo "                              (";
                            echo twig_get_attribute($this->env, $this->source, $context["option_value"], "price_prefix", []);
                            echo twig_get_attribute($this->env, $this->source, $context["option_value"], "price", []);
                            echo ")";
                        }
                        // line 207
                        echo "</label>
                        </div>";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option_value'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 210
                    echo "                    </div>
                  </div>";
                }
                // line 213
                if ((twig_get_attribute($this->env, $this->source, $context["option"], "type", []) == "text")) {
                    // line 214
                    echo "                  <div class=\"form-group";
                    if (twig_get_attribute($this->env, $this->source, $context["option"], "required", [])) {
                        echo " required";
                    }
                    echo "\">
                    <label class=\"col-form-label\" for=\"input-option";
                    // line 215
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\">";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "name", []);
                    echo "</label> <input type=\"text\" name=\"option[";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "]\" value=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "value", []);
                    echo "\" placeholder=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "name", []);
                    echo "\" id=\"input-option";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\" class=\"form-control\"/>
                  </div>";
                }
                // line 218
                if ((twig_get_attribute($this->env, $this->source, $context["option"], "type", []) == "textarea")) {
                    // line 219
                    echo "                  <div class=\"form-group";
                    if (twig_get_attribute($this->env, $this->source, $context["option"], "required", [])) {
                        echo " required";
                    }
                    echo "\">
                    <label class=\"col-form-label\" for=\"input-option";
                    // line 220
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\">";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "name", []);
                    echo "</label> <textarea name=\"option[";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "]\" rows=\"5\" placeholder=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "name", []);
                    echo "\" id=\"input-option";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\" class=\"form-control\">";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "value", []);
                    echo "</textarea>
                  </div>";
                }
                // line 223
                if ((twig_get_attribute($this->env, $this->source, $context["option"], "type", []) == "file")) {
                    // line 224
                    echo "                  <div class=\"form-group";
                    if (twig_get_attribute($this->env, $this->source, $context["option"], "required", [])) {
                        echo " required";
                    }
                    echo "\">
                    <label class=\"col-form-label\">";
                    // line 225
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "name", []);
                    echo "</label>
                    <button type=\"button\" id=\"button-upload";
                    // line 226
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\" data-loading-text=\"";
                    echo ($context["text_loading"] ?? null);
                    echo "\" class=\"btn btn-light btn-block\"><i class=\"fas fa-upload\"></i>";
                    echo ($context["button_upload"] ?? null);
                    echo "</button>
                    <input type=\"hidden\" name=\"option[";
                    // line 227
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "]\" value=\"\" id=\"input-option";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\"/>
                  </div>";
                }
                // line 230
                if ((twig_get_attribute($this->env, $this->source, $context["option"], "type", []) == "date")) {
                    // line 231
                    echo "                  <div class=\"form-group";
                    if (twig_get_attribute($this->env, $this->source, $context["option"], "required", [])) {
                        echo " required";
                    }
                    echo "\">
                    <label class=\"col-form-label\" for=\"input-option";
                    // line 232
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\">";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "name", []);
                    echo "</label>
                    <div class=\"input-group date\">
                      <input type=\"text\" name=\"option[";
                    // line 234
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "]\" value=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "value", []);
                    echo "\" id=\"input-option";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\" class=\"form-control\"/>
                      <div class=\"input-group-append\">
                        <span class=\"input-group-text\"><i class=\"fas fa-calendar\"></i></span>
                      </div>
                    </div>
                  </div>";
                }
                // line 241
                if ((twig_get_attribute($this->env, $this->source, $context["option"], "type", []) == "datetime")) {
                    // line 242
                    echo "                  <div class=\"form-group";
                    if (twig_get_attribute($this->env, $this->source, $context["option"], "required", [])) {
                        echo " required";
                    }
                    echo "\">
                    <label class=\"col-form-label\" for=\"input-option";
                    // line 243
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\">";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "name", []);
                    echo "</label>
                    <div class=\"input-group datetime\">
                      <input type=\"text\" name=\"option[";
                    // line 245
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "]\" value=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "value", []);
                    echo "\" id=\"input-option";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\" class=\"form-control\"/>
                      <div class=\"input-group-append\">
                        <span class=\"input-group-text\"><i class=\"fas fa-calendar\"></i></span>
                      </div>
                    </div>
                  </div>";
                }
                // line 252
                if ((twig_get_attribute($this->env, $this->source, $context["option"], "type", []) == "time")) {
                    // line 253
                    echo "                  <div class=\"form-group";
                    if (twig_get_attribute($this->env, $this->source, $context["option"], "required", [])) {
                        echo " required";
                    }
                    echo "\">
                    <label class=\"col-form-label\" for=\"input-option";
                    // line 254
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\">";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "name", []);
                    echo "</label>
                    <div class=\"input-group time\">
                      <input type=\"text\" name=\"option[";
                    // line 256
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "]\" value=\"";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "value", []);
                    echo "\" id=\"input-option";
                    echo twig_get_attribute($this->env, $this->source, $context["option"], "product_option_id", []);
                    echo "\" class=\"form-control\"/>
                      <div class=\"input-group-append\">
                        <span class=\"input-group-text\"><i class=\"fas fa-calendar\"></i></span>
                      </div>
                    </div>
                  </div>";
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        // line 265
        if (($context["recurrings"] ?? null)) {
            // line 266
            echo "              <hr/>
              <h3>";
            // line 267
            echo ($context["text_payment_recurring"] ?? null);
            echo "</h3>
              <div class=\"form-group required\">
                <select name=\"recurring_id\" class=\"form-control\">
                  <option value=\"\">";
            // line 270
            echo ($context["text_select"] ?? null);
            echo "</option>";
            // line 271
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["recurrings"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["recurring"]) {
                // line 272
                echo "                    <option value=\"";
                echo twig_get_attribute($this->env, $this->source, $context["recurring"], "recurring_id", []);
                echo "\">";
                echo twig_get_attribute($this->env, $this->source, $context["recurring"], "name", []);
                echo "</option>";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['recurring'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 274
            echo "                </select>
                <div id=\"recurring-description\" class=\"help-block\"></div>
              </div>";
        }
        // line 278
        echo "            <div class=\"form-group\">
              <label for=\"input-quantity\" class=\"col-form-label\">";
        // line 279
        echo ($context["entry_qty"] ?? null);
        echo "</label> <input type=\"text\" name=\"quantity\" value=\"";
        echo ($context["minimum"] ?? null);
        echo "\" size=\"2\" id=\"input-quantity\" class=\"form-control\"/> <input type=\"hidden\" name=\"product_id\" value=\"";
        echo ($context["product_id"] ?? null);
        echo "\"/>
              <br/>
              <button type=\"button\" id=\"button-cart\" data-loading-text=\"";
        // line 281
        echo ($context["text_loading"] ?? null);
        echo "\" class=\"btn btn-primary btn-lg btn-block\">";
        echo ($context["button_cart"] ?? null);
        echo "</button>
            </div>";
        // line 283
        if ((($context["minimum"] ?? null) > 1)) {
            // line 284
            echo "              <div class=\"alert alert-info\"><i class=\"fas fa-info-circle\"></i>";
            echo ($context["text_minimum"] ?? null);
            echo "</div>";
        }
        // line 285
        echo "</div>";
        // line 286
        if (($context["review_status"] ?? null)) {
            // line 287
            echo "            <div class=\"rating\">
              <p>";
            // line 288
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(1, 5));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 289
                if ((($context["rating"] ?? null) < $context["i"])) {
                    echo "<span class=\"fas fa-stack\"><i class=\"far fa-star fa-stack-1x\"></i></span>";
                } else {
                    echo "<span class=\"fas fa-stack\"><i class=\"fas fa-star fa-stack-1x\"></i><i class=\"far fa-star fa-stack-1x\"></i></span>";
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 291
            echo "                <a href=\"\" onclick=\"\$('a[href=\\'#tab-review\\']').trigger('click'); return false;\">";
            echo ($context["reviews"] ?? null);
            echo "</a> / <a href=\"\" onclick=\"\$('a[href=\\'#tab-review\\']').trigger('click'); return false;\">";
            echo ($context["text_write"] ?? null);
            echo "</a></p>
              <hr/>
              <!-- AddThis Button BEGIN -->
              <div class=\"addthis_toolbox addthis_default_style\" data-url=\"";
            // line 294
            echo ($context["share"] ?? null);
            echo "\"><a class=\"addthis_button_facebook_like\" fb:like:layout=\"button_count\"></a> <a class=\"addthis_button_tweet\"></a> <a class=\"addthis_button_pinterest_pinit\"></a> <a class=\"addthis_counter addthis_pill_style\"></a></div>
              <script type=\"text/javascript\" src=\"//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-515eeaf54693130e\"></script>
              <!-- AddThis Button END -->
            </div>";
        }
        // line 299
        echo "        </div>
      </div>";
        // line 301
        if (($context["products"] ?? null)) {
            // line 302
            echo "        <h3>";
            echo ($context["text_related"] ?? null);
            echo "</h3>
        <div class=\"row\">";
            // line 304
            $context["i"] = 0;
            // line 305
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["products"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
                // line 306
                echo "            <div class=\"col\">
              <div class=\"product-thumb transition\">
                <div class=\"image\"><a href=\"";
                // line 308
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
                // line 310
                echo twig_get_attribute($this->env, $this->source, $context["product"], "href", []);
                echo "\">";
                echo twig_get_attribute($this->env, $this->source, $context["product"], "name", []);
                echo "</a></h4>
                  <p>";
                // line 311
                echo twig_get_attribute($this->env, $this->source, $context["product"], "description", []);
                echo "</p>";
                // line 312
                if (twig_get_attribute($this->env, $this->source, $context["product"], "rating", [])) {
                    // line 313
                    echo "                    <div class=\"rating\">";
                    // line 314
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(range(1, 5));
                    foreach ($context['_seq'] as $context["_key"] => $context["j"]) {
                        // line 315
                        if ((twig_get_attribute($this->env, $this->source, $context["product"], "rating", []) < $context["j"])) {
                            echo " <span class=\"fas fa-stack\"><i class=\"fas fa-star-o fa-stack-1x\"></i></span>";
                        } else {
                            echo " <span class=\"fas fa-stack\"><i class=\"fas fa-star fa-stack-1x\"></i><i class=\"fas fa-star-o fa-stack-1x\"></i></span>";
                        }
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['j'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 317
                    echo "                    </div>";
                }
                // line 319
                if (twig_get_attribute($this->env, $this->source, $context["product"], "price", [])) {
                    // line 320
                    echo "                    <p class=\"price\">";
                    // line 321
                    if ( !twig_get_attribute($this->env, $this->source, $context["product"], "special", [])) {
                        // line 322
                        echo twig_get_attribute($this->env, $this->source, $context["product"], "price", []);
                    } else {
                        // line 324
                        echo "                        <span class=\"price-new\">";
                        echo twig_get_attribute($this->env, $this->source, $context["product"], "special", []);
                        echo "</span> <span class=\"price-old\">";
                        echo twig_get_attribute($this->env, $this->source, $context["product"], "price", []);
                        echo "</span>";
                    }
                    // line 326
                    if (twig_get_attribute($this->env, $this->source, $context["product"], "tax", [])) {
                        // line 327
                        echo "                        <span class=\"price-tax\">";
                        echo ($context["text_tax"] ?? null);
                        echo twig_get_attribute($this->env, $this->source, $context["product"], "tax", []);
                        echo "</span>";
                    }
                    // line 329
                    echo "                    </p>";
                }
                // line 331
                echo "                </div>
                <div class=\"button-group\">
                  <button type=\"button\" onclick=\"cart.add('";
                // line 333
                echo twig_get_attribute($this->env, $this->source, $context["product"], "product_id", []);
                echo "', '";
                echo twig_get_attribute($this->env, $this->source, $context["product"], "minimum", []);
                echo "');\"><i class=\"fas fa-shopping-cart\"></i> <span class=\"d-none d-lg-inline\">";
                echo ($context["button_cart"] ?? null);
                echo "</span></button>
                  <button type=\"button\" data-toggle=\"tooltip\" title=\"";
                // line 334
                echo ($context["button_wishlist"] ?? null);
                echo "\" onclick=\"wishlist.add('";
                echo twig_get_attribute($this->env, $this->source, $context["product"], "product_id", []);
                echo "');\"><i class=\"fas fa-heart\"></i></button>
                  <button type=\"button\" data-toggle=\"tooltip\" title=\"";
                // line 335
                echo ($context["button_compare"] ?? null);
                echo "\" onclick=\"compare.add('";
                echo twig_get_attribute($this->env, $this->source, $context["product"], "product_id", []);
                echo "');\"><i class=\"fas fa-exchange-alt\"></i></button>
                </div>
              </div>
            </div>";
                // line 339
                if (((($context["column_left"] ?? null) && ($context["column_right"] ?? null)) && (((($context["i"] ?? null) + 1) % 2) == 0))) {
                    // line 340
                    echo "              <div class=\"clearfix visible-md visible-sm\"></div>";
                } elseif ((                // line 341
($context["column_left"] ?? null) || (($context["column_right"] ?? null) && (((($context["i"] ?? null) + 1) % 3) == 0)))) {
                    // line 342
                    echo "              <div class=\"clearfix visible-md\"></div>";
                } elseif ((((                // line 343
($context["i"] ?? null) + 1) % 4) == 0)) {
                    // line 344
                    echo "              <div class=\"clearfix visible-md\"></div>";
                }
                // line 346
                $context["i"] = (($context["i"] ?? null) + 1);
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['product'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 348
            echo "        </div>";
        }
        // line 350
        if (($context["tags"] ?? null)) {
            // line 351
            echo "        <p>";
            echo ($context["text_tags"] ?? null);
            // line 352
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(0, twig_length_filter($this->env, ($context["tags"] ?? null))));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 353
                if (($context["i"] < (twig_length_filter($this->env, ($context["tags"] ?? null)) - 1))) {
                    // line 354
                    echo "              <a href=\"";
                    echo twig_get_attribute($this->env, $this->source, (($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 = ($context["tags"] ?? null)) && is_array($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5) || $__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5 instanceof ArrayAccess ? ($__internal_7cd7461123377b8c9c1b6a01f46c7bbd94bd12e59266005df5e93029ddbc0ec5[$context["i"]] ?? null) : null), "href", []);
                    echo "\">";
                    echo twig_get_attribute($this->env, $this->source, (($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a = ($context["tags"] ?? null)) && is_array($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a) || $__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a instanceof ArrayAccess ? ($__internal_3e28b7f596c58d7729642bcf2acc6efc894803703bf5fa7e74cd8d2aa1f8c68a[$context["i"]] ?? null) : null), "tag", []);
                    echo "</a>,";
                } else {
                    // line 356
                    echo "              <a href=\"";
                    echo twig_get_attribute($this->env, $this->source, (($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 = ($context["tags"] ?? null)) && is_array($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57) || $__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57 instanceof ArrayAccess ? ($__internal_b0b3d6199cdf4d15a08b3fb98fe017ecb01164300193d18d78027218d843fc57[$context["i"]] ?? null) : null), "href", []);
                    echo "\">";
                    echo twig_get_attribute($this->env, $this->source, (($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 = ($context["tags"] ?? null)) && is_array($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9) || $__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9 instanceof ArrayAccess ? ($__internal_81ccf322d0988ca0aa9ae9943d772c435c5ff01fb50b956278e245e40ae66ab9[$context["i"]] ?? null) : null), "tag", []);
                    echo "</a>";
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 359
            echo "        </p>";
        }
        // line 361
        echo ($context["content_bottom"] ?? null);
        echo "
    </div>";
        // line 363
        echo ($context["column_right"] ?? null);
        echo "
  </div>
</div>
<script type=\"text/javascript\"><!--
\$('select[name=\\'recurring_id\\'], input[name=\"quantity\"]').change(function() {
\t\$.ajax({
\t\turl: 'index.php?route=product/product/getDescription',
\t\ttype: 'post',
\t\tdata: \$('input[name=\\'product_id\\'], input[name=\\'quantity\\'], select[name=\\'recurring_id\\']'),
\t\tdataType: 'json',
\t\tbeforeSend: function() {
\t\t\t\$('#recurring-description').html('');
\t\t},
\t\tsuccess: function(json) {
\t\t\t\$('.alert-dismissible, .text-danger').remove();

\t\t\tif (json['success']) {
\t\t\t\t\$('#recurring-description').html(json['success']);
\t\t\t}
\t\t}
\t});
});
//--></script>
<script type=\"text/javascript\"><!--
\$('#button-cart').on('click', function() {
\t\$.ajax({
\t\turl: 'index.php?route=checkout/cart/add',
\t\ttype: 'post',
\t\tdata: \$('#product input[type=\\'text\\'], #product input[type=\\'hidden\\'], #product input[type=\\'radio\\']:checked, #product input[type=\\'checkbox\\']:checked, #product select, #product textarea'),
\t\tdataType: 'json',
\t\tbeforeSend: function() {
\t\t\t\$('#button-cart').button('loading');
\t\t},
\t\tcomplete: function() {
\t\t\t\$('#button-cart').button('reset');
\t\t},
\t\tsuccess: function(json) {
\t\t\t\$('.alert-dismissible, .text-danger').remove();
\t\t\t\$('.form-control').removeClass('is-invalid');

\t\t\tif (json['error']) {
\t\t\t\tif (json['error']['option']) {
\t\t\t\t\tfor (i in json['error']['option']) {
\t\t\t\t\t\tvar element = \$('#input-option' + i.replace('_', '-'));

\t\t\t\t\t\tif (element.parent().hasClass('input-group')) {
\t\t\t\t\t\t\telement.parent().after('<div class=\"text-danger\">' + json['error']['option'][i] + '</div>');
\t\t\t\t\t\t} else {
\t\t\t\t\t\t\telement.after('<div class=\"text-danger\">' + json['error']['option'][i] + '</div>');
\t\t\t\t\t\t}
\t\t\t\t\t}
\t\t\t\t}

\t\t\t\tif (json['error']['recurring']) {
\t\t\t\t\t\$('select[name=\\'recurring_id\\']').after('<div class=\"text-danger\">' + json['error']['recurring'] + '</div>');
\t\t\t\t}

\t\t\t\tif (json['error']['quantity']) {
\t\t\t\t\t\$('input[name=\\'quantity\\']').after('<div class=\"text-danger\">' + json['error']['quantity'] + '</div>');
\t\t\t\t}

\t\t\t\t// Highlight any found errors
\t\t\t\t\$('.text-danger').each(function() {
\t\t\t\t\tvar element = \$(this).parent().find(':input');

\t\t\t\t\tif (element.hasClass('form-control')) {
\t\t\t\t\t\telement.addClass('is-invalid');
\t\t\t\t\t}
\t\t\t\t});

\t\t\t\t\$('.invalid-tooltip').show();
\t\t\t}

\t\t\tif (json['success']) {
\t\t\t\thtml = '<div id=\"toast\" class=\"toast\">';
\t\t\t\thtml += '  <div class=\"toast-header\">';
\t\t\t\thtml += '\t <strong class=\"mr-auto\"><i class=\"fas fa-shopping-cart\"></i> Shopping Cart</strong>';
\t\t\t\thtml += '    <button type=\"button\" class=\"ml-2 mb-1 close\" data-dismiss=\"toast\">&times;</button>';
\t\t\t\thtml += '  </div>';
\t\t\t\thtml += '  <div class=\"toast-body\">' + json['success'] + '</div>';
\t\t\t\thtml += '</div>';

\t\t\t\t\$('body').append(html);

\t\t\t\t\$('#toast').toast({'delay': 3000});
\t\t\t\t\$('#toast').toast('show');

\t\t\t\t\$('#cart').parent().load('index.php?route=common/cart/info');
\t\t\t}
\t\t},
\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t}
\t});
});
//--></script>
<script type=\"text/javascript\"><!--
\$('.date').datetimepicker({
\t'format': 'YYYY-MM-DD',
\t'locale': '";
        // line 462
        echo ($context["datepicker"] ?? null);
        echo "',
\t'allowInputToggle': true
});

\$('.time').datetimepicker({
\t'format': 'HH:mm',
\t'locale': '";
        // line 468
        echo ($context["datepicker"] ?? null);
        echo "',
\t'allowInputToggle': true
});

\$('.datetime').datetimepicker({
\t'format': 'YYYY-MM-DD HH:mm',
\t'locale': '";
        // line 474
        echo ($context["datepicker"] ?? null);
        echo "',
\t'allowInputToggle': true
});
//--></script>
<script type=\"text/javascript\"><!--
\$('button[id^=\\'button-upload\\']').on('click', function() {
\tvar element = this;

\t\$('#form-upload').remove();

\t\$('body').prepend('<form enctype=\"multipart/form-data\" id=\"form-upload\" style=\"display: none;\"><input type=\"file\" name=\"file\" /></form>');

\t\$('#form-upload input[name=\\'file\\']').trigger('click');

\tif (typeof timer != 'undefined') {
\t\tclearInterval(timer);
\t}

\ttimer = setInterval(function() {
\t\tif (\$('#form-upload input[name=\\'file\\']').val() != '') {
\t\t\tclearInterval(timer);

\t\t\t\$.ajax({
\t\t\t\turl: 'index.php?route=tool/upload',
\t\t\t\ttype: 'post',
\t\t\t\tdataType: 'json',
\t\t\t\tdata: new FormData(\$('#form-upload')[0]),
\t\t\t\tcache: false,
\t\t\t\tcontentType: false,
\t\t\t\tprocessData: false,
\t\t\t\tbeforeSend: function() {
\t\t\t\t\t\$(element).button('loading');
\t\t\t\t},
\t\t\t\tcomplete: function() {
\t\t\t\t\t\$(element).button('reset');
\t\t\t\t},
\t\t\t\tsuccess: function(json) {
\t\t\t\t\t\$('.text-danger').remove();

\t\t\t\t\tif (json['error']) {
\t\t\t\t\t\t\$(element).parent().find('input').after('<div class=\"text-danger\">' + json['error'] + '</div>');
\t\t\t\t\t}

\t\t\t\t\tif (json['success']) {
\t\t\t\t\t\talert(json['success']);

\t\t\t\t\t\t\$(element).parent().find('input').val(json['code']);
\t\t\t\t\t}
\t\t\t\t},
\t\t\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t\t\t}
\t\t\t});
\t\t}
\t}, 500);
});
//--></script>
<script type=\"text/javascript\"><!--
\$('#review').delegate('.pagination a', 'click', function(e) {
\te.preventDefault();

\t\$('#review').fadeOut('slow');

\t\$('#review').load(this.href);

\t\$('#review').fadeIn('slow');
});

\$('#review').load('index.php?route=product/product/review&product_id=";
        // line 542
        echo ($context["product_id"] ?? null);
        echo "');

\$('#button-review').on('click', function() {
\t\$.ajax({
\t\turl: 'index.php?route=product/product/write&product_id=";
        // line 546
        echo ($context["product_id"] ?? null);
        echo "',
\t\ttype: 'post',
\t\tdataType: 'json',
\t\tdata: \$('#form-review').serialize(),
\t\tbeforeSend: function() {
\t\t\t\$('#button-review').button('loading');
\t\t},
\t\tcomplete: function() {
\t\t\t\$('#button-review').button('reset');
\t\t},
\t\tsuccess: function(json) {
\t\t\t\$('.alert-dismissible').remove();

\t\t\tif (json['error']) {
\t\t\t\t\$('#review').after('<div class=\"alert alert-danger alert-dismissible\"><i class=\"fas fa-exclamation-circle\"></i> ' + json['error'] + '</div>');
\t\t\t}

\t\t\tif (json['success']) {
\t\t\t\t\$('#review').after('<div class=\"alert alert-success alert-dismissible\"><i class=\"fas fa-check-circle\"></i> ' + json['success'] + '</div>');

\t\t\t\t\$('input[name=\\'name\\']').val('');
\t\t\t\t\$('textarea[name=\\'text\\']').val('');
\t\t\t\t\$('input[name=\\'rating\\']:checked').prop('checked', false);
\t\t\t}
\t\t}
\t});
});

\$(document).ready(function() {
\t\$('.thumbnails').magnificPopup({
\t\ttype: 'image',
\t\tdelegate: 'a',
\t\tgallery: {
\t\t\tenabled: true
\t\t}
\t});
});
//--></script>";
        // line 584
        echo ($context["footer"] ?? null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "default/template/product/product.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1254 => 584,  1214 => 546,  1207 => 542,  1136 => 474,  1127 => 468,  1118 => 462,  1016 => 363,  1012 => 361,  1009 => 359,  998 => 356,  991 => 354,  989 => 353,  985 => 352,  982 => 351,  980 => 350,  977 => 348,  971 => 346,  968 => 344,  966 => 343,  964 => 342,  962 => 341,  960 => 340,  958 => 339,  950 => 335,  944 => 334,  936 => 333,  932 => 331,  929 => 329,  923 => 327,  921 => 326,  914 => 324,  911 => 322,  909 => 321,  907 => 320,  905 => 319,  902 => 317,  892 => 315,  888 => 314,  886 => 313,  884 => 312,  881 => 311,  875 => 310,  864 => 308,  860 => 306,  856 => 305,  854 => 304,  849 => 302,  847 => 301,  844 => 299,  837 => 294,  828 => 291,  818 => 289,  814 => 288,  811 => 287,  809 => 286,  807 => 285,  802 => 284,  800 => 283,  794 => 281,  785 => 279,  782 => 278,  777 => 274,  767 => 272,  763 => 271,  760 => 270,  754 => 267,  751 => 266,  749 => 265,  731 => 256,  724 => 254,  717 => 253,  715 => 252,  702 => 245,  695 => 243,  688 => 242,  686 => 241,  673 => 234,  666 => 232,  659 => 231,  657 => 230,  650 => 227,  642 => 226,  638 => 225,  631 => 224,  629 => 223,  614 => 220,  607 => 219,  605 => 218,  590 => 215,  583 => 214,  581 => 213,  577 => 210,  570 => 207,  564 => 206,  562 => 205,  560 => 204,  548 => 203,  543 => 202,  540 => 201,  534 => 200,  530 => 199,  523 => 198,  521 => 197,  517 => 194,  510 => 191,  504 => 189,  502 => 188,  500 => 187,  488 => 186,  483 => 185,  480 => 184,  476 => 183,  473 => 182,  469 => 181,  462 => 180,  460 => 179,  456 => 176,  450 => 174,  444 => 173,  442 => 172,  437 => 171,  433 => 170,  430 => 169,  420 => 168,  413 => 167,  411 => 166,  407 => 165,  404 => 164,  401 => 163,  398 => 162,  395 => 160,  384 => 157,  380 => 156,  376 => 153,  374 => 152,  368 => 150,  366 => 149,  360 => 147,  358 => 146,  353 => 143,  347 => 141,  342 => 138,  339 => 137,  337 => 136,  335 => 135,  333 => 134,  327 => 132,  321 => 130,  319 => 129,  314 => 128,  305 => 126,  303 => 125,  299 => 123,  292 => 121,  286 => 120,  280 => 118,  277 => 116,  274 => 114,  272 => 113,  269 => 111,  265 => 109,  262 => 107,  254 => 103,  248 => 100,  244 => 98,  231 => 88,  227 => 87,  220 => 83,  216 => 82,  208 => 79,  205 => 78,  203 => 77,  200 => 76,  195 => 73,  193 => 72,  188 => 68,  182 => 66,  174 => 63,  170 => 62,  167 => 61,  163 => 60,  157 => 56,  153 => 54,  149 => 53,  145 => 50,  143 => 49,  139 => 46,  135 => 43,  131 => 41,  128 => 39,  126 => 38,  122 => 36,  119 => 34,  117 => 33,  114 => 32,  110 => 29,  107 => 27,  90 => 24,  86 => 23,  84 => 22,  71 => 20,  69 => 19,  67 => 18,  65 => 17,  61 => 16,  58 => 14,  55 => 12,  53 => 11,  49 => 9,  45 => 8,  42 => 7,  32 => 5,  28 => 4,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "default/template/product/product.twig", "");
    }
}
