<?php if(!empty($productsonoffer)) { ?>
<style type="text/css">
.peroff {
	float: right;
    padding: 20px;
    color: #FFF;
    background-color: rgba(0, 0, 0, 0.4);
    margin-top: -95px;
    border-radius: 4px;
    border: 1px solid white;
}
.mainoffer img {
	max-height: 500px;
    width: auto;
    padding: 15px;
}
.otheroffers img {
	max-height: 250px;
	padding: 15px;
    width: auto;
}
.offersDiv {
	height: 530px;
}
.mainoffer,
.otheroffers{
	height: 500px;
}
.otheroffers div {
	height: 250px;
	margin: -5px;
	border: 1px solid rgba(207,207,207,.3);
}
.offersDiv img:hover {
	padding: 0px;
	transition-duration: 0.5s;
}
.loadoffershead h2{
	text-align: center;
	text-transform: uppercase;
}
</style>
<div class="container panel">
  <div class="loadoffershead center box-heading"><h2><?php echo $heading_title; ?></h2></div>
  <div class="title-divider-style-6"></div>
	<div class="panel-content" style="text-align: center;">
	  <div class="col-xs-12 offersDiv">
	  	<?php foreach($productsonoffer as $key => $product) { 
		  	$thumb = $product['image'];
		  	if(!empty($thumb)) {
		  		$thumb = 'image/'.$thumb;
		  	} else {
		  		$thumb = 'image/no_image.png';
		  	}
		  	$perc = ($product['realprice'] - $product['price'])/100;
		  	$href = 'index.php?route=product/product&product_id='.$product['product_id'];
		  	if($key == 0) { ?>
			  	<div class="mainoffer col-xs-12 col-sm-5" itemprop="offers" itemtype="http://schema.org/Offer">
			  		<a href="<?php echo $href ?>">
			  			<img class="col-xs-12 img-responsive" src="<?php echo $thumb ?>"/>
			  			<span class="peroff col-xs-12">
			  				<i class="fa fa-star fa-2x" aria-hidden=""><?php echo $perc. '% Off'; ?></i>
			  			</span>
			  		</a>
			  	</div>
			  	<div class="otheroffers col-xs-12 col-sm-7">
				  	<?php } else { 
				  	if($key <= $loadoffers_limit) { ?>
				  			<div class="col-xs-12 col-sm-4" itemprop="offers" itemtype="http://schema.org/Offer">
				  				<a href="<?php echo $href ?>">
						  			<img class="img-responsive" src="<?php echo $thumb ?>"/>
						  			<span class="peroff col-xs-12">
				  						<i class="fa fa-star" aria-hidden=""><?php echo $perc. '% Off'; ?></i>
				  					</span>
						  		</a>
				  			</div>
				  	<?php } } } ?>
		  		</div>
	  </div>
	</div>
</div>
<?php } ?>