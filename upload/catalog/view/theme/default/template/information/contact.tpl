<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"> <?php echo $content_top; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <h1><?php echo $heading_title; ?></h1>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2><?php echo $text_location; ?></h2>
    <div class="contact-info">
      <div class="content">
        <div id="map"></div>
        <br />
        
        <?php foreach($locations as $location) { ?>
        <div class="left"><b><?php echo $location['name']; ?></b><br />
          <?php echo $location['address_1']; ?><br />
          <?php echo $location['address_2']; ?><br />
          <?php echo $location['city']; ?><br />
          <?php echo $location['postcode'];  ?><br />
          <br />
          
          <?php if ($location['open']) { ?>
          <b><?php echo $text_open; ?></b><br/>
          <?php echo $location['open']; ?><br />
          <br />
          <?php } ?>
          
          <?php if ($location['comment']) { ?>
          <?php echo $location['comment']; ?><br />
          <?php } ?>
          
          <br />
          <br />
        </div>
        <?php } ?>
      </div>
    </div>
    <h2><?php echo $text_contact; ?></h2>
    <div class="content"><b><?php echo $entry_name; ?></b><br />
      <input type="text" name="name" value="<?php echo $name; ?>" />
      <br />
      <?php if ($error_name) { ?>
      <span class="error"><?php echo $error_name; ?></span>
      <?php } ?>
      <br />
      <b><?php echo $entry_email; ?></b><br />
      <input type="text" name="email" value="<?php echo $email; ?>" />
      <br />
      <?php if ($error_email) { ?>
      <span class="error"><?php echo $error_email; ?></span>
      <?php } ?>
      <br />
      <b><?php echo $entry_enquiry; ?></b><br />
      <textarea name="enquiry" cols="40" rows="10" style="width: 99%;"><?php echo $enquiry; ?></textarea>
      <br />
      <?php if ($error_enquiry) { ?>
      <span class="error"><?php echo $error_enquiry; ?></span>
      <?php } ?>
      <br />
      <b><?php echo $entry_captcha; ?></b><br />
      <input type="text" name="captcha" value="<?php echo $captcha; ?>" />
      <br />
      <img src="index.php?route=information/contact/captcha" alt="" />
      <?php if ($error_captcha) { ?>
      <span class="error"><?php echo $error_captcha; ?></span>
      <?php } ?>
    </div>
    <div class="buttons">
      <div class="right">
        <input type="submit" value="<?php echo $button_continue; ?>" class="btn" />
      </div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&callback=initialize"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script type="text/javascript"><!--
var openedInfoWindow = null;

function initialize() {
	//  Create Variables for our geocodes and markers
	<?php foreach($locations as $location) { ?>
	var myLatlng<?php echo $location['location_id']; ?> = new google.maps.LatLng(<?php echo $location['geocode']; ?>);
	<?php } ?>
	
	var mapOptions = {
	  zoom: 6,
	  center: myLatlng<?php echo $location['location_id']; ?>,
	  mapTypeId: google.maps.MapTypeId.ROADMAP
	}
	
	var map = new google.maps.Map(document.getElementById('map'), mapOptions);
	
	var bound = new google.maps.LatLngBounds();
	
	<?php foreach($locations as $location) { ?>
	//  Loop through our Array and place all the markers onto the map
	var contentString<?php echo $location['location_id']; ?> = '<div id="address">' +
		'<h2><?php echo $location['name']; ?></h2>' +
		'<div id="bodyContent">'+
		'<p> <?php echo $location['address_1'] .   "<br />"; ?>' +
		'<?php echo $location['address_2'] .   "<br />"; ?> '+
		'<?php echo $location['city'] .   "<br />"; ?> '+
		'<?php echo $location['postcode']  .   "<br />"; ?> '+
		'</div>'+
		'</div>';
	
	var infowindow<?php echo $location['location_id']; ?> = new google.maps.InfoWindow({
		content: contentString<?php echo $location['location_id']; ?>,
		maxWidth: 100
	});
	
	var marker<?php echo $location['location_id']; ?> = new google.maps.Marker({
		position: myLatlng<?php echo $location['location_id']; ?>,
		map: map,
		animation: google.maps.Animation.DROP,
		title: '<?php echo addslashes($location['name']); ?>'
	});
	
	google.maps.event.addListener(marker<?php echo $location['location_id']; ?>, 'click', function() {
	  if (openedInfoWindow != null) openedInfoWindow.close();
	  
	  infowindow<?php echo $location['location_id']; ?>.open(map,marker<?php echo $location['location_id']; ?>);
	  
	  openedInfoWindow = infowindow<?php echo $location['location_id']; ?>;
	  
		google.maps.event.addListener(infowindow<?php echo $location['location_id']; ?>, 'closeclick', function() {
			openedInfoWindow = null;
		});
	});
	
	//  Setting up bounds so that it extends the viewport
	bound.extend(marker<?php echo $location['location_id']; ?>.getPosition());
	<?php } ?>  
  
	//  Fit map to bounds so you can see all the markers
	map.fitBounds(bound);    
}
//--></script> 
<?php echo $footer; ?>