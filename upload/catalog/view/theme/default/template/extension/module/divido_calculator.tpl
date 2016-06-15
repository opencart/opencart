<script src="<?php echo $merchant_script; ?>"></script>
<style>
.divido-calculator {
	padding:10px 20px 20px 20px;
	text-shadow:none;
	-webkit-box-shadow: rgba(0, 0, 0, 0.2) 0 1px 2px 0, rgba(0, 0, 0, 0.05) 0 0 0 5px;
	-moz-box-shadow: rgba(0, 0, 0, 0.2) 0 1px 2px 0, rgba(0, 0, 0, 0.05) 0 0 0 5px;
	-ms-box-shadow: rgba(0, 0, 0, 0.2) 0 1px 2px 0, rgba(0, 0, 0, 0.05) 0 0 0 5px;
	-o-box-shadow: rgba(0, 0, 0, 0.2) 0 1px 2px 0, rgba(0, 0, 0, 0.05) 0 0 0 5px;
	box-shadow: rgba(0, 0, 0, 0.2) 0 1px 2px 0, rgba(0, 0, 0, 0.05) 0 0 0 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	-ms-border-radius: 5px;
	-o-border-radius: 5px;
	border-radius: 5px;
	border: 1px solid #555;
	border-color: #d9d7ce #c8c6c1 #B0AEA6;
	margin:20px;
}

#divido-checkout {
	margin:0px;
}

.divido-calculator h1 {
    color: white;
    font-size: 24px;
}

@media screen and (min-width: 550px) {
    .divido-calculator h1 {
        font-size: 33px;
    }
}

.divido-calculator dl {
	width: 100%;
	overflow: hidden;
	padding: 0;
	margin: 0
}

.divido-calculator dt,.divido-calculator dd {
	float: left;
	padding: 2px 0px;
	margin: 0
}


.divido-calculator > dl > dt, .divido-calculator > dl > dd {
    min-width: 150px;
}

.divido-calculator > dl > dt, .divido-calculator > dl > dd {
    width: 100%;
}

.divido-calculator > dl > dt {
    margin-bottom: 20px;
}

@media screen and (min-width: 960px) {
    .divido-calculator > dl > dt, .divido-calculator > dl > dd {
        width: 50%;
    }

    .divido-calculator > dl > dt {
        margin-bottom: auto;
    }
}

.divido-calculator div.divido-info dl dt, .divido-calculator div.divido-info dl dd {
    width: 100%;
}
.divido-calculator div.divido-info dl dd {
    padding-left: 5px;
}
@media screen and (min-width: 350px) {
    .divido-calculator div.divido-info dl dt {
        width: 70%;
    }
    .divido-calculator div.divido-info dl dd {
        width: 30%;
    }
}
@media screen and (min-width: 450px) {
    .divido-calculator div.divido-info dl dt, .divido-calculator div.divido-info dl dd {
        width: 50%;
    }
}
@media screen and (min-width: 550px) {
    .divido-calculator div.divido-info dl dt {
        width: 40%;
    }
    .divido-calculator div.divido-info dl dd {
        width: 60%;
    }
}

@media screen and (min-width: 1080px) {
	.divido-calculator div.divido-info dl dt {
		width: 30%;
	}
	.divido-calculator div.divido-info dl dd {
		width: 70%;
	}
}

.divido-calculator input[type=range]{
	-webkit-appearance: none;
	display:inline-block;
	width:80%;
	border:0px;
	padding:0px;
}

@media screen and (min-width: 450px) {
    .divido-calculator input[type=range]{
	    width:85%;
    }
}

@media screen and (min-width: 550px) {
    .divido-calculator input[type=range]{
	    width:90%;
    }
}

.divido-calculator select {
	text-transform: none;
	width: 100%;
}

@media screen and (min-width: 960px) {
    .divido-calculator select {
        width: 80%;
    }
}

.divido-calculator div.description {
	font-size:13px;
	margin-top:15px;
	margin-bottom:20px;
}

.divido-calculator .divido-info {
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	-ms-border-radius: 5px;
	-o-border-radius: 5px;
	border-radius: 5px;
}

.divido-calculator .divido-deposit span {
	display:inline-block;
	margin-left:4px;
	line-height:15px;
	vertical-align:top;
}

.divido-calculator input[type=range]::-webkit-slider-runnable-track {
	height: 5px;
	border: none;
	border-radius: 3px;
}

.divido-calculator label {
	display:block;
	clear:both;
	font-weight:bold;
}

.divido-calculator input[type=range]::-webkit-slider-thumb {
	-webkit-appearance: none;
	border: none;
	height: 16px;
	width: 16px;
	border-radius: 50%;
	margin-top: -4px;
}

.divido-calculator input[type=range]:focus {
	outline: none !important;
	border:0px !important;
}

.divido-calculator .divido-logo {
	width: 140px;
	height: 42px;
	display: block;
	font-size: 20px;
	line-height: 1;
	text-indent: -9999px;
	background-image:url('//content.divido.com.s3-eu-west-1.amazonaws.com/images/logo-black-140x42.png');
	background-repeat: no-repeat;
	background-size: cover;
}

.divido-calculator .divido-logo-sm {
	width: 75px;
	height: 23px;
	background-image:url('//content.divido.com.s3-eu-west-1.amazonaws.com/images/logo-black-75x23.png');
}

.divido-calculator .divido-info {
	padding:20px;
}


/* 			*/

.divido-them-light .divido-info {
	background:#f9f9f9;
}

.divido-theme-light a.divido-logo {
	background-image:url('//content.divido.com.s3-eu-west-1.amazonaws.com/images/logo-blue-140x42.png');
}
.divido-theme-light a.divido-logo-sm {
	background-image:url('//content.divido.com.s3-eu-west-1.amazonaws.com/images/logo-blue-75x23.png');
}

.divido-theme-light {
	background-color:#fff;
	color:#3E4F8B;
}

.divido-theme-light p {
	color:#3E4F8B !important;
}

.divido-theme-light input[type=range]:focus::-webkit-slider-runnable-track {
	background: #ccc;
}

.divido-theme-light input[type=range] {
	background-color:transparent;
}

.divido-theme-light input[type=range]::-webkit-slider-runnable-track {
	background: #999;
}

.divido-theme-light input[type=range]::-webkit-slider-thumb {
	background: #3E4F8B;
}


/* 			*/

.divido-theme-blue .divido-info {
	background:#f9f9f9;
	color:#000;
}

.divido-theme-blue a.divido-logo {
	background-image:url('//content.divido.com.s3-eu-west-1.amazonaws.com/images/logo-white-140x42.png');
}

.divido-theme-blue a.divido-logo-sm {
	background-image:url('//content.divido.com.s3-eu-west-1.amazonaws.com/images/logo-white-75x23.png');
}

.divido-theme-blue {
	background-color:#3E4F8B;
	color:#fff;
}

.divido-theme-blue p {
	color:#fff !important;
}

.divido-theme-blue input[type=range]:focus::-webkit-slider-runnable-track {
	background: #ccc;
}

.divido-theme-blue input[type=range] {
	background-color:transparent;
}

.divido-theme-blue input[type=range]::-webkit-slider-runnable-track {
	background: #999;
}

.divido-theme-blue input[type=range]::-webkit-slider-thumb {
	background: #fff;
}


.divido-btn {
	-webkit-border-radius: 4	;
    -moz-border-radius: 4;
    border-radius: 4px;
    font-family: Arial;
    color: #ffffff;
    font-size: 11px;
    background: #3E4F8B;
    padding: 3px 7px 5px 7px !important;
    text-decoration: none;
    border:1px solid #296AB3;
}

.divido-btn :hover {
    background: #3cb0fd;
    background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
    background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
    text-decoration: none;
}

.divido-widget img {
    display: inline-block;
}

.divido-calculator select {
        color: #000;
}
</style>
<div id="divido-checkout" data-divido-calculator class="divido-calculator divido-theme-blue" data-divido-amount="<?php echo $product_price; ?>" data-divido-plans="<?php echo $plan_list; ?>">
    <h1>
        <a href="https://www.divido.com" target="_blank" class="divido-logo divido-logo-sm" style="float:right;">Divido</a>
        <?php echo $text_checkout_title; ?>
    </h1>
    <div style="clear:both;"></div>
    <dl>
        <dt><span data-divido-choose-finance data-divido-label="<?php echo $text_choose_plan; ?>" data-divido-form="divido_finance"></span></dt>
        <dd><span class="divido-deposit" data-divido-choose-deposit data-divido-label="<?php echo $text_choose_deposit; ?>" data-divido-form="divido_deposit"></span></dd>
    </dl>
    <div class="description">
        <strong>
        <span data-divido-agreement-duration></span> <?php echo $text_monthly_payments; ?> <span data-divido-monthly-instalment></span>
        </strong>
    </div>
    <div class="divido-info">
        <dl>
            <dt><?php echo $text_term; ?></dt>
            <dd><span data-divido-agreement-duration></span> <?php echo $text_months; ?></dd>
            <dt><?php echo $text_monthly_installment; ?></dt>
            <dd><span data-divido-monthly-instalment></span></dd>
            <dt><?php echo $text_deposit; ?></dt>
            <dd><span data-divido-deposit></span></dd>
            <dt><?php echo $text_credit_amount; ?></dt>
            <dd><span data-divido-credit-amount-rounded></span></dd>
            <dt><?php echo $text_amount_payable; ?></dt>
            <dd><span data-divido-total-payable-rounded></span></dd>
            <dt><?php echo $text_total_interest; ?></dt>
            <dd><span data-divido-interest-rate></span></dd>
        </dl>
    </div>
    <div class="clear"></div>
</div>
