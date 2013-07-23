<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
        <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a class="button" href="<?php echo $return ?>"><?php echo $text_return ?></a></div>
        </div>
        <div class="content">
            <table class="form">
                <tr>
                    <td><?php echo $entry_order_recurring ?></td>
                    <td><?php echo $order_recurring_id ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_order_id ?></td>
                    <td><a href="<?php echo $order_href ?>"><?php echo $order_id ?></a></td>
                </tr>
                <tr>
                    <td><?php echo $entry_customer ?></td>
                    <td>
                        <?php if ($customer_href): ?>
                            <a href="<?php echo $customer_href ?>"><?php echo $customer ?></a>
                        <?php else: ?>
                            <?php echo $customer ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_email ?></td>
                    <td><?php echo $email ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_status ?></td>
                    <td><?php echo $status ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_date_created ?></td>
                    <td><?php echo $date_created ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_payment_reference ?></td>
                    <td><?php echo $profile_reference ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_payment_type ?></td>
                    <td><?php echo $payment_method ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_profile ?></td>
                    <td>
                        <?php if ($profile): ?>
                        <a href="<?php echo $profile ?>"><?php echo $profile_name ?></a>
                        <?php else: ?>
                        <?php echo $profile_name ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_profile_description ?></td>
                    <td><?php echo $profile_description ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_product ?></td>
                    <td><?php echo $product ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_quantity ?></td>
                    <td><?php echo $quantity ?></td>
                </tr>

                <?php if($cancel_link){ ?>
                <tr>
                    <td><?php echo $entry_cancel_payment ?></td>
                    <td><a id="cancel-profile" href="<?php echo $cancel_link; ?>" class="button"><?php echo $text_cancel ?></a></td>
                </tr>
                <?php } ?>
            </table>
            <h2><?php echo $text_transactions ?></h2>
            <table class="list">
                <thead>
                    <tr>
                        <td class="left"><?php echo $entry_date_created ?></td>
                        <td class="left"><?php echo $entry_amount ?></td>
                        <td class="left"><?php echo $entry_type ?></td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td class="left"><?php echo $transaction['created'] ?></td>
                        <td class="left"><?php echo $transaction['amount'] ?></td>
                        <td class="left"><?php echo $transaction['type'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    $('#cancel-profile').click(function(){
        return confirm("<?php echo $text_cancel_confirm ?>");
    });
//--></script>
<?php echo $footer; ?>