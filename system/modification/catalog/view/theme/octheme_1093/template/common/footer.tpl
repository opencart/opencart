<footer>
  <div class="container">
    <div class="row">
      <?php if ($informations) { ?>
      <div class="col-sm-12">
        
        <ul class="list-unstyled" style="list-style:none">
          <?php for($i=0;$i<count($informations);$i++) { ?>
          <?php  $information= $informations[$i]; ?>
    

          <li style="display:inline"><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php  if($i!=count($informations)-1) {?>
           &nbsp;&nbsp;|&nbsp;&nbsp;
          <?php  }?>

                <?php if($i==0) { ?>
           <li style="display:inline"><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>&nbsp;&nbsp;|&nbsp;&nbsp;
          <?php } ?>
          <?php } ?>
        
        </ul>
      </div>
      <?php } ?>



    </div>
   

            <div class="nav">
            <ul class="list-inline">
          <?php if ($followme_facebook) { ?><li><a href="http://facebook.com/<?php echo $followme_facebook; ?>"  target="_blank" title="<?php echo $text_follow_us; ?>&nbsp;<?php echo $name; ?>&nbsp;<?php echo $text_followmeon; ?>&nbsp;<?php echo $text_facebook; ?>"><i class="fa fa-facebook-square " style="color:#3B5B98;font-size:2em; "></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_facebook; ?></span></a></li><?php } ?>
          <?php if ($followme_twitter) { ?><li><a href="http://twitter.com/<?php echo $followme_twitter; ?>"  target="_blank" title="<?php echo $text_follow_us; ?>&nbsp;<?php echo $name; ?>&nbsp;<?php echo $text_followmeon; ?>&nbsp;<?php echo $text_twitter; ?>"><i class="fa fa-twitter" style="color:#4798D7;font-size:2em;"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_twitter; ?></span></a></li><?php } ?>
          <?php if ($followme_pinterest) { ?><li><a href="http://pinterest.com/<?php echo $followme_pinterest; ?>"  target="_blank" title="<?php echo $text_follow_us; ?>&nbsp;<?php echo $name; ?>&nbsp;<?php echo $text_followmeon; ?>&nbsp;<?php echo $text_pinterest; ?>"><i class="fa fa-pinterest" style="color:#BD2126;font-size:2em;"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_pinterest; ?></span></a></li><?php } ?>
          <?php if ($followme_google) { ?><li><a href="http://plus.google.com/<?php echo $followme_google; ?>"  target="_blank" title="<?php echo $text_follow_us; ?>&nbsp;<?php echo $name; ?>&nbsp;<?php echo $text_followmeon; ?>&nbsp;<?php echo $text_google; ?>"><i class="fa fa-google" style="color:#0057C8;font-size:2em;"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_google; ?></span></a></li><?php } ?>
          <?php if ($followme_linkedin) { ?><li><a href="http://linkedin.com/<?php echo $followme_linkedin; ?>"  target="_blank" title="<?php echo $text_follow_us; ?>&nbsp;<?php echo $name; ?>&nbsp;<?php echo $text_followmeon; ?>&nbsp;<?php echo $text_linkedin; ?>"><i class="fa fa-linkedin"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_linkedin; ?></span></a></li><?php } ?>
          </ul>
           
          </div>
            
             <div class="nav pull-right">
              <?php echo $powered; ?>
              </div>
    
  </div>
</footer>

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//--> 

<!-- Theme created by Welford Media for OpenCart 2.0 www.welfordmedia.co.uk -->

	
					<script src="catalog/view/javascript/backtotop/js/easing.js" type="text/javascript"></script>
					<script src="catalog/view/javascript/backtotop/js/jquery.ui.totop.min.js" type="text/javascript"></script>
					<script type="text/javascript">
						$(document).ready(function() {
							/*
							var defaults = {
								containerID: 'toTop', // fading element id
								containerHoverID: 'toTopHover', // fading element hover id
								scrollSpeed: 1200,
								easingType: 'linear' 
							};
							*/
							
							$().UItoTop({ easingType: 'easeOutQuart' });
							
						});
					</script>
				
</body></html>