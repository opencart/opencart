<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator'] ?><a href="<?php echo $breadcrumb['href'] ?>"><?php echo $breadcrumb['text'] ?></a>
        <?php } ?>
    </div>
        

    <div class="box mBottom130">
        <div class="heading">
            <h1><?php echo $text_edit_heading; ?></h1>
            <div class="buttons">
                <?php if(isset($url_create_new)) : ?>
                <a onclick="location = '<?php echo $url_create_new; ?>'" class="button"><span><?php echo $button_create_new_listing; ?></span></a>
                <?php endif; ?>
                <?php if(isset($url_delete_links)) : ?>
                <a onclick="location = '<?php echo $url_delete_links; ?>'" class="button"><span><?php echo $button_remove_links;?></span></a>
                <?php endif; ?>
                <a onclick="location = '<?php echo $url_return; ?>'" class="button"><span><?php echo $button_return; ?></span></a>
            </div>
        </div>
        <div class="content">
            <?php if($has_saved_listings) : ?>
            <p><?php echo $text_has_saved_listings; ?></p><p><a onclick="location = '<?php echo $url_saved_listings; ?>'" class="button"><span><?php echo $button_saved_listings; ?></span></a></p>
            <?php endif; ?>
            <table align="left" class="list">
                <thead>
                    <tr>
                        <td class="center" colspan="5">Product links</td>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <td class="left" width="20%"><?php echo $column_name ;?></td>
                        <td class="left" width="20%"><?php echo $column_model ;?></td>
                        <td class="left" width="20%"><?php echo $column_combination ;?></td>
                        <td class="left" width="20%"><?php echo $column_sku ;?></td>
                        <td class="left" width="20%"><?php echo $column_amazon_sku ;?></td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($product_links as $link) : ?>
                    <tr>
                        <td class="left"><?php echo $link['product_name']; ?></td>
                        <td class="left"><?php echo $link['model']; ?></td>
                        <td class="left"><?php echo $link['combi']; ?></td>
                        <td class="left"><?php echo $link['sku']; ?></td>
                        <td class="left"><?php echo $link['amazon_sku']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php echo $footer; ?>