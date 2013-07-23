<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <div class="box">
        <div class="heading">
            <h1><?php echo $lang_title;?></h1>
            <div class="buttons">
                <a class="button" onclick="location = '<?php echo $link_overview; ?>';" ><span><?php echo $lang_btn_return; ?></span></a>
            </div>
        </div>

        <div class="content">
            <?php if (!$user_plan) { ?>
                <div id="register-div">
                    <h2><?php echo $lang_register ?></h2>
                     <p><?php echo $lang_register_invite ?></p>
                     <p>
                       <a href="<?php echo $server ?>account/register/" class="button" target="_blank"><?php echo $lang_register ?></a>
                     </p>
                </div> 
            <?php } else { ?>
                <div id="userPlan-div">
                    <h2><?php echo $lang_current_plan; ?></h2>
                    <table class="list" style="width: 650px">
                        <thead>
                            <tr>
                                <td class="left" style="text-align: center" colspan="2"><?php echo $lang_your_plan ?></td>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="right"><b><?php echo $lang_account_status;?>:</b></td>
                                <td class="left"><?php echo $user_plan['user_status'] ?></td>
                            </tr>
                            <tr>
                                <td class="right"><b><?php echo $lang_name ?>:</b></td>
                                <td class="left"><?php echo $user_plan['title'] ?></td>
                            </tr>
                            <tr>
                                <td class="right"><b><?php echo $lang_description ?>:</b></td>
                                <td class="left"><?php echo $user_plan['description'] ?></td>
                            </tr>
                            <tr>
                                <td class="right"><b><?php echo $lang_order_frequency ?>:</b></td>
                                <td class="left"><?php echo $user_plan['order_frequency'] ?></td>
                            </tr>
                            <tr>
                                <td class="right"><b><?php echo $lang_product_listings ?>:</b></td>
                                <td class="left"><?php echo $user_plan['product_listings'] ?></td>
                            </tr>
                            <tr>
                                <td class="right"><b><?php echo $lang_listings_remaining ?>:</b></td>
                                <td class="left"><?php echo $user_plan['listings_remain'] ?></td>
                            </tr>
                            <tr>
                                <td class="right"><b><?php echo $lang_listings_reserved ?>:</b></td>
                                <td class="left"><?php echo $user_plan['listings_reserved'] ?></td>
                            </tr>
                            <tr>
                                <td class="right"><b><?php echo $lang_price ?>:</b></td>
                                <td class="left">&pound;<?php echo $user_plan['price'] ?></td>
                            </tr>
                        </tbody>
                     </table>   
                </div>
                <div id="changePlan-div">    
                    <h2><?php echo $lang_change_plans ?></h2>
                    <p><?php echo $lang_change_plans_help ?></p>
                    <div>
                        <table class="list" style="width: 650px">
                            <thead>
                                <tr>
                                    <td class="right"><?php echo $lang_name ?></td>
                                    <td class="left"><?php echo $lang_description ?></td>
                                    <td class="left"><?php echo $lang_order_frequency ?></td>
                                    <td class="left"><?php echo $lang_product_listings ?></td>
                                    <td class="left"><?php echo $lang_price ?></td>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($plans as $plan): ?>
                                <tr>
                                    <td class="right"><?php echo $plan['title'] ?></td>
                                    <td class="left"><?php echo $plan['description'] ?></td>
                                    <td class="left"><?php echo $plan['order_frequency'] ?></td>
                                    <td class="left"><?php echo $plan['product_listings'] ?></td>
                                    <td class="left">&pound;<?php echo $plan['price'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($token): ?>
                        <a href="<?php echo $server ?>account/changePlan/?token=<?php echo $token ?>" class="button" target="_blank"><span><?php echo $lang_change_plan ?></span></a>
                    <?php endif; ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<?php echo $footer; ?>