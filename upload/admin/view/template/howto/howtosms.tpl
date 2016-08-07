<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
    <style>
  body {font-size: 14px;}
  p {
  font-size: 16px;
  margin-bottom: 30px;
  margin-top: 30px;
  text-align: center;
  text-indent: 30px;
  }
  .content > img {width: 100%;}
  </style>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-comment-o fa-fw"></i> Прежде чем настраивать SMS уведомления о новых заказах необходимо зарегистрироваться в сервисе <a style="color:#1e91cf;" href="http://smsc.ru/?ppocshop" target="_blank">smsc.ru</a></h3>
      </div>
      <div class="content">
	  <img src="view/image/howto/smsc.png" width="1180" />
	  <p>В том случае если у вас остались вопросы задавайте их на нашем форуме.</p>
    </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>