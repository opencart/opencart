<?php if (count($currencies) > 1) { ?>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div class="btn-group currency-dropdown">
        <a id="currency-drop" class="btn btn-link dropdown-toggle" role="button" data-toggle="dropdown" href="#">
            <?php foreach ($currencies as $currency) { ?>
            <?php if ($currency['symbol_left'] && $currency['code'] == $currency_code) { ?>
            <strong id="currency-choice"><?php echo $currency['symbol_left']; ?></strong>
            <?php } elseif ($currency['code'] == $currency_code) { ?>
            <strong id="currency-choice"><?php echo $currency['symbol_right']; ?></strong>
            <?php } ?>
            <?php } ?>
            <span class="hidden-phone hidden-tablet"><?php echo $text_currency; ?></span>
            <i class="icon-caret-down"></i>
        </a>
        <ul class="dropdown-menu animated fadeIn" role="menu" aria-labelledby="currency-drop" id="currency-menu">
                <?php foreach ($currencies as $currency) { ?>
                <?php if ($currency['code'] == $currency_code) { ?>
                <?php if ($currency['symbol_left']) { ?>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" title="<?php echo $currency['title']; ?>"><?php echo $currency['symbol_left']; ?> <?php echo $currency['title']; ?></a>
                </li>
                <?php } else { ?>
                <li role="presentation">                     
                    <a role="menuitem" tabindex="-1" title="<?php echo $currency['title']; ?>"><?php echo $currency['symbol_right']; ?> <?php echo $currency['title']; ?></a>
                </li>
                <?php } ?>
                <?php } else { ?>
                <?php if ($currency['symbol_left']) { ?>
                <li role="presentation">
                    <a role="menuitem" tabindex="-1" onclick="$('input[name=\'currency_code\']').attr('value', '<?php echo $currency['code']; ?>'); $(this).parent().parent().parent().parent().submit();" title="<?php echo $currency['title']; ?>"><?php echo $currency['symbol_left']; ?> <?php echo $currency['title']; ?></a>
                </li>
                <?php } else { ?>
                <li role="presentation"> 
                    <a role="menuitem" tabindex="-1" onclick="$('input[name=\'currency_code\']').attr('value', '<?php echo $currency['code']; ?>'); $(this).parent().parent().parent().parent().submit();" title="<?php echo $currency['title']; ?>"><?php echo $currency['symbol_right']; ?> <?php echo $currency['title']; ?></a>
                </li>
                <?php } ?>
                <?php } ?>
                <?php } ?>
        </ul>
                <input type="hidden" name="currency_code" value="" />
            <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
    </div>
</form>

<?php } ?>