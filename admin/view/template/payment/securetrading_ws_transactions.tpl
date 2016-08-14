<table class="transactions list">
    <thead>
        <tr>
            <td class="left"><?php echo $column_order_id ?></td>
            <td class="left"><?php echo $column_transaction_reference ?></td>
            <td class="left"><?php echo $column_customer ?></td>
            <td class="left"><?php echo $column_total ?></td>
            <td class="left"><?php echo $column_currency ?></td>
            <td class="left"><?php echo $column_settle_status ?></td>
            <td class="left"><?php echo $column_status ?></td>
            <td class="left"><?php echo $column_payment_type ?></td>
            <td class="left"><?php echo $column_type ?></td>
        </tr>
    </thead>
    <tbody>
        <?php if ($transactions) { ?>
            <?php foreach ($transactions as $transaction) { ?>
            
            <tr>
                <td class="left"><a href="<?php echo $transaction['order_href'] ?>" target="_blank"><?php echo $transaction['order_id'] ?></a></td>
                <td class="left"><?php echo $transaction['transaction_reference'] ?></td>
                <td class="left"><?php echo $transaction['customer'] ?></td>
                <td class="left"><?php echo $transaction['total'] ?></td>
                <td class="left"><?php echo $transaction['currency'] ?></td>
                <td class="left"><?php echo $transaction['settle_status'] ?></td>
                <td class="left"><?php echo $transaction['status'] ?></td>
                <td class="left"><?php echo $transaction['payment_type'] ?></td>
                <td class="left"><?php echo $transaction['type'] ?></td>
            </tr>
            
            <?php } ?>
        <?php } else { ?>
        
        <tr>
            <td colspan="9" class="center"><?php echo $text_no_transactions ?></td>
        </tr>
        
        <?php } ?>
    </tbody>
</table>