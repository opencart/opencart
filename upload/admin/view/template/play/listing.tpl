<?php echo $header; ?>
<div id="content">

    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>

    <div class="box" style="margin-bottom:130px;">
        <div class="heading">
            <h1><?php echo $lang_page_title; ?></h1>
            <div class="buttons">
                <a onclick="validateForm(); return false;" class="button"><span><?php echo $lang_save; ?></span></a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $lang_cancel; ?></span></a>
                <a onclick="location = '<?php echo $delete; ?>';" class="button"><span><?php echo $lang_delete; ?></span></a>
            </div>
        </div>
        <div class="content">
            <?php
                if (isset($listing['errors']) && is_array($listing['errors'])) {
                    foreach($listing['errors'] as $e){
                        echo'<div class="warning">'.$e['status_msg'].'</div>';
                    } 
                } 
            ?>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

                <input type="hidden" name="isbn" id="isbn" value="<?php echo $product['isbn']; ?>" />
                <input type="hidden" name="upc" id="upc" value="<?php echo $product['upc']; ?>" />
                <input type="hidden" name="ean" id="ean" value="<?php echo $product['ean']; ?>" />

                <input type="hidden" name="sku" value="<?php echo $product['product_id']; ?>" />
                <input type="hidden" name="add_delete" value="<?php echo $actionCode; ?>" />

                <table class="form">
                    <tr>
                        <td><p><?php echo $lang_product_name; ?></p></td>
                        <td><p><?php echo $product['name']; ?></p></td>
                    </tr>
                    <tr>
                        <td><p><label for="product_id"><?php echo $lang_product_id; ?></p></td>
                        <td>
                            <p>
                                <?php if(isset($listing['play_product_id'])){ echo $listing['play_product_id']; }else{ ?>
                                <input type="text" name="product_id" id="product_id" style="width:300px;" maxlength="" />
                                <?php } ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td><p><?php echo $lang_product_id_type; ?></p></td>
                        <td>
                            <p>
                                <?php if(isset($listing['play_product_id_type'])){ echo $product_id_types[$listing['play_product_id_type']]; }else{ ?>
                                    <select name="product_id_type" style="width:200px;" id="product_id_type">
                                        <?php foreach($product_id_types as $key => $type){ ?>
                                            <option value="<?php echo $key; ?>"><?php echo $type; ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td><p><?php echo $lang_product_dispatch_to; ?></p></td>
                        <td>
                            <p>
                                <select name="dispatch_to" style="width:200px;" id="dispatch_to">
                                    <?php foreach($product_dispatch_to as $key => $type){ ?>
                                        <option value="<?php echo $key; ?>"
                                            <?php
                                                if(!isset($listing['dispatch_to'])){
                                                    if($defaults['shipto'] == $key){ echo ' selected'; }
                                                }else{
                                                    if($listing['dispatch_to'] == $key){ echo ' selected'; }
                                                }
                                            ?>
                                        ><?php echo $type; ?></option>
                                    <?php } ?>
                                </select>
                            </p>
                        </td>
                    </tr>
                    <tr id="price_tr_uk">
                        <td><p><label for="price_uk"><?php echo $lang_price_uk; ?></p></td>
                        <td>
                            <p>&pound;<input type="text" name="price_uk" id="price_uk" style="width:75px;" value="<?php if(isset($listing['price_gb'])){ echo $listing['price_gb']; } ?>" /></p>
                        </td>
                    </tr>
                    <tr id="price_tr_euro">
                        <td><p><label for="price_euro"><?php echo $lang_price_euro; ?></p></td>
                        <td>
                            <p><input type="text" name="price_euro" id="price_euro" style="width:75px;" value="<?php if(isset($listing['price_eu'])){ echo $listing['price_eu']; } ?>" />&euro;</p>
                        </td>
                    </tr>
                    <tr>
                        <td><p><label for="qty"><?php echo $lang_quantity; ?></p></td>
                        <td>
                            <p>
                                <?php echo $product['quantity']; ?>
                                <input type="hidden" name="qty" id="qty" value="<?php echo $product['quantity']; ?>" />
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td><p><?php echo $lang_condition; ?></p></td>
                        <td>
                            <p>
                                <select name="condition" style="width:200px;">
                                    <?php foreach($product_conditions as $key => $type){ ?>
                                        <option value="<?php echo $key; ?>"
                                            <?php
                                                if(!isset($listing['condition'])){
                                                    if($defaults['condition'] == $key){ echo ' selected'; }
                                                }else{
                                                    if($listing['condition'] == $key){ echo ' selected'; }
                                                }
                                            ?>
                                        ><?php echo $type; ?></option>
                                    <?php } ?>
                                </select>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td><p><?php echo $lang_product_dispatch_fr; ?></p></td>
                        <td>
                            <p>
                                <select name="dispatch_from" style="width:200px;">
                                    <?php foreach($product_dispatch_fr as $key => $type){ ?>
                                        <option value="<?php echo $key; ?>"
                                            <?php
                                                if(!isset($listing['dispatch_from'])){
                                                    if($defaults['shipfrom'] == $key){ echo ' selected'; }
                                                }else{
                                                    if($listing['dispatch_from'] == $key){ echo ' selected'; }
                                                }
                                            ?>
                                        ><?php echo $type; ?></option>
                                    <?php } ?>
                                </select>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $lang_comment; ?></td>
                        <td>
                            <textarea name="comment" style="width:400px; height:100px;"><?php if(isset($listing['comment'])){ echo $listing['comment']; } ?></textarea>
                        </td>
                    </tr>
                </table>
                <div class="buttons" style="text-align:right;">
                    <a onclick="validateForm(); return false;" class="button"><span><?php echo $lang_save; ?></span></a>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

    function validateForm() {
        //check that the prices have been entered based on the ship to location
        var dispatchId  = $('#dispatch_to').val();
        var priceUk     = $('#price_uk').val();
        var priceEu     = $('#price_euro').val();
        if(dispatchId == 1){
            if(priceUk < 0.98){
                alert('<?php echo $lang_error_min_price; ?>');
                return false;
            }
        }

        if(dispatchId == 2){
            if(priceEu < 0.98){
                alert('<?php echo $lang_error_min_price_eu; ?>');
                return false;
            }
        }

        if(dispatchId == 3){
            if(priceUk < 0.98){
                alert('<?php echo $lang_error_max_price; ?>');
                return false;
            }
            if(priceEu < 0.98){
                alert('<?php echo $lang_error_max_price_eu; ?>');
                return false;
            }
        }

        $('#form').submit();
    }

    function displayPriceBoxes(){
        var dispatchId = $('#dispatch_to').val();

        if(dispatchId == 1){
            $('#price_tr_uk').show();
            $('#price_tr_euro').hide();
        }

        if(dispatchId == 2){
            $('#price_tr_uk').hide();
            $('#price_tr_euro').show();
        }

        if(dispatchId == 3){
            $('#price_tr_uk').show();
            $('#price_tr_euro').show();
        }
    }

    function loadProductId(){
        var typeId = $('#product_id_type').val();
        var isbn = $('#isbn').val();
        var ean = $('#ean').val();
        var upc = $('#upc').val();

        if(typeId == 1){
            $('#product_id').val('');
        }

        if(typeId == 2){
            $('#product_id').val(isbn);
        }

        if(typeId == 3){
            if(ean != ''){
                $('#product_id').val(ean);
            }
            if(upc != ''){
                $('#product_id').val(upc);
            }
            if(upc == '' && ean == ''){
                $('#product_id').val('');
            }
        }
    }

    $('#dispatch_to').change(function(){
        displayPriceBoxes();
    });

    $('#product_id_type').change(function(){
        loadProductId();
    });

    $(document).ready(function() {
        displayPriceBoxes();
        loadProductId();
    });
    //--></script>
<?php echo $footer; ?>