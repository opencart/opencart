<?php echo $header; ?>

<!-- Breadcrumb -->
<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    
    <li>
        <a href="<?php echo $breadcrumb['href']; ?>">
            <?php echo $breadcrumb['text']; ?>
        </a>
    </li>
    <?php } ?>
</ul>

<?php if ($success) { ?>
<div class="alert alert-success">
  <?php echo $success; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>

<div class="row">

    <?php echo $column_left; ?>


    <div id="content" class="span9">

        <?php echo $content_top; ?>

        <h2><?php echo $heading_title; ?></h2>



        <?php if ($products) { ?>
        <div class="wishlist-info">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <td class="image"><?php echo $column_image; ?></td>
                        <td class="name"><?php echo $column_name; ?></td>
                        <td class="model"><?php echo $column_model; ?></td>
                        <td class="stock"><?php echo $column_stock; ?></td>
                        <td class="price"><?php echo $column_price; ?></td>
                        <td class="action"><?php echo $column_action; ?></td>
                    </tr>
                </thead>

                <?php foreach ($products as $product) { ?>
                <tbody id="wishlist-row<?php echo $product['product_id']; ?>">
                    <tr>
                        
                        <td class="image" width="47">
                            <?php if ($product['thumb']) { ?>
                            <a href="<?php echo $product['href']; ?>">
                                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
                            </a>
                            <?php } else { ?>

                            <a href="<?php echo $product['href']; ?>">
                                <img src="catalog/view/theme/default/image/placeholder.png" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
                            </a>

                            <?php } ?>
                        </td>

                        <td class="name">
                            <a href="<?php echo $product['href']; ?>">
                                <?php echo $product['name']; ?>
                            </a>
                        </td>

                        <td class="model">
                            <?php echo $product['model']; ?>
                        </td>

                        <td class="stock">
                            <?php echo $product['stock']; ?>
                        </td>

                        <td class="price">
                            <?php if ($product['price']) { ?>
                            <div class="price">
                                <?php if (!$product['special']) { ?>
                                <?php echo $product['price']; ?>
                                <?php } else { ?>
                                <b><?php echo $product['special']; ?></b> <s><?php echo $product['price']; ?></s>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </td>

                        <td class="action" width="47">
                            <input data-toggle="tooltip" type="submit" class="icon-cart tooltip-item" value="&#xf07a;" onclick="addToCart('<?php echo $product['product_id']; ?>');" alt="<?php echo $button_cart; ?>" title="<?php echo $button_cart; ?>">
                            <a data-toggle="tooltip" href="<?php echo $product['remove']; ?>" class="icon-remove tooltip-item" title="<?php echo $button_remove; ?>">
                            </a>
                        </td>

                    </tr>
                </tbody>
                <?php } ?>
            </table>
        </div>

        <div class="buttons clearfix">
            <div class="pull-right">
                <a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
            </div>
        </div>
        <?php } else { ?>
        <div id="content" class="span9">
            <?php echo $text_empty; ?>
            <div class="buttons clearfix">
                <div class="pull-right">
                    <a href="<?php echo $continue; ?>" class="btn btn-primary">
                        <?php echo $button_continue; ?>
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>



        <?php echo $content_bottom; ?>

    </div> <!-- content span9 -->


    <?php echo $column_right; ?>

</div> <!-- row -->
<?php echo $footer; ?>
