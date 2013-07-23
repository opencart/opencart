<span id="AmazonCheckoutWidgetCart"></span>
<script type="text/javascript"><!--
              
    new CBA.Widgets.InlineCheckoutWidget({
        merchantId: "<?php echo $merchant_id ?>",
        buttonSettings: {
            color: '<?php echo $button_colour ?>',
            background: '<?php echo $button_background ?>',
            size: '<?php echo $button_size ?>',
        },
        onAuthorize: function(widget) {
            var redirectUrl = '<?php echo html_entity_decode($amazon_checkout) ?>';
            if (redirectUrl.indexOf('?') == -1) {
                redirectUrl += '?contract_id=' + widget.getPurchaseContractId();
            } else {
                redirectUrl += '&contract_id=' + widget.getPurchaseContractId();
            }
                      
            window.location = redirectUrl;
        }
    }).render("AmazonCheckoutWidgetCart");
              
//--></script>