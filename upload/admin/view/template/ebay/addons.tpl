<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
<?php 
        foreach ($breadcrumbs as $breadcrumb) {
            echo $breadcrumb['separator'].'<a href="'.$breadcrumb['href'].'">'.$breadcrumb['text'].'</a>';
        } 
?>
    </div>

    <div class="box mBottom130"> 
        <div class="heading">
            <h1><?php echo $lang_heading; ?></h1>
            <div class="buttons">
                <a onclick="location = '<?php echo $return; ?>';" class="button"><span><?php echo $lang_btn_return; ?></span></a>
            </div>
        </div>
        <div class="content">
            <?php if($validation === true) { ?>
                <p><?php echo $lang_addon_desc; ?></p>
                <table class="list" id="addonTable">
                    <thead>
                        <tr>
                            <td class="left"><?php echo $lang_addon_name; ?></td>
                            <td class="center width100"><?php echo $lang_addon_version; ?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(!empty($addons)){
                            foreach($addons as $addon) { 
                                echo'<tr><td class="left">'.$addon->addonName.'</td><td class="center">'.$addon->addonVersion.'</td></tr>';
                            }
                        }else{
                            echo '<tr><td class="left" colspan="2">'.$lang_addon_none.'</td></tr>';
                        } 
                        ?>
                    </tbody>
                </table>
            <?php }else{ ?>
                <div class="warning"><?php echo $lang_error_validation; ?></div>
            <?php } ?>
        </div>
    </div>
</div>

<?php echo $footer; ?>
