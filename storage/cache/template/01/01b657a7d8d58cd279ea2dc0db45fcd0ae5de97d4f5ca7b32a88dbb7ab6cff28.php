<?php

/* common/security.twig */
class __TwigTemplate_cc2a73dac36b36c0759db7abbb923da08ef209197e98e8c5ebb410d1f811495e extends Twig_Template
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
        echo "<div id=\"modal-security\" class=\"modal\">
  <div class=\"modal-dialog\">
    <div class=\"modal-content\">
      <div class=\"modal-header\">
        <h5 class=\"modal-title text-danger\"><i class=\"fas fa-exclamation-triangle\"></i>";
        // line 5
        echo ($context["heading_title"] ?? null);
        echo "</h5>
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
      </div>
      <div class=\"modal-body\">
        <div class=\"alert alert-info\"><i class=\"fas fa-exclamation-circle\"></i>";
        // line 9
        echo ($context["text_security"] ?? null);
        echo "</div>
        <form>
          <fieldset>
            <legend>";
        // line 12
        echo ($context["text_choose"] ?? null);
        echo "</legend>
            <div class=\"form-group\">
              <select name=\"type\" id=\"input-type\" class=\"form-control\">
                <option value=\"automatic\">";
        // line 15
        echo ($context["text_automatic"] ?? null);
        echo "</option>
                <option value=\"manual\">";
        // line 16
        echo ($context["text_manual"] ?? null);
        echo "</option>
              </select>
            </div>
          </fieldset>
          <fieldset id=\"collapse-automatic\" class=\"collapse\">
            <legend>";
        // line 21
        echo ($context["text_automatic"] ?? null);
        echo "</legend>
            <div class=\"form-group\">
              <div class=\"input-group\">
                <div class=\"input-group-prepend dropdown\">
                  <button type=\"button\" id=\"button-path\" data-toggle=\"dropdown\" class=\"btn btn-primary dropdown-toggle\">";
        // line 25
        echo ($context["document_root"] ?? null);
        echo " <span class=\"fas fa-caret-down\"></span></button>
                  <div class=\"dropdown-menu\">";
        // line 27
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["paths"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["path"]) {
            // line 28
            if ((twig_length_filter($this->env, $context["path"]) > twig_length_filter($this->env, ($context["document_root"] ?? null)))) {
                // line 29
                echo "                        <a href=\"";
                echo $context["path"];
                echo "\" class=\"dropdown-item\"><i class=\"fas fa-exclamation-triangle fa-fw text-danger\"></i>";
                echo $context["path"];
                echo "</a>";
            } else {
                // line 31
                echo "                        <a href=\"";
                echo $context["path"];
                echo "\" class=\"dropdown-item\"><i class=\"fas fa-check-circle fa-fw text-success\"></i>";
                echo $context["path"];
                echo "</a>";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['path'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 34
        echo "                  </div>
                </div>
                <input type=\"text\" name=\"directory\" value=\"storage\" placeholder=\"";
        // line 36
        echo ($context["entry_directory"] ?? null);
        echo "\" class=\"form-control\"/>
                <div class=\"input-group-append\">
                  <button type=\"button\" id=\"button-move\" data-loading-text=\"";
        // line 38
        echo ($context["text_loading"] ?? null);
        echo "\" class=\"btn btn-danger\"><i class=\"fas fa-exclamation-triangle\"></i>";
        echo ($context["button_move"] ?? null);
        echo "</button>
                </div>
              </div>
              <input type=\"hidden\" name=\"path\" value=\"";
        // line 41
        echo ($context["document_root"] ?? null);
        echo "\"/>
            </div>
          </fieldset>
          <fieldset id=\"collapse-manual\" class=\"collapse\">
            <legend>";
        // line 45
        echo ($context["text_manual"] ?? null);
        echo "</legend>
            <div class=\"card bg-light\">
              <div class=\"card-body\" style=\"height: 300px; overflow-y: scroll;\"></div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
<script type=\"text/javascript\"><!--
\$(document).ready(function() {
\t\$('#modal-security').modal('show');
});

\$('#modal-security select[name=\\'type\\']').on('change', function() {
\t\$('#modal-security fieldset.collapse').removeClass('show');

\t\$('#modal-security #collapse-' + \$(this).val()).addClass('show');
});

\$('#modal-security select[name=\\'type\\']').trigger('change');

\$('#modal-security .dropdown-menu a').on('click', function(e) {
\te.preventDefault();

\t\$('#modal-security #button-path').html(\$(this).html() + ' <span class=\"fas fa-caret-down\"></span>');

\t\$('#modal-security input[name=\\'path\\']').val(\$(this).attr('href'));
});


\$('#modal-security .dropdown-menu a').on('click', function(e) {
\te.preventDefault();

\t\$('#button-path').html(\$(this).text() + ' <span class=\"fas fa-caret-down\"></span>');

\t\$('input[name=\\'path\\']').val(\$(this).attr('href'));

\t\$('input[name=\\'path\\']').trigger('change');
});

\$('#button-move').on('click', function() {
\tvar element = this;

\t\$.ajax({
\t\turl: 'index.php?route=common/security/move&user_token=";
        // line 91
        echo ($context["user_token"] ?? null);
        echo "',
\t\ttype: 'post',
\t\tdata: \$('input[name=\\'path\\'], input[name=\\'directory\\']'),
\t\tdataType: 'json',
\t\tcrossDomain: true,
\t\tbeforeSend: function() {
\t\t\t\$(element).button('loading');
\t\t},
\t\tcomplete: function() {
\t\t\t\$(element).button('reset');
\t\t},
\t\tsuccess: function(json) {
\t\t\t\$('.alert-dismissible').remove();

\t\t\tif (json['error']) {
\t\t\t\t\$('#modal-security .modal-body').prepend('<div class=\"alert alert-danger alert-dismissible\"><i class=\"fas fa-exclamation-circle\"></i> ' + json['error'] + ' <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></div>');
\t\t\t}

\t\t\tif (json['success']) {
\t\t\t\t\$('#modal-security .modal-body').prepend('<div class=\"alert alert-success alert-dismissible\"><i class=\"fas fa-check-circle\"></i> ' + json['success'] + ' <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></div>');
\t\t\t}
\t\t},
\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t}
\t});
});

\$('#modal-security select[name=\\'type\\']').on('change', function() {
\thtml = '<ol>';
\thtml += '  <li><p>";
        // line 121
        echo ($context["text_move"] ?? null);
        echo "</p>';
\thtml += '    <p><strong>";
        // line 122
        echo ($context["storage"] ?? null);
        echo "</strong></p>';
\thtml += '    <p>";
        // line 123
        echo ($context["text_to"] ?? null);
        echo "</p>';
\thtml += '    <p><strong>' + \$('#modal-security input[name=\\'path\\']').val() + \$('#modal-security input[name=\\'directory\\']').val() + '/</strong></p></li>';
\thtml += '   <li><p>";
        // line 125
        echo ($context["text_config"] ?? null);
        echo "</p>';
\thtml += '     <p><strong>define(\\'DIR_STORAGE\\', DIR_SYSTEM . \\'storage/\\');</strong></p>';
\thtml += '     <p>";
        // line 127
        echo ($context["text_by"] ?? null);
        echo "</p>';
\thtml += '     <p><strong>define(\\'DIR_STORAGE\\', \\'' + \$('#modal-security input[name=\\'path\\']').val() + \$('#modal-security input[name=\\'directory\\']').val() + '/\\');</strong></p></li>';
\thtml += '   <li><p>";
        // line 129
        echo ($context["text_admin"] ?? null);
        echo "</p>';
\thtml += '     <p><strong>define(\\'DIR_STORAGE\\', DIR_SYSTEM . \\'storage/\\');</strong></p>';
\thtml += '     <p>";
        // line 131
        echo ($context["text_by"] ?? null);
        echo "</p>';
\thtml += '     <p><strong>define(\\'DIR_STORAGE\\', \\'' + \$('#modal-security input[name=\\'path\\']').val() + \$('#modal-security input[name=\\'directory\\']').val() + '/\\');</strong></p></li>';
\thtml += '  </li>';
\thtml += '</ol>';

\t\$('#collapse-manual .card-body').html(html);
});

\$('input[name=\\'path\\']').trigger('change');
//--></script>";
    }

    public function getTemplateName()
    {
        return "common/security.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  229 => 131,  224 => 129,  219 => 127,  214 => 125,  209 => 123,  205 => 122,  201 => 121,  168 => 91,  119 => 45,  112 => 41,  104 => 38,  99 => 36,  95 => 34,  84 => 31,  77 => 29,  75 => 28,  71 => 27,  67 => 25,  60 => 21,  52 => 16,  48 => 15,  42 => 12,  36 => 9,  29 => 5,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "common/security.twig", "");
    }
}
