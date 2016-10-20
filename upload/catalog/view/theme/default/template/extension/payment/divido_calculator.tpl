<div class="divido-calculator divido-theme-blue" data-divido-calculator data-divido-plans="<?php echo $planList; ?>" data-divido-amount="<?php echo $productPrice; ?>">
    <h1>
        <a href="https://www.divido.com" target="_blank" class="divido-logo divido-logo-sm" style="float:right;">Divido</a>
        <?php echo $text_checkout_title; ?> 
    </h1>

    <dl>
        <dt><span data-divido-choose-finance data-divido-label="<?php echo $text_choose_plan; ?>" data-divido-form="finance"></span></dt>
        <dd><span class="divido-deposit" data-divido-choose-deposit data-divido-label="<?php echo $text_choose_deposit; ?>" data-divido-form="deposit"></span></dd>
    </dl>
    <div class="description"><strong><span data-divido-agreement-duration></span> <?php echo $text_monthly_payments; ?> <span data-divido-monthly-instalment></span></strong></div>
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
