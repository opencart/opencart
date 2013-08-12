<div id="tab-openbay" style="display:none;">
    <table class="list">
        <thead>
        <tr>
            <td class="left"><?php echo $lang_marketplace; ?></td>
            <td class="center"><?php echo $lang_status; ?></td>
            <td class="right"><?php echo $lang_option; ?></td>
        </tr>
        </thead>
        <tfoot>
            <?php foreach ($markets as $market) { ?>
            <tr>
                <td class="left"><?php echo $market['name']; ?></td>
                <td class="center"><img style="display:inline; position:relative; top:3px;" src="view/image/<?php if($market['status'] == 1){echo'success';}else{echo'delete';} ?>.png" /></td>
                <td class="right">
                    <?php if($market['href'] != ''){ ?>
                        <a href="<?php echo $market['href']; ?>"<?php if($market['target'] != ''){ echo' target="'.$market['target'].'"'; } ?>><?php echo $market['text']; ?></a>
                    <?php }else{ ?>
                        <?php echo $market['text']; ?>
                    <?php } ?>
                </td>
            <?php } ?>
        </tfoot>
    </table>
</div>