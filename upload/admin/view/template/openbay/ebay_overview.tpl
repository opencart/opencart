<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
<?php 
        foreach ($breadcrumbs as $breadcrumb) {
            echo $breadcrumb['separator'].'<a href="'.$breadcrumb['href'].'">'.$breadcrumb['text'].'</a>';
        } 
?>
    </div> 
        
    <?php if ($success) { ?>
        <div class="success mBottom10"><?php echo $success; ?></div>
    <?php } ?>

    <div class="box mBottom130"> 
        <div class="heading">
            <h1><?php echo $lang_heading; ?></h1>
        </div>
        <div class="content">
            <div class="openbayLinks">
                <div class="openbayPod" onclick="location='<?php echo $links_settings; ?>'">
                    <img src="view/image/openbay/openbay_icon1.png" title="" alt="" border="0" />
                    <h3><?php echo $lang_heading_settings; ?></h3>
                </div>
                
                <?php if($validation == true){ ?>
                    <div class="openbayPod" onclick="location='<?php echo $links_sync; ?>'">
                        <img src="view/image/openbay/openbay_icon10.png" title="" alt="" border="0" />
                        <h3><?php echo $lang_heading_sync; ?></h3>
                    </div>
                    <div class="openbayPod" onclick="location='<?php echo $links_subscribe; ?>'">
                        <img src="view/image/openbay/openbay_icon2.png" title="" alt="" border="0" />
                        <h3><?php echo $lang_heading_account; ?></h3>
                    </div>
                    <div class="openbayPod" onclick="location='<?php echo $links_itemlink; ?>'">
                        <img src="view/image/openbay/openbay_icon3.png" title="" alt="" border="0" />
                        <h3><?php echo $lang_heading_links; ?></h3>
                    </div>
                    <div class="openbayPod" onclick="location='<?php echo $links_itemimport; ?>'">
                        <img src="view/image/openbay/openbay_icon5.png" title="" alt="" border="0" />
                        <h3><?php echo $lang_heading_item_import; ?></h3>
                    </div>
                    <div class="openbayPod" onclick="location='<?php echo $links_orderimport; ?>'">
                        <img src="view/image/openbay/openbay_icon13.png" title="" alt="" border="0" />
                        <h3><?php echo $lang_heading_order_import; ?></h3>
                    </div>
                    <div class="openbayPod" onclick="location='<?php echo $links_summary; ?>'">
                        <img src="view/image/openbay/openbay_icon8.png" title="" alt="" border="0" />
                        <h3><?php echo $lang_heading_summary; ?></h3>
                    </div>
                    <div class="openbayPod" onclick="location='<?php echo $links_profile; ?>'">
                        <img src="view/image/openbay/openbay_icon12.png" title="" alt="" border="0" />
                        <h3><?php echo $lang_heading_profile; ?></h3>
                    </div>
                    <div class="openbayPod" onclick="location='<?php echo $links_template; ?>'">
                        <img src="view/image/openbay/openbay_icon12.png" title="" alt="" border="0" />
                        <h3><?php echo $lang_heading_template; ?></h3>
                    </div>
                <?php }else{ ?>
                    <a class="openbayPod" href="https://account.openbaypro.com/ebay/apiRegister/" target="_BLANK">
                        <img src="view/image/openbay/openbay_icon2.png" title="" alt="" border="0" />
                        <h3><?php echo $lang_heading_register; ?></h3>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
    $(document).ready(function() {
        $('.openbayPod').hover( function(){
            $(this).css('background-color', '#CCCCCC').css('border-color', '#003366');
        },
        function(){
            $(this).css('background-color', '#FFFFFF').css('border-color', '#CCCCCC');
        });
    });
//--></script>

<?php echo $footer; ?>