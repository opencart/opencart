<div id="showcase">
 <div class="container gallery clearfix">
  <?php foreach ($products as $product) { ?>
  <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
  <figure>
    <div class="category-thumb transition">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
    <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <p>
        <?php echo $product['description']; ?><br/>
        <a href="<?php echo $product['href']; ?>"><?php echo $text_view; ?><span><i class="fa fa-arrow-circle-o-right "></i></span></a></p>
      </div>
    </div>
    </figure>
   </div>
  <?php } ?>
 </div>
</div>

<link rel="stylesheet" href="catalog/view/javascript/parallax/css/style.css" type="text/css">
    <script type="text/javascript" src="catalog/view/javascript/parallax/js/greensock/TweenMax.min.js"></script>
  	<script src="catalog/view/javascript/parallax/js/jquery.lettering-0.6.1.min.js"></script>
	<script src="catalog/view/javascript/parallax/js/jquery.superscrollorama.js"></script>

    	<script>
		$(document).ready(function() {
			// set rotation of flash
			TweenMax.set("#newversion", {rotation: 15});

			$('body').css('visibility','visible');

			// hide content until after title animation
			$('#content-wrapper').css('display','none');

			// lettering.js to split up letters for animation
			$('#title-line1').lettering();
			$('#title-line2').lettering();
			$('#title-line3').lettering();

			// TimelineLite for title animation, then start up superscrollorama when complete
			(new TimelineLite({onComplete:initScrollAnimations}))
				.from( $('#title-line1 span'), .4, {delay: 1, css:{right:'1000px'}, ease:Back.easeOut})
				.from( $('#title-line2'), .4, {css:{top:'1000px',opacity:'0'}, ease:Expo.easeOut})
				.append([
					TweenMax.from( $('#title-line3 .char1'), .25+Math.random(), {css:{top: '-200px', right:'1000px'}, ease:Elastic.easeOut}),
					TweenMax.from( $('#title-line3 .char2'), .25+Math.random(), {css:{top: '300px', right:'1000px'}, ease:Elastic.easeOut}),
					TweenMax.from( $('#title-line3 .char3'), .25+Math.random(), {css:{top: '-400px', right:'1000px'}, ease:Elastic.easeOut}),
					TweenMax.from( $('#title-line3 .char4'), .25+Math.random(), {css:{top: '-200px', left:'1000px'}, ease:Elastic.easeOut}),
					TweenMax.from( $('#title-line3 .char5'), .25+Math.random(), {css:{top: '200px', left:'1000px'}, ease:Elastic.easeOut})
				])
				.from("#newversion", .4, {scale: 5, autoAlpha: 0, ease: Elastic.easeOut})
				.to( $('#title-info'), .5, {css:{opacity:.99, 'margin-top':0}, delay:-1, ease:Quad.easeOut});

			function initScrollAnimations() {
				$('#content-wrapper').css('display','block');
				var controller = $.superscrollorama();


				// showcase tweens
				controller.addTween('#showcase h1', TweenMax.from( $('#showcase h1'), .75, {css:{letterSpacing:20,opacity:0}, ease:Quad.easeOut}));
				controller.addTween('#showcase p', TweenMax.from( $('#showcase p'), 1, {css:{opacity:0}, ease:Quad.easeOut}));
				$('#showcase .gallery figure').css('position','relative').each(function() {
					controller.addTween('#showcase .gallery', TweenMax.from( $(this), 1, {delay:Math.random()*.2,css:{left:Math.random()*200-100,top:Math.random()*200-100,opacity:0}, ease:Back.easeOut}));
				});

			}
		});
	</script>