<?php echo $header; ?>
<div class="container">
  <header>
    <div class="row">
      <div class="col-sm-6">
        <h3><?php echo $heading_openbay; ?><br><small><?php echo $heading_openbay_small; ?></small></h3>
      </div>
      <div class="col-sm-6">
        <div id="logo" class="pull-right hidden-xs">
          <img src="view/image/logo.png" alt="OpenCart" title="OpenCart" />
        </div>
      </div>
    </div>
  </header>
  <div class="row">
    <div class="col-sm-12">
      <div class="core-modules">
        <div class="row">
          <div class="col-sm-6 text-center">
            <img src="view/image/ebay.png">
            <p>eBay is a multi-billion dollar market place that allows business or private sellers to auction and retail goods online. Available to sellers worldwide.</p>
            <a href="https://account.openbaypro.com/ebay/apiRegister" class="btn btn-primary">Register</a>
          </div>
          <div class="col-sm-6 text-center">
            <img src="view/image/amazon.png">
            <p>Amazon Marketplace a fixed-price online marketplace allowing sellers to offer new and used items alongside Amazon's regular retail service.</p>
            <a href="https://account.openbaypro.com/ebay/apiRegister" class="btn btn-primary">Register EU</a>
            <a href="https://account.openbaypro.com/ebay/apiRegister" class="btn btn-primary">Register USA</a>
        </div>
      </div>
      <p class="text-center"><a href="http://www.openbaypro.com/features.php" target="_blank">Features</a> | <a href="http://www.openbaypro.com/plans.php" target="_blank">Fees</a> | <a href="http://help.welfordmedia.co.uk/" target="_blank">Support</a></p>
    </div>
    <div class="buttons">
      <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>