<?php echo $header; ?>
<div class="container" xmlns="http://www.w3.org/1999/html">
  <header>
    <div class="row">
      <div class="col-sm-6">
        <h3><?php echo $heading_title; ?><br>
          <small><?php echo $text_openbay; ?></small></h3>
      </div>
      <div class="col-sm-6">
        <div id="logo" class="pull-right hidden-xs"><img src="view/image/logo.png" alt="OpenCart" title="OpenCart" /></div>
      </div>
    </div>
  </header>
  <div class="row">
    <div class="col-sm-12">
      <form>
        <fieldset class="core-modules">
          <div class="row">
            <div class="col-sm-6 text-center"><img src="view/image/ebay.png">
              <p><?php echo $text_ebay; ?></p>
              <a href="https://account.openbaypro.com/ebay/apiRegister?utm_source=opencart_install&utm_medium=register_button&utm_campaign=opencart_install" class="btn btn-primary" target="_blank"><?php echo $button_register; ?></a></div>
            <div class="col-sm-6 text-center"> <img src="view/image/amazon.png">
              <p><?php echo $text_amazon; ?></p>
              <a href="https://account.openbaypro.com/amazon/apiRegister?utm_source=opencart_install&utm_medium=register_button&utm_campaign=opencart_install" class="btn btn-primary" target="_blank"><?php echo $button_register_eu; ?></a> <a href="https://account.openbaypro.com/amazonus/apiRegister?utm_source=opencart_install&utm_medium=register_button&utm_campaign=opencart_install" class="btn btn-primary" target="_blank"><?php echo $button_register_us; ?></a></div>
          </div>
        </fieldset>
        <div class="buttons">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>