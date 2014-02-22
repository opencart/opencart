<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if (isset($error_warning)) { ?>
    <div class="warning" style="margin-bottom:10px;"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if (isset($success)) { ?>
    <div class="success" style="margin-bottom:10px;"><?php echo $success; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/product.png" alt="" /> <?php echo $lang_title_list; ?></h1>
            <div class="buttons">
                <form action="<?php echo $btn_add; ?>" method="post" id="addForm">
                    <a onclick="$('#addForm').submit();" class="button"><span><?php echo $lang_btn_add; ?></span></a>
                </form>
            </div>
        </div>   
        <div class="content">
            <table class="list">
                <thead>
                    <tr>
                        <td class="left"><?php echo $lang_template_name; ?></td>
                        <td class="left" width="150"></td>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($templates) { ?>
                        <?php foreach ($templates as $template) { ?>
                        <tr>
                            <td class="left"><?php echo $template['name']; ?></td>
                            <td class="right">
                                <div class="buttons">
                                    <a href="<?php echo $template['link_edit']; ?>" class="button profileEdit"><?php echo $lang_btn_edit; ?></a>&nbsp;
                                    <a href="<?php echo $template['link_delete']; ?>" class="button profileDelete"><?php echo $lang_btn_delete; ?></a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } else { ?>
                    <tr>
                        <td class="center" colspan="2"><?php echo $lang_no_results; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
    $(document).ready(function() {
        $('a.profileDelete').click(function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            var confirm_box = confirm('<?php echo $lang_confirm_delete; ?>');
            if (confirm_box) {
                window.location = url;
            }
        });
    });
//--></script>

<?php echo $footer; ?>