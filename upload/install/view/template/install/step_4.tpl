<?php echo $header; ?>
<div class="container">
  <header>
    <div class="row">
      <div class="col-sm-6">
        <h1 class="pull-left">4<small>/4</small></h1>
        <h3><?php echo $heading_title; ?><br>
          <small><?php echo $text_step_4; ?></small></h3>
      </div>
      <div class="col-sm-6">
        <div id="logo" class="pull-right hidden-xs"><img src="view/image/logo.png" alt="OpenCart" title="OpenCart" /></div>
      </div>
    </div>
  </header>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <div class="visit">
    <div class="row">
      <div class="col-sm-5 col-sm-offset-1 text-center">
        <p><i class="fa fa-shopping-cart fa-5x"></i></p>
        <a href="../" class="btn btn-secondary"><?php echo $text_catalog; ?></a></div>
      <div class="col-sm-5 text-center">
        <p><i class="fa fa-cog fa-5x white"></i></p>
        <a href="../admin/" class="btn btn-secondary"><?php echo $text_admin; ?></a></div>
    </div>
  </div>
  <div class="modules">
    <div id="extensions" class="row"><div class="col-sm-6 module">  
	<a href="https://liveopencart.ru/opencart-moduli-shablony/shablonyi/2-x-revolution-adaptivnyiy-universalnyiy-shablon" target="_BLANK" class="thumbnail pull-left">
	<img alt="Revolution. Адаптивный универсальный шаблон" src="/install/view/image/revolution.png"></a>  
	<h5>Revolution. Адаптивный универсальный шаблон</h5>  <p><a href="https://liveopencart.ru/opencart-moduli-shablony/shablonyi/2-x-revolution-adaptivnyiy-universalnyiy-shablon" target="_BLANK">Подробнее</a></p>  
	<div class="clearfix"></div></div><div class="col-sm-6 module">  
	<a href="https://liveopencart.ru/opencart-moduli-shablony/shablonyi/newstore-universalnyiy-adaptivnyiy-shablon" target="_BLANK" class="thumbnail pull-left">
	<img alt="NewStore - универсальный, адаптивный шаблон" src="/install/view/image/newstore.png"></a>
	<h5>NewStore - универсальный, адаптивный шаблон</h5>
	<p><a href="https://liveopencart.ru/opencart-moduli-shablony/shablonyi/newstore-universalnyiy-adaptivnyiy-shablon" target="_BLANK">Подробнее</a></p>  
	<div class="clearfix"></div></div><div class="col-sm-6 module">  <a href="http://simpleopencart.com/" target="_BLANK" class="thumbnail pull-left">
	<img alt="Простая регистрация и заказ Simple" src="/install/view/image/simple.png"></a>  <h5>Простая регистрация и заказ Simple</h5>  <p><a href="http://simpleopencart.com/" target="_BLANK">Подробнее</a></p>  
	<div class="clearfix"></div></div></div>
    <div class="row">
      <div class="col-sm-12 text-center"><a href="https://liveopencart.ru/" target="_BLANK" class="btn btn-default"><?php echo $text_extension; ?></a></div>
    </div>
  </div>
  <div class="mailing">
    <div class="row">
      <div class="col-sm-12"><i class="fa fa-gavel fa-5x"></i>
        <h3><?php echo $text_mail; ?><br>
          <small><?php echo $text_mail_description; ?></small></h3>
        <a href="http://opencart.pro/discount/" target="_BLANK" class="btn btn-secondary"><?php echo $button_mail; ?></a></div>
    </div>
  </div>
  <div class="support text-center">
    <div class="row">
      <div class="col-sm-4"><a href="https://vk.com/club65930646" class="icon transition"><i class="fa fa-vk fa-4x"></i></a>
        <h3><?php echo $text_facebook; ?></h3>
        <p><?php echo $text_facebook_description; ?></p>
        <a href="https://vk.com/club65930646"><?php echo $text_facebook_visit; ?></a></div>
      <div class="col-sm-4"><a href="http://forum.opencart.pro" class="icon transition"><i class="fa fa-comments fa-4x"></i></a>
        <h3><?php echo $text_forum; ?></h3>
        <p><?php echo $text_forum_description; ?></p>
        <a href="http://forum.opencart.pro"><?php echo $text_forum_visit; ?></a></div>
      <div class="col-sm-4"><a href="http://opencart.pro/blog/" class="icon transition"><i class="fa fa-user fa-4x"></i></a>
        <h3><?php echo $text_commercial; ?></h3>
        <p><?php echo $text_commercial_description; ?></p>
        <a href="http://opencart.pro/blog/" target="_BLANK"><?php echo $text_commercial_visit; ?></a></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
<script type="text/javascript"><!--
$(document).ready(function() {
	$.ajax({
		url: '<?php echo $extension; ?>',
		type: 'post',
		dataType: 'json',
		success: function(json) {
			if (json['extensions']) {
				html  = '';

				for (i = 0; i < json['extensions'].length; i++) {
					extension = json['extensions'][i];

					html += '<div class="col-sm-6 module">';
					html += '  <a class="thumbnail pull-left" href="' + extension['href'] + '"><img src="' + extension['image'] + '" alt="' + extension['name'] + '" /></a>';
					html += '  <h5>' + extension['name'] + '</h5>';
					html += '  <p>' + extension['price'] + ' <a target="_BLANK" href="' + extension['href'] + '"><?php echo $text_view; ?></a></p>';
					html += '  <div class="clearfix"></div>';
					html += '</div>';

					i++;
				}

				$('#extension').html(html);
			} else {
				$('#extension').fadeOut();
			}
		}
	});
});
//--></script>
