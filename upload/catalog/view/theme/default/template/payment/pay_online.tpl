<div class="buttons">
    <div class="pull-right">
        <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $text_loading; ?>" />
    </div>
</div>
<script type="text/javascript"><!--
    $('#button-confirm').on('click', function() {
        $.ajax({
            type: 'get',
            url: 'index.php?route=checkout/confirm/addOrder',
            cache: false,
            beforeSend: function() {
                $('#button-confirm').button('loading');
            },
            complete: function() {
                $('#button-confirm').button('reset');
            },
            success: function(json) {
                debugger;
                if(json['success'] == 1) {
                    location = '<?php echo $continue; ?>';
                } else {
                    location = json['redirect'];
                }
            }
        });
    });
    //--></script>
