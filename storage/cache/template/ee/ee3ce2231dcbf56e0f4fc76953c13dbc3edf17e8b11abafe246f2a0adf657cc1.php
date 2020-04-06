<?php

/* install/step_2.twig */
class __TwigTemplate_5f86c671c211fa6966aee4696c57ed51541657689ce9ef4d2b6cbb5f9c8b022c extends Twig_Template
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
<div class=\"container\">
  <header>
    <div class=\"row\">
      <div class=\"col-sm-6\">
        <h1 class=\"pull-left\">2
          <small>/4</small>
        </h1>
        <h3>";
        // line 9
        echo ($context["heading_title"] ?? null);
        echo "
          <br>
          <small>";
        // line 11
        echo ($context["text_step_2"] ?? null);
        echo "</small>
        </h3>
      </div>
      <div class=\"col-sm-6\">
        <div id=\"logo\" class=\"pull-right hidden-xs\"><img src=\"view/image/logo.png\" alt=\"OpenCart\" title=\"OpenCart\"/></div>
      </div>
    </div>
  </header>";
        // line 19
        if (($context["error_warning"] ?? null)) {
            // line 20
            echo "    <div class=\"alert alert-danger alert-dismissible\"><i class=\"fa fa-exclamation-circle\"></i>";
            echo ($context["error_warning"] ?? null);
            echo "
      <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
    </div>";
        }
        // line 24
        echo "  <div class=\"row\">
    <div class=\"col-sm-9\">
      <form action=\"";
        // line 26
        echo ($context["action"] ?? null);
        echo "\" method=\"post\" enctype=\"multipart/form-data\">
        <p>";
        // line 27
        echo ($context["text_install_php"] ?? null);
        echo "</p>
        <fieldset>
          <table class=\"table\">
            <thead>
              <tr>
                <td width=\"35%\"><b>";
        // line 32
        echo ($context["text_setting"] ?? null);
        echo "</b></td>
                <td width=\"25%\"><b>";
        // line 33
        echo ($context["text_current"] ?? null);
        echo "</b></td>
                <td width=\"25%\"><b>";
        // line 34
        echo ($context["text_required"] ?? null);
        echo "</b></td>
                <td width=\"15%\" class=\"text-center\"><b>";
        // line 35
        echo ($context["text_status"] ?? null);
        echo "</b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>";
        // line 40
        echo ($context["text_version"] ?? null);
        echo "</td>
                <td>";
        // line 41
        echo ($context["php_version"] ?? null);
        echo "</td>
                <td>7.0+</td>
                <td class=\"text-center\">";
        // line 44
        if (($context["version"] ?? null)) {
            // line 45
            echo "                    <span class=\"text-success\"><i class=\"fa fa-check-circle\"></i></span>";
        } else {
            // line 47
            echo "                    <span class=\"text-danger\"><i class=\"fa fa-minus-circle\"></i></span>";
        }
        // line 48
        echo "</td>
              </tr>
              <tr>
                <td>";
        // line 51
        echo ($context["text_global"] ?? null);
        echo "</td>
                <td>";
        // line 52
        if (($context["register_globals"] ?? null)) {
            // line 53
            echo ($context["text_on"] ?? null);
        } else {
            // line 55
            echo ($context["text_off"] ?? null);
        }
        // line 56
        echo "</td>
                <td>";
        // line 57
        echo ($context["text_off"] ?? null);
        echo "</td>
                <td class=\"text-center\">";
        // line 59
        if ( !($context["register_globals"] ?? null)) {
            // line 60
            echo "                    <span class=\"text-success\"><i class=\"fa fa-check-circle\"></i></span>";
        } else {
            // line 62
            echo "                    <span class=\"text-danger\"><i class=\"fa fa-minus-circle\"></i></span>";
        }
        // line 63
        echo "</td>
              </tr>
              <tr>
                <td>";
        // line 66
        echo ($context["text_magic"] ?? null);
        echo "</td>
                <td>";
        // line 67
        if (($context["magic_quotes_gpc"] ?? null)) {
            // line 68
            echo ($context["text_on"] ?? null);
        } else {
            // line 70
            echo ($context["text_off"] ?? null);
        }
        // line 71
        echo "</td>
                <td>";
        // line 72
        echo ($context["text_off"] ?? null);
        echo "</td>
                <td class=\"text-center\">";
        // line 74
        if ( !($context["error_magic_quotes_gpc"] ?? null)) {
            // line 75
            echo "                    <span class=\"text-success\"><i class=\"fa fa-check-circle\"></i></span>";
        } else {
            // line 77
            echo "                    <span class=\"text-danger\"><i class=\"fa fa-minus-circle\"></i></span>";
        }
        // line 78
        echo "</td>
              </tr>
              <tr>
                <td>";
        // line 81
        echo ($context["text_file_upload"] ?? null);
        echo "</td>
                <td>";
        // line 82
        if (($context["file_uploads"] ?? null)) {
            // line 83
            echo ($context["text_on"] ?? null);
        } else {
            // line 85
            echo ($context["text_off"] ?? null);
        }
        // line 86
        echo "</td>
                <td>";
        // line 87
        echo ($context["text_on"] ?? null);
        echo "</td>
                <td class=\"text-center\">";
        // line 88
        if (($context["file_uploads"] ?? null)) {
            // line 89
            echo "                    <span class=\"text-success\"><i class=\"fa fa-check-circle\"></i></span>";
        } else {
            // line 91
            echo "                    <span class=\"text-danger\"><i class=\"fa fa-minus-circle\"></i></span>";
        }
        // line 92
        echo "</td>
              </tr>
              <tr>
                <td>";
        // line 95
        echo ($context["text_session"] ?? null);
        echo "</td>
                <td>";
        // line 96
        if (($context["session_auto_start"] ?? null)) {
            // line 97
            echo ($context["text_on"] ?? null);
        } else {
            // line 99
            echo ($context["text_off"] ?? null);
        }
        // line 100
        echo "</td>
                <td>";
        // line 101
        echo ($context["text_off"] ?? null);
        echo "</td>
                <td class=\"text-center\">";
        // line 102
        if ( !($context["session_auto_start"] ?? null)) {
            // line 103
            echo "                    <span class=\"text-success\"><i class=\"fa fa-check-circle\"></i></span>";
        } else {
            // line 105
            echo "                    <span class=\"text-danger\"><i class=\"fa fa-minus-circle\"></i></span>";
        }
        // line 106
        echo "</td>
              </tr>
            </tbody>
          </table>
        </fieldset>
        <p>";
        // line 111
        echo ($context["text_install_extension"] ?? null);
        echo "</p>
        <fieldset>
          <table class=\"table\">
            <thead>
              <tr>
                <td width=\"35%\"><b>";
        // line 116
        echo ($context["text_extension"] ?? null);
        echo "</b></td>
                <td width=\"25%\"><b>";
        // line 117
        echo ($context["text_current"] ?? null);
        echo "</b></td>
                <td width=\"25%\"><b>";
        // line 118
        echo ($context["text_required"] ?? null);
        echo "</b></td>
                <td width=\"15%\" class=\"text-center\"><b>";
        // line 119
        echo ($context["text_status"] ?? null);
        echo "</b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>";
        // line 124
        echo ($context["text_db"] ?? null);
        echo "</td>
                <td>";
        // line 125
        if (($context["db"] ?? null)) {
            // line 126
            echo ($context["text_on"] ?? null);
        } else {
            // line 128
            echo ($context["text_off"] ?? null);
        }
        // line 129
        echo "</td>
                <td>";
        // line 130
        echo ($context["text_on"] ?? null);
        echo "</td>
                <td class=\"text-center\">";
        // line 131
        if (($context["db"] ?? null)) {
            // line 132
            echo "                    <span class=\"text-success\"><i class=\"fa fa-check-circle\"></i></span>";
        } else {
            // line 134
            echo "                    <span class=\"text-danger\"><i class=\"fa fa-minus-circle\"></i></span>";
        }
        // line 135
        echo "</td>
              </tr>
              <tr>
                <td>";
        // line 138
        echo ($context["text_gd"] ?? null);
        echo "</td>
                <td>";
        // line 139
        if (($context["gd"] ?? null)) {
            // line 140
            echo ($context["text_on"] ?? null);
        } else {
            // line 142
            echo ($context["text_off"] ?? null);
        }
        // line 143
        echo "</td>
                <td>";
        // line 144
        echo ($context["text_on"] ?? null);
        echo "</td>
                <td class=\"text-center\">";
        // line 145
        if (($context["gd"] ?? null)) {
            // line 146
            echo "                    <span class=\"text-success\"><i class=\"fa fa-check-circle\"></i></span>";
        } else {
            // line 148
            echo "                    <span class=\"text-danger\"><i class=\"fa fa-minus-circle\"></i></span>";
        }
        // line 149
        echo "</td>
              </tr>
              <tr>
                <td>";
        // line 152
        echo ($context["text_curl"] ?? null);
        echo "</td>
                <td>";
        // line 153
        if (($context["curl"] ?? null)) {
            // line 154
            echo ($context["text_on"] ?? null);
        } else {
            // line 156
            echo ($context["text_off"] ?? null);
        }
        // line 157
        echo "</td>
                <td>";
        // line 158
        echo ($context["text_on"] ?? null);
        echo "</td>
                <td class=\"text-center\">";
        // line 159
        if (($context["curl"] ?? null)) {
            // line 160
            echo "                    <span class=\"text-success\"><i class=\"fa fa-check-circle\"></i></span>";
        } else {
            // line 162
            echo "                    <span class=\"text-danger\"><i class=\"fa fa-minus-circle\"></i></span>";
        }
        // line 163
        echo "</td>
              </tr>
              <tr>
                <td>";
        // line 166
        echo ($context["text_openssl"] ?? null);
        echo "</td>
                <td>";
        // line 167
        if (($context["openssl"] ?? null)) {
            // line 168
            echo ($context["text_on"] ?? null);
        } else {
            // line 170
            echo ($context["text_off"] ?? null);
        }
        // line 171
        echo "</td>
                <td>";
        // line 172
        echo ($context["text_on"] ?? null);
        echo "</td>
                <td class=\"text-center\">";
        // line 173
        if (($context["openssl"] ?? null)) {
            // line 174
            echo "                    <span class=\"text-success\"><i class=\"fa fa-check-circle\"></i></span>";
        } else {
            // line 176
            echo "                    <span class=\"text-danger\"><i class=\"fa fa-minus-circle\"></i></span>";
        }
        // line 177
        echo "</td>
              </tr>
              <tr>
                <td>";
        // line 180
        echo ($context["text_zlib"] ?? null);
        echo "</td>
                <td>";
        // line 181
        if (($context["zlib"] ?? null)) {
            // line 182
            echo ($context["text_on"] ?? null);
        } else {
            // line 184
            echo ($context["text_off"] ?? null);
        }
        // line 185
        echo "</td>
                <td>";
        // line 186
        echo ($context["text_on"] ?? null);
        echo "</td>
                <td class=\"text-center\">";
        // line 187
        if (($context["zlib"] ?? null)) {
            // line 188
            echo "                    <span class=\"text-success\"><i class=\"fa fa-check-circle\"></i></span>";
        } else {
            // line 190
            echo "                    <span class=\"text-danger\"><i class=\"fa fa-minus-circle\"></i></span>";
        }
        // line 191
        echo "</td>
              </tr>
              <tr>
                <td>";
        // line 194
        echo ($context["text_zip"] ?? null);
        echo "</td>
                <td>";
        // line 195
        if (($context["zip"] ?? null)) {
            // line 196
            echo ($context["text_on"] ?? null);
        } else {
            // line 198
            echo ($context["text_off"] ?? null);
        }
        // line 199
        echo "</td>
                <td>";
        // line 200
        echo ($context["text_on"] ?? null);
        echo "</td>
                <td class=\"text-center\">";
        // line 201
        if (($context["zip"] ?? null)) {
            // line 202
            echo "                    <span class=\"text-success\"><i class=\"fa fa-check-circle\"></i></span>";
        } else {
            // line 204
            echo "                    <span class=\"text-danger\"><i class=\"fa fa-minus-circle\"></i></span>";
        }
        // line 205
        echo "</td>
              </tr>";
        // line 207
        if ( !($context["iconv"] ?? null)) {
            // line 208
            echo "                <tr>
                  <td>";
            // line 209
            echo ($context["text_mbstring"] ?? null);
            echo "</td>
                  <td>";
            // line 210
            if (($context["mbstring"] ?? null)) {
                // line 211
                echo ($context["text_on"] ?? null);
            } else {
                // line 213
                echo ($context["text_off"] ?? null);
            }
            // line 214
            echo "</td>
                  <td>";
            // line 215
            echo ($context["text_on"] ?? null);
            echo "</td>
                  <td class=\"text-center\">";
            // line 216
            if (($context["mbstring"] ?? null)) {
                // line 217
                echo "                      <span class=\"text-success\"><i class=\"fa fa-check-circle\"></i></span>";
            } else {
                // line 219
                echo "                      <span class=\"text-danger\"><i class=\"fa fa-minus-circle\"></i></span>";
            }
            // line 220
            echo "</td>
                </tr>";
        }
        // line 223
        echo "            </tbody>
          </table>
        </fieldset>
        <p>";
        // line 226
        echo ($context["text_install_file"] ?? null);
        echo "</p>
        <fieldset>
          <table class=\"table\">
            <thead>
              <tr>
                <td><b>";
        // line 231
        echo ($context["text_file"] ?? null);
        echo "</b></td>
                <td><b>";
        // line 232
        echo ($context["text_status"] ?? null);
        echo "</b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>";
        // line 237
        echo ($context["catalog_config"] ?? null);
        echo "</td>
                <td>";
        // line 238
        if ( !($context["error_catalog_config"] ?? null)) {
            // line 239
            echo "                    <span class=\"text-success\">";
            echo ($context["text_writable"] ?? null);
            echo "</span>";
        } else {
            // line 241
            echo "                    <span class=\"text-danger\">";
            echo ($context["error_catalog_config"] ?? null);
            echo "</span>";
        }
        // line 242
        echo "</td>
              </tr>
              <tr>
                <td>";
        // line 245
        echo ($context["admin_config"] ?? null);
        echo "</td>
                <td>";
        // line 246
        if ( !($context["error_admin_config"] ?? null)) {
            // line 247
            echo "                    <span class=\"text-success\">";
            echo ($context["text_writable"] ?? null);
            echo "</span>";
        } else {
            // line 249
            echo "                    <span class=\"text-danger\">";
            echo ($context["error_admin_config"] ?? null);
            echo "</span>";
        }
        // line 250
        echo "</td>
              </tr>
            </tbody>
          </table>
        </fieldset>
        <div class=\"buttons\">
          <div class=\"pull-left\"><a href=\"";
        // line 256
        echo ($context["back"] ?? null);
        echo "\" class=\"btn btn-default\">";
        echo ($context["button_back"] ?? null);
        echo "</a></div>
          <div class=\"pull-right\">
            <input type=\"submit\" value=\"";
        // line 258
        echo ($context["button_continue"] ?? null);
        echo "\" class=\"btn btn-primary\"/>
          </div>
        </div>
      </form>
    </div>
    <div class=\"col-sm-3\">";
        // line 263
        echo ($context["column_left"] ?? null);
        echo "</div>
  </div>
</div>";
        // line 266
        echo ($context["footer"] ?? null);
    }

    public function getTemplateName()
    {
        return "install/step_2.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  590 => 266,  585 => 263,  577 => 258,  570 => 256,  562 => 250,  557 => 249,  552 => 247,  550 => 246,  546 => 245,  541 => 242,  536 => 241,  531 => 239,  529 => 238,  525 => 237,  517 => 232,  513 => 231,  505 => 226,  500 => 223,  496 => 220,  493 => 219,  490 => 217,  488 => 216,  484 => 215,  481 => 214,  478 => 213,  475 => 211,  473 => 210,  469 => 209,  466 => 208,  464 => 207,  461 => 205,  458 => 204,  455 => 202,  453 => 201,  449 => 200,  446 => 199,  443 => 198,  440 => 196,  438 => 195,  434 => 194,  429 => 191,  426 => 190,  423 => 188,  421 => 187,  417 => 186,  414 => 185,  411 => 184,  408 => 182,  406 => 181,  402 => 180,  397 => 177,  394 => 176,  391 => 174,  389 => 173,  385 => 172,  382 => 171,  379 => 170,  376 => 168,  374 => 167,  370 => 166,  365 => 163,  362 => 162,  359 => 160,  357 => 159,  353 => 158,  350 => 157,  347 => 156,  344 => 154,  342 => 153,  338 => 152,  333 => 149,  330 => 148,  327 => 146,  325 => 145,  321 => 144,  318 => 143,  315 => 142,  312 => 140,  310 => 139,  306 => 138,  301 => 135,  298 => 134,  295 => 132,  293 => 131,  289 => 130,  286 => 129,  283 => 128,  280 => 126,  278 => 125,  274 => 124,  266 => 119,  262 => 118,  258 => 117,  254 => 116,  246 => 111,  239 => 106,  236 => 105,  233 => 103,  231 => 102,  227 => 101,  224 => 100,  221 => 99,  218 => 97,  216 => 96,  212 => 95,  207 => 92,  204 => 91,  201 => 89,  199 => 88,  195 => 87,  192 => 86,  189 => 85,  186 => 83,  184 => 82,  180 => 81,  175 => 78,  172 => 77,  169 => 75,  167 => 74,  163 => 72,  160 => 71,  157 => 70,  154 => 68,  152 => 67,  148 => 66,  143 => 63,  140 => 62,  137 => 60,  135 => 59,  131 => 57,  128 => 56,  125 => 55,  122 => 53,  120 => 52,  116 => 51,  111 => 48,  108 => 47,  105 => 45,  103 => 44,  98 => 41,  94 => 40,  86 => 35,  82 => 34,  78 => 33,  74 => 32,  66 => 27,  62 => 26,  58 => 24,  51 => 20,  49 => 19,  39 => 11,  34 => 9,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "install/step_2.twig", "");
    }
}
