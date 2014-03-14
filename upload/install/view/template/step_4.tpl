<?php echo $header; ?>
<div class="container">
  <header>
      <div class="row">
        <div class="col-sm-6">
          <h1 class="pull-left">4<small>/4</small></h1>
          <h3><?php echo $heading_step_4; ?><br><small><?php echo $heading_step_4_small; ?></small></h3>
        </div>
        <div class="col-sm-6">
          <div id="logo" class="pull-right hidden-xs">
            <img src="view/image/logo.png" alt="OpenCart" title="OpenCart" />
          </div>
        </div>
      </div>
  </header>
  <div class="alert alert-danger"><?php echo $text_forget; ?></div>
  <!-- Visit -->
  <div class="visit">
    <div class="row">
      <div class="col-sm-5 col-sm-offset-1 text-center">
        <img src="/install/view/image/icon-store.png">
        <a class="btn btn-secondary" href="../"><?php echo $text_shop; ?></a>
      </div>
      <div class="col-sm-5 text-center">
        <img src="/install/view/image/icon-admin.png">
        <a class="btn btn-secondary" href="../admin/"><?php echo $text_login; ?></a>
      </div>
    </div>
  </div>
  <!-- Language -->
  <div class="language" id="module-language" style="display:none;"></div>
  <!-- Modules -->
  <div class="modules">
    <div class="row" id="modules-loading">
      <div class="col-sm-12">
        <h2 class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading modules...</h2>
      </div>
    </div>
    <div class="row" id="modules" style="display:none;"></div>
    <div class="row">
      <div class="col-sm-12 text-center">
        <a class="btn btn-default" href="http://www.opencart.com/index.php?route=extension/extension" target="_BLANK">visit the extensions store</a>
      </div>
    </div>
  </div>
  <!-- Mailing list -->
  <div class="mailing-list">
    <div class="row">
      <div class="col-sm-12">
        <img src="/install/view/image/icon-mail.png">
        <h3>Join the mailing list<br><small>Stay informed of OpenCart updates and events.</small></h3>
        <a class="btn btn-secondary" href="http://newsletter.opencart.com/h/r/B660EBBE4980C85C" target="_BLANK">Join our mailing list</a>
      </div>
    </div>
  </div>
  <!-- Core Modules -->
  <div class="core-modules">
    <div class="row">
      <div class="col-sm-12">
        <h2 class="text-center">Core Modules</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 text-center">
        <img src="/install/view/image/openbay_pro.gif">
        <p>OpenBay Pro gives merchants the ability to link their store with 3rd party markets like eBay and Amazon. Import orders, list items and handle shipping information direct from OpenCart... <a href="http://www.openbaypro.com/">More info</a></p>
        <a class="btn btn-primary" href="#">Setup now</a>
      </div>
      <div class="col-sm-6 text-center">
        <img src="/install/view/image/maxmind.gif">
        <p>MaxMind provides merchants the ability to identify risky transactions quickly, reducing the risk of fraud and minimises the time spent reviewing orders by giving a risk score for each one... <a href="http://www.maxmind.com/">More info</a></p>
        <a class="btn btn-primary" href="#">Setup now</a>
      </div>
    </div>
  </div>
  <!-- Support -->
  <div class="support text-center">
    <div class="row">
      <div class="col-sm-4">
        <a href="#" class="icon transition">
          <i class="fa fa-facebook fa-4x"></i>
        </a>
        <h3>Like Us On Facebook</h3>
        <p>Tell us how much you like OpenCart!</p>
        <a href="#">Visit our Facebook page</a>
      </div>
      <div class="col-sm-4">
        <a href="http://forum.opencart.com/" class="icon transition">
          <i class="fa fa-comments fa-4x"></i>
        </a>
        <h3>Community Forums</h3>
        <p>Give and receive help to other OpenCart users</p>
        <a href="http://forum.opencart.com/">Visit our forums</a>
      </div>
      <div class="col-sm-4">
        <a href="http://www.opencart.com/index.php?route=partner/partner" class="icon transition">
          <i class="fa fa-user fa-4x"></i>
        </a>
        <h3>Commercial Support</h3>
        <p>Development services from OpenCart partners</p>
        <a href="http://www.opencart.com/index.php?route=partner/partner" target="_BLANK">Visit our partner page</a>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript"><!--
function searchExtensions() {
  var html = '';

  $.ajax({
    url: 'index.php?route=step_4/extensions',
    type: 'post',
    dataType: 'json',
    beforeSend: function(){
      $('#modules-loading').show();
      $('#modules').empty().hide();
    },
    success: function(json) {
      $.each (json.extensions, function(key, val) {
        html = '<div class="col-sm-6 module">';
        html += '<a class="thumbnail pull-left" href="'+val.href+'"><img src="'+val.image+'"></a>';
        html += '<h5>'+val.name+'</h5>';
        html += '<p>'+val.price+' <a target="_BLANK" href="'+val.href+'">View details</a></p>';
        html += '<div class="clearfix"></div>';
        html += '</div>';

        $('#modules').append(html);
      });

      $('#modules').fadeIn();
      $('#modules-loading').hide();
    },
    failure: function(){
      $('#modules-loading').hide();
    },
    error: function(){
      $('#modules-loading').hide();
    }
  });
}
function searchLanguages() {
  var html = '';

  $.ajax({
    url: 'index.php?route=step_4/language',
    type: 'post',
    data: {'language' : '<?php echo $language; ?>' },
    dataType: 'json',
    beforeSend: function(){
      $('#module-language').empty().hide();
    },
    success: function(json) {
      if (json.extension != '') {
        html += '<div class="row">';
          html += '<div class="col-sm-12">';
            html += '<img class="img-rounded" src="'+json.extension.image+'">';
            html += '<h3>'+json.extension.name+'<br><small>Downloads: '+json.extension.downloaded+', Price: '+json.extension.price+'</small></h3>';
          html += '<a class="btn btn-primary" href="'+json.extension.href+'" target="_BLANK">Download</a>';
          html += '</div>';
        html += '</div>';

        $('#module-language').html(html).fadeIn();
      }
    },
    failure: function(){ },
    error: function(){ }
  });
}
$( document ).ready(function() {
  searchExtensions();
  searchLanguages();
});
//--></script>