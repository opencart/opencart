<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="list-unstyled payment-methods">
                <li>
                    <a href="<?php echo $paypal_express_url; ?>" title="PayPal Express">
                        <img src="<?php echo HTTPS_SERVER; ?>/catalog/view/theme/default/image/PayPal.png" />
                    </a>
                </li>
                <li>
                    <a href="<?php echo ($paypal_pro_url . '&credit=VISA'); ?>" title="Visa">
                        <img src="<?php echo HTTPS_SERVER; ?>/catalog/view/theme/default/image/visa.png" />
                    </a>
                </li>
                <li>
                    <a href="<?php echo ($paypal_pro_url . '&credit=MASTERCARD'); ?>" title="mastercard">
                        <img src="<?php echo HTTPS_SERVER; ?>/catalog/view/theme/default/image/mastercard.png" />
                    </a>
                </li>
            </ul>

            <?php /*
            <!--<div class="row">
                <div class="col-md-12">
                    <h2 class="text-danger">SandBox Test Account</h2>
                </div>
                <div class="col-md-6">
                    <p><h3>Account: <b>dev-buyer@datatellit.com</b></h3></p>
                    <p><h3>Password: <b>12349876</b></h3></p>
                </div>
                <div class="col-md-6">
                    <p><h3>Credit Card Number: <b>4214028266324685</b></h3></p>
                    <p><h3>Credit Card Type: <b>VISA</b></h3></p>
                    <p><h3>Expiration Date: <b>10/2021</b></h3></p>
                    <p><h3>Card Security Code (CVV2): <b>123</b></h3></p>
                </div>
            </div>
            */?>

            <?php echo $content_bottom; ?>
        </div>
        <?php echo $column_right; ?>
    </div>
</div>