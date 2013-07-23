<h2><?php echo $text_amazon_details ?></h2>
<table class="form">
    <tr>
        <td><?php echo $text_amazon_order_id ?></td>
        <td><?php echo $amazon_order_id ?></td>
    </tr>
</table>
<table class="list">
    <thead>
        <tr>
            <td class="left"><?php echo $column_product; ?></td>
            <td class="left"><?php echo $column_model; ?></td>
            <td class="left"><?php echo $column_amazon_order_item_code; ?></td>
            <td class="right"><?php echo $column_quantity; ?></td>
            <td class="right"><?php echo $column_price; ?></td>
            <td class="right"><?php echo $column_total; ?></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product) { ?>
            <tr>
                <td class="left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                    <?php foreach ($product['option'] as $option) { ?>
                        <br />
                        <?php if ($option['type'] != 'file') { ?>
                            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                        <?php } else { ?>
                            &nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
                        <?php } ?>
                    <?php } ?></td>
                <td class="left"><?php echo $product['model']; ?></td>
                <td class="left"><?php echo $product['amazon_order_item_code']; ?></td>
                <td class="right"><?php echo $product['quantity']; ?></td>
                <td class="right"><?php echo $product['price']; ?></td>
                <td class="right"><?php echo $product['total']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<p><?php echo $help_adjustment ?></p>
<p><?php echo $text_download ?></p>
<p><?php echo $text_upload_template ?></p>
<p><a id="button-upload" class="button"><?php echo $text_upload; ?></a></p>

<table class="list">
    <thead>
        <tr>
            <td class="left"><?php echo $column_submission_id ?></td>
            <td class="left"><?php echo $column_status ?></td>
            <td class="left"><?php echo $column_text ?></td>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($report_submissions as $report_submission): ?>

            <tr>
                <td class="left"><?php echo $report_submission['submission_id'] ?></td>
                <td class="left"><?php echo $report_submission['status'] ?></td>
                <td class="left"><?php echo $report_submission['text'] ?></td>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>

<script type="text/javascript" src="view/javascript/jquery/ajaxupload.js"></script> 
<script type="text/javascript"><!--
    
    new AjaxUpload('#button-upload', {
        action: 'index.php?route=payment/amazon_checkout/uploadOrderAdjustment&token=<?php echo $token; ?>',
        name: 'file',
        autoSubmit: true,
        responseType: 'json',
        
        onSubmit: function(file, extension) {
            $('#button-upload').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
            $('#button-upload').attr('disabled', true);
        },
        
        onComplete: function(file, json) {
            console.log(json);
            
            $('#button-upload').attr('disabled', false);
            
            if (json['success']) {
                alert(json['success']);
                
                $.get('index.php?route=payment/amazon_checkout/addSubmission&order_id=<?php echo $order_id ?>&submission_id=' + json['submission_id'] + '&token=<?php echo $token; ?>');
            }
            
            if (json['error']) {
                alert(json['error']);
            }
            
            $('.loading').remove();	
        }
    });
    
//--></script>