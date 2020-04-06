<?php

/* design/theme.twig */
class __TwigTemplate_b514b09068d7ab3f41de6a5389f461ef3c52fd4875522cf9fadd0690051d230a extends Twig_Template
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
        echo ($context["column_left"] ?? null);
        echo "
<div id=\"content\">
  <div class=\"page-header\">
    <div class=\"container-fluid\">
      <h1>";
        // line 5
        echo ($context["heading_title"] ?? null);
        echo "</h1>
      <ol class=\"breadcrumb\">";
        // line 7
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["breadcrumbs"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["breadcrumb"]) {
            // line 8
            echo "          <li class=\"breadcrumb-item\"><a href=\"";
            echo twig_get_attribute($this->env, $this->source, $context["breadcrumb"], "href", []);
            echo "\">";
            echo twig_get_attribute($this->env, $this->source, $context["breadcrumb"], "text", []);
            echo "</a></li>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['breadcrumb'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 10
        echo "      </ol>
    </div>
  </div>
  <div class=\"container-fluid\">
    <div class=\"card\">
      <div class=\"card-header\"><i class=\"fas fa-list\"></i>";
        // line 15
        echo ($context["text_edit"] ?? null);
        echo "</div>
      <div class=\"card-body\">
        <div class=\"row\">
          <div class=\"col-lg-3 col-md-3 col-sm-12\">
            <div class=\"list-group mb-3\">
              <div class=\"list-group-item\">
                <h4 class=\"list-group-item-heading\">";
        // line 21
        echo ($context["text_store"] ?? null);
        echo "</h4>
              </div>
              <div class=\"list-group-item\">
                <select name=\"store_id\" class=\"form-control\">
                  <option value=\"0\">";
        // line 25
        echo ($context["text_default"] ?? null);
        echo "</option>";
        // line 26
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["stores"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["store"]) {
            // line 27
            echo "                    <option value=\"";
            echo twig_get_attribute($this->env, $this->source, $context["store"], "store_id", []);
            echo "\">";
            echo twig_get_attribute($this->env, $this->source, $context["store"], "name", []);
            echo "</option>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['store'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 29
        echo "                </select>
              </div>
            </div>
            <div class=\"list-group\">
              <div class=\"list-group-item\">
                <h4 class=\"list-group-item-heading\">";
        // line 34
        echo ($context["text_template"] ?? null);
        echo "</h4>
              </div>
              <div id=\"path\"></div>
            </div>
          </div>
          <div class=\"col-lg-9 col-md-9 col-sm-12\">
            <div class=\"alert alert-info\"><i class=\"fas fa-info-circle\"></i>";
        // line 40
        echo ($context["text_twig"] ?? null);
        echo "</div>
            <div id=\"recent\">
              <fieldset>
                <legend>";
        // line 43
        echo ($context["text_history"] ?? null);
        echo "</legend>
                <div id=\"history\"></div>
              </fieldset>
            </div>
            <div id=\"code\" style=\"display: none;\">
              <ul class=\"nav nav-tabs\"></ul>
              <div class=\"tab-content\"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<link href=\"view/javascript/codemirror/lib/codemirror.css\" rel=\"stylesheet\"/>
<link href=\"view/javascript/codemirror/theme/monokai.css\" rel=\"stylesheet\"/>
<script type=\"text/javascript\" src=\"view/javascript/codemirror/lib/codemirror.js\"></script>
<script type=\"text/javascript\" src=\"view/javascript/codemirror/lib/xml.js\"></script>
<script type=\"text/javascript\" src=\"view/javascript/codemirror/lib/formatting.js\"></script>
<script type=\"text/javascript\"><!--
\$('select[name=\\'store_id\\']').on('change', function(e) {
\t\$.ajax({
\t\turl: 'index.php?route=design/theme/path&user_token=";
        // line 65
        echo ($context["user_token"] ?? null);
        echo "&store_id=' + \$('select[name=\\'store_id\\']').val(),
\t\tdataType: 'json',
\t\tbeforeSend: function() {
\t\t\t\$('select[name=\\'store_id\\']').prop('disabled', true);
\t\t},
\t\tcomplete: function() {
\t\t\t\$('select[name=\\'store_id\\']').prop('disabled', false);
\t\t},
\t\tsuccess: function(json) {
\t\t\thtml = '';

\t\t\tif (json['directory']) {
\t\t\t\tfor (i = 0; i < json['directory'].length; i++) {
\t\t\t\t\thtml += '<a href=\"' + json['directory'][i]['path'] + '\" class=\"list-group-item list-group-item-action directory\">' + json['directory'][i]['name'] + ' <i class=\"fas fa-arrow-right fa-fw float-right\"></i></a>';
\t\t\t\t}
\t\t\t}

\t\t\tif (json['file']) {
\t\t\t\tfor (i = 0; i < json['file'].length; i++) {
\t\t\t\t\thtml += '<a href=\"' + json['file'][i]['path'] + '\" class=\"list-group-item list-group-item-action file\">' + json['file'][i]['name'] + ' <i class=\"fas fa-arrow-right fa-fw float-right\"></i></a>';
\t\t\t\t}
\t\t\t}

\t\t\t\$('#path').html(html);
\t\t},
\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t}
\t});
});

\$('select[name=\\'store_id\\']').trigger('change');

\$('#path').on('click', 'a.directory', function(e) {
\te.preventDefault();

\tvar element = this;

\t\$.ajax({
\t\turl: 'index.php?route=design/theme/path&user_token=";
        // line 104
        echo ($context["user_token"] ?? null);
        echo "&store_id=' + \$('select[name=\\'store_id\\']').val() + '&path=' + \$(element).attr('href'),
\t\tdataType: 'json',
\t\tbeforeSend: function() {
\t\t\t\$(element).find('i').removeClass('fa-arrow-right');
\t\t\t\$(element).find('i').addClass('fa-circle-notch fa-spin');
\t\t},
\t\tcomplete: function() {
\t\t\t\$(element).find('i').removeClass('fa-circle-notch fa-spin');
\t\t\t\$(element).find('i').addClass('fa-arrow-right');
\t\t},
\t\tsuccess: function(json) {
\t\t\thtml = '';

\t\t\tif (json['directory']) {
\t\t\t\tfor (i = 0; i < json['directory'].length; i++) {
\t\t\t\t\thtml += '<a href=\"' + json['directory'][i]['path'] + '\" class=\"list-group-item list-group-item-action directory\">' + json['directory'][i]['name'] + ' <i class=\"fas fa-arrow-right fa-fw float-right\"></i></a>';
\t\t\t\t}
\t\t\t}

\t\t\tif (json['file']) {
\t\t\t\tfor (i = 0; i < json['file'].length; i++) {
\t\t\t\t\thtml += '<a href=\"' + json['file'][i]['path'] + '\" class=\"list-group-item list-group-item-action file\">' + json['file'][i]['name'] + ' <i class=\"fas fa-arrow-right fa-fw float-right\"></i></a>';
\t\t\t\t}
\t\t\t}

\t\t\tif (json['back']) {
\t\t\t\thtml += '<a href=\"' + json['back']['path'] + '\" class=\"list-group-item list-group-item-action directory\">' + json['back']['name'] + ' <i class=\"fas fa-arrow-left fa-fw float-right\"></i></a>';
\t\t\t}

\t\t\t\$('#path').html(html);
\t\t},
\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t}
\t});
});

var editor = [];

\$('#path').on('click', 'a.file', function(e) {
\te.preventDefault();

\tvar element = this;

\t// Check if the file has an extension
\tvar pos = \$(element).attr('href').lastIndexOf('.');

\tif (pos != -1) {
\t\tvar tab_id = \$('select[name=\\'store_id\\']').val() + '-' + \$(element).attr('href').slice(0, pos).replace(/\\//g, '-').replace(/_/g, '-');
\t} else {
\t\tvar tab_id = \$('select[name=\\'store_id\\']').val() + '-' + \$(element).attr('href').replace(/\\//g, '-').replace(/_/g, '-');
\t}

\tif (!editor['#tab-' + tab_id]) {
\t\teditor['#tab-' + tab_id] = \$.ajax({
\t\t\turl: 'index.php?route=design/theme/template&user_token=";
        // line 159
        echo ($context["user_token"] ?? null);
        echo "&store_id=' + \$('select[name=\\'store_id\\']').val() + '&path=' + \$(element).attr('href'),
\t\t\tdataType: 'json',
\t\t\tbeforeSend: function() {
\t\t\t\t\$(element).find('i').removeClass('fa-arrow-right');
\t\t\t\t\$(element).find('i').addClass('fa-circle-notch fa-spin');
\t\t\t},
\t\t\tcomplete: function() {
\t\t\t\t\$(element).find('i').removeClass('fa-circle-notch fa-spin');
\t\t\t\t\$(element).find('i').addClass('fa-arrow-right');
\t\t\t},
\t\t\tsuccess: function(json) {
\t\t\t\tif (json['code']) {
\t\t\t\t\t\$('#code').show();
\t\t\t\t\t\$('#recent').hide();

\t\t\t\t\t\$('.nav-tabs').append('<li class=\"nav-item\"><a href=\"#tab-' + tab_id + '\" data-toggle=\"tab\" class=\"nav-link\">' + \$(element).attr('href').split('/').join(' / ') + '&nbsp;&nbsp;<i class=\"fas fa-minus-circle\"></i></a></li>');

\t\t\t\t\thtml = '<div class=\"tab-pane\" id=\"tab-' + tab_id + '\">';
\t\t\t\t\thtml += '  <textarea name=\"code\" rows=\"10\" class=\"form-control\"></textarea>';
\t\t\t\t\thtml += '  <input type=\"hidden\" name=\"store_id\" value=\"' + \$('select[name=\\'store_id\\']').val() + '\" />';
\t\t\t\t\thtml += '  <input type=\"hidden\" name=\"path\" value=\"' + \$(element).attr('href') + '\" />';
\t\t\t\t\thtml += '  <br />';
\t\t\t\t\thtml += '  <div class=\"float-right\">';
\t\t\t\t\thtml += '    <button type=\"button\" data-loading-text=\"";
        // line 182
        echo ($context["text_loading"] ?? null);
        echo "\" class=\"btn btn-primary\"><i class=\"fas fa-save\"></i>";
        echo ($context["button_save"] ?? null);
        echo "</button>';
\t\t\t\t\thtml += '    <button data-loading-text=\"";
        // line 183
        echo ($context["text_loading"] ?? null);
        echo "\" class=\"btn btn-danger\"><i class=\"fas fa-recycle\"></i>";
        echo ($context["button_reset"] ?? null);
        echo "</button>';
\t\t\t\t\thtml += '  </div>';
\t\t\t\t\thtml += '</div>';

\t\t\t\t\t\$('.tab-content').append(html);

\t\t\t\t\t\$('.nav-tabs a[href=\\'#tab-' + tab_id + '\\']').tab('show');

\t\t\t\t\t// Initialize codemirrror
\t\t\t\t\tvar codemirror = CodeMirror.fromTextArea(document.querySelector('.tab-content .active textarea'), {
\t\t\t\t\t\tmode: 'text/html',
\t\t\t\t\t\theight: '500px',
\t\t\t\t\t\tlineNumbers: true,
\t\t\t\t\t\tautofocus: true,
\t\t\t\t\t\ttheme: 'monokai'
\t\t\t\t\t});

\t\t\t\t\tcodemirror.setValue(json['code']);
\t\t\t\t}
\t\t\t},
\t\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t\t}
\t\t});
\t} else {
\t\t\$('.nav-tabs a[href=\\'#tab-' + tab_id + '\\']').tab('show');
\t}
});

\$('#code .nav-tabs').on('click', 'i.fas.fa-minus-circle', function(e) {
\te.preventDefault();

\t// 1. Remove tab functionality
\t\$(this).parent().removeAttr('data-toggle');

\tvar remove = \$(this).parent().attr('href');

\t// 2. Remove entry in the editor array
\tif (editor[remove]) {
\t\tdelete editor[remove];
\t}

\t// 3. Remove the tab
\t\$(this).parent().parent().remove();

\t// 4. Remove the tab panel
\t\$(remove).remove();

\tif (\$(this).parent().hasClass('active')) {
\t\t\$('.nav-tabs li:last-child a').tab('show');
\t}

\tif (!\$('#code > ul > li').length) {
\t\t\$('#code').hide();
\t\t\$('#recent').show();
\t}
});

\$('.tab-content').on('click', '.btn-primary', function(e) {
\tvar element = this;

\tvar editor = \$('.tab-content .active .CodeMirror')[0].CodeMirror;

\t\$.ajax({
\t\turl: 'index.php?route=design/theme/save&user_token=";
        // line 247
        echo ($context["user_token"] ?? null);
        echo "&store_id=' + \$('.tab-content .active input[name=\\'store_id\\']').val() + '&path=' + \$('.tab-content .active input[name=\"path\"]').val(),
\t\ttype: 'post',
\t\tdata: 'code=' + encodeURIComponent(editor.getValue()),
\t\tdataType: 'json',
\t\tbeforeSend: function() {
\t\t\t\$(element).button('loading');
\t\t},
\t\tcomplete: function() {
\t\t\t\$(element).button('reset');
\t\t},
\t\tsuccess: function(json) {
\t\t\t\$('.alert-dismissible').remove();

\t\t\tif (json['error']) {
\t\t\t\t\$('#content > .container-fluid').prepend('<div class=\"alert alert-danger alert-dismissible\"><i class=\"fas fa-exclamation-circle\"></i> ' + json['error'] + '  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></div>');
\t\t\t}

\t\t\tif (json['success']) {
\t\t\t\t\$('#content > .container-fluid').prepend('<div class=\"alert alert-success alert-dismissible\"><i class=\"fas fa-exclamation-circle\"></i> ' + json['success'] + '  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></div>');

\t\t\t\t\$('#history').load('index.php?route=design/theme/history&user_token=";
        // line 267
        echo ($context["user_token"] ?? null);
        echo "');
\t\t\t}
\t\t},
\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t}
\t});
});

\$('.tab-content').on('click', '.btn-danger', function(e) {
\tif (confirm('";
        // line 277
        echo ($context["text_confirm"] ?? null);
        echo "')) {
\t\tvar element = this;

\t\t\$.ajax({
\t\t\turl: 'index.php?route=design/theme/reset&user_token=";
        // line 281
        echo ($context["user_token"] ?? null);
        echo "&store_id=' + \$('.tab-content .active input[name=\\'store_id\\']').val() + '&path=' + \$('.tab-content .active input[name=\\'path\\']').val(),
\t\t\tdataType: 'json',
\t\t\tbeforeSend: function() {
\t\t\t\t\$(element).button('loading');
\t\t\t},
\t\t\tcomplete: function() {
\t\t\t\t\$(element).button('reset');
\t\t\t},
\t\t\tsuccess: function(json) {
\t\t\t\t\$('.alert-dismissible').remove();

\t\t\t\tif (json['error']) {
\t\t\t\t\t\$('#content > .container-fluid').prepend('<div class=\"alert alert-danger alert-dismissible\"><i class=\"fas fa-exclamation-circle\"></i> ' + json['error'] + '  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></div>');
\t\t\t\t}

\t\t\t\tif (json['success']) {
\t\t\t\t\t\$('#content > .container-fluid').prepend('<div class=\"alert alert-success alert-dismissible\"><i class=\"fas fa-exclamation-circle\"></i> ' + json['success'] + '  <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></div>');
\t\t\t\t}

\t\t\t\tvar codemirror = \$('.tab-content .active .CodeMirror')[0].CodeMirror;

\t\t\t\tcodemirror.setValue(json['code']);
\t\t\t},
\t\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t\t}
\t\t});
\t}
});

\$('#history').on('click', '.pagination a', function(e) {
\te.preventDefault();

\t\$('#history').load(this.href);
});

\$('#history').load('index.php?route=design/theme/history&user_token=";
        // line 317
        echo ($context["user_token"] ?? null);
        echo "');

\$('#history').on('click', '.btn-primary', function(e) {
\te.preventDefault();

\tvar element = this;

\t// Check if the file has an extension
\tvar tab_id = \$(element).parent().parent().find('input[name=\\'store_id\\']').val() + '-' + \$(element).parent().parent().find('input[name=\\'path\\']').val().replace(/\\//g, '-').replace(/_/g, '-');

\tif (!editor['#tab-' + tab_id]) {
\t\t\$.ajax({
\t\t\turl: 'index.php?route=design/theme/template&user_token=";
        // line 329
        echo ($context["user_token"] ?? null);
        echo "&store_id=' + \$(element).parent().parent().find('input[name=\\'store_id\\']').val() + '&path=' + \$(element).parent().parent().find('input[name=\\'path\\']').val(),
\t\t\tdataType: 'json',
\t\t\tbeforeSend: function() {
\t\t\t\t\$(element).button('loading');
\t\t\t},
\t\t\tcomplete: function() {
\t\t\t\t\$(element).button('reset');
\t\t\t},
\t\t\tsuccess: function(json) {
\t\t\t\tif (json['code']) {
\t\t\t\t\t\$('#code').show();
\t\t\t\t\t\$('#recent').hide();

\t\t\t\t\t\$('.nav-tabs').append('<li class=\"nav-item\"><a href=\"#tab-' + tab_id + '\" data-toggle=\"tab\" class=\"nav-link\">' + \$(element).parent().parent().find('input[name=\\'path\\']').val().split('/').join(' / ') + '&nbsp;&nbsp;<i class=\"fas fa-minus-circle\"></i></a></li>');

\t\t\t\t\thtml = '<div class=\"tab-pane\" id=\"tab-' + tab_id + '\">';
\t\t\t\t\thtml += '  <textarea name=\"code\" rows=\"10\"></textarea>';
\t\t\t\t\thtml += '  <input type=\"hidden\" name=\"store_id\" value=\"' + \$(element).parent().parent().find('input[name=\\'store_id\\']').val() + '\" />';
\t\t\t\t\thtml += '  <input type=\"hidden\" name=\"path\" value=\"' + \$(element).parent().parent().find('input[name=\\'path\\']').val() + '.twig\" />';
\t\t\t\t\thtml += '  <br />';
\t\t\t\t\thtml += '  <div class=\"float-right\">';
\t\t\t\t\thtml += '    <button type=\"button\" data-loading-text=\"";
        // line 350
        echo ($context["text_loading"] ?? null);
        echo "\" class=\"btn btn-primary\"><i class=\"fas fa-save\"></i>";
        echo ($context["button_save"] ?? null);
        echo "</button>';
\t\t\t\t\thtml += '    <button data-loading-text=\"";
        // line 351
        echo ($context["text_loading"] ?? null);
        echo "\" class=\"btn btn-danger\"><i class=\"fas fa-recycle\"></i>";
        echo ($context["button_reset"] ?? null);
        echo "</button>';
\t\t\t\t\thtml += '  </div>';
\t\t\t\t\thtml += '</div>';

\t\t\t\t\t\$('.tab-content').append(html);

\t\t\t\t\t\$('.nav-tabs a[href=\\'#tab-' + tab_id + '\\']').tab('show');

\t\t\t\t\t// Initialize codemirrror
\t\t\t\t\tvar codemirror = CodeMirror.fromTextArea(document.querySelector('.tab-content .active textarea'), {
\t\t\t\t\t\tmode: 'text/html',
\t\t\t\t\t\theight: '500px',
\t\t\t\t\t\tlineNumbers: true,
\t\t\t\t\t\tautofocus: true,
\t\t\t\t\t\ttheme: 'monokai'
\t\t\t\t\t});

\t\t\t\t\tcodemirror.setValue(json['code']);
\t\t\t\t}
\t\t\t},
\t\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t\t}
\t\t});
\t} else {
\t\t\$('.nav-tabs a[href=\\'#tab-' + tab_id + '\\']').tab('show');
\t}
});

\$('#history').on('click', '.btn-danger', function(e) {
\te.preventDefault();

\tif (confirm('";
        // line 383
        echo ($context["text_confirm"] ?? null);
        echo "')) {
\t\tvar element = this;

\t\t\$.ajax({
\t\t\turl: \$(element).attr('href'),
\t\t\tdataType: 'json',
\t\t\tbeforeSend: function() {
\t\t\t\t\$(element).button('loading');
\t\t\t},
\t\t\tcomplete: function() {
\t\t\t\t\$(element).button('reset');
\t\t\t},
\t\t\tsuccess: function(json) {
\t\t\t\t\$('.alert-dismissible').remove();

\t\t\t\tif (json['error']) {
\t\t\t\t\t\$('#history').before('<div class=\"alert alert-danger alert-dismissible\"><i class=\"fas fa-exclamation-circle\"></i> ' + json['error'] + ' <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></div>');
\t\t\t\t}

\t\t\t\tif (json['success']) {
\t\t\t\t\t\$('#history').before('<div class=\"alert alert-success alert-dismissible\"><i class=\"fas fa-exclamation-circle\"></i> ' + json['success'] + ' <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></div>');

\t\t\t\t\t\$('#history').load('index.php?route=design/theme/history&user_token=";
        // line 405
        echo ($context["user_token"] ?? null);
        echo "');
\t\t\t\t}
\t\t\t},
\t\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t\t}
\t\t});
\t}
});
//--></script>";
        // line 415
        echo ($context["footer"] ?? null);
    }

    public function getTemplateName()
    {
        return "design/theme.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  538 => 415,  526 => 405,  501 => 383,  464 => 351,  458 => 350,  434 => 329,  419 => 317,  380 => 281,  373 => 277,  360 => 267,  337 => 247,  268 => 183,  262 => 182,  236 => 159,  178 => 104,  136 => 65,  111 => 43,  105 => 40,  96 => 34,  89 => 29,  79 => 27,  75 => 26,  72 => 25,  65 => 21,  56 => 15,  49 => 10,  39 => 8,  35 => 7,  31 => 5,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "design/theme.twig", "");
    }
}
