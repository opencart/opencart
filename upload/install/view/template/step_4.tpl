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
  <div class="language">
    <div class="row">
      <div class="col-sm-12">
        <img class="img-rounded" src="http://placehold.it/120x50">
        <h3>Install Language Pack<br><small>Lorem ipsum dolor sit amet.</small></h3>
        <a class="btn btn-primary" href="#">Install Language pack</a>
      </div>
    </div>
  </div>
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
        <a class="btn btn-default" href="#">visit the extensions store</a>
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
        <h2 class="text-center">Setup Core Modules</h2>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 text-center">
        <img class="img-circle" src="http://placehold.it/100x100">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis posuere pharetra ante, non malesuada ante aliquet id... <a href="#">More info</a></p>
        <a class="btn btn-primary" href="#">Install now</a>
      </div>
      <div class="col-sm-6 text-center">
        <img class="img-circle" src="http://placehold.it/100x100">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis posuere pharetra ante, non malesuada ante aliquet id... <a href="#">More info</a></p>
        <a class="btn btn-primary" href="#">Install now</a>
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
        <p>Lorem ipsum dolor sit amet.</p>
        <a href="#">Visit our Facebook page</a>
      </div>
      <div class="col-sm-4">
        <a href="#" class="icon transition">
          <i class="fa fa-comments fa-4x"></i>
        </a>
        <h3>Community Forums</h3>
        <p>Lorem ipsum dolor sit amet.</p>
        <a href="#">Visit our forums</a>
      </div>
      <div class="col-sm-4">
        <a href="#" class="icon transition">
          <i class="fa fa-user fa-4x"></i>
        </a>
        <h3>Commercial Support</h3>
        <p>Lorem ipsum dolor sit amet.</p>
        <a href="#">Visit our support site</a>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript"><!--
function loadExtensions(){
  var html = '';
  var html_heading = '';

  $.ajax({
    url: 'index.php?route=step_4/extensions',
    type: 'post',
    dataType: 'json',
    beforeSend: function(){
      $('#modules-loading').show();
      $('#modules').empty().hide;
    },
    success: function(json) {
      $.each (json.extensions, function(key, val) {
        html = '<div class="col-sm-6 module">';
        html += '<a class="thumbnail pull-left" href="'+val.href+'"><img src="'+val.image+'"></a>';
        html += '<h4>'+val.name+'</h4>';
        html += '<p>'+val.price+' <a target="_BLANK" href="'+val.href+'">View details</a></p>';
        html += '<div class="clearfix"></div>';
        html += '</div>';

        $('#modules').append(html);
      });

      $('#modules').prepend('<div class="row"><div class="col-sm-12"><h2 class="text-center">Top OpenCart Modules</h2></div></div>');
      $('#modules').fadeIn();
      $('#modules-loading').hide();
    },
    failure: function(){ },
    error: function(){ }
  });
}
$( document ).ready(function() {
  loadExtensions();
});
//--></script>