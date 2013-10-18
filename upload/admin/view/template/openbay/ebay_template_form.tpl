<?php echo $header; ?>
<style type="text/css">
    .CodeMirror {border: 1px solid #888; height:1000px; width:100%;}
</style>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning" style="margin-bottom:10px;"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><?php echo $page_title; ?></h1>
            <div class="buttons">
                <a onclick="$('#form').submit();" class="button"><span><?php echo $lang_btn_save; ?></span></a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $lang_btn_cancel; ?></span></a>
            </div>
        </div>
        <div class="content">
            <form action="<?php echo $btn_save; ?>" method="post" enctype="multipart/form-data" id="form">
                <input type="hidden" name="template_id" value="<?php echo $template_id; ?>" />

                    <table class="form">
                        <tr>
                            <td><?php echo $lang_template_name; ?></td>
                            <td><input type="text" name="name" size="80" value="<?php if(isset($name)){ echo $name; } ?>"></td>
                        </tr>
                        <tr>
                            <td><?php echo $lang_template_html; ?></td>
                            <td>
                                <textarea name="html" cols="100" rows="100" id="code"><?php if(isset($html)){ echo $html; } ?></textarea>
                            </td>
                        </tr>
                    </table>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        mode: 'text/html',
        autoCloseTags: true,
        lineNumbers: true,
        tabMode: "indent",
        lineWrapping: true,
        indentUnit: 4
    });
</script>

<?php echo $footer; ?>