<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<!-- Google Mapssss -->
   <script>
      var openedInfoWindow  =   null;
   
      function initialize() {
        //  Create Variables for our geocodes and markers
        <?php foreach($location as $locations) { ?>
        var myLatlng<?php echo $locations['location_id']; ?> = new google.maps.LatLng(<?php echo $locations['geocode']; ?>);
        <? } ?>
        
        var mapOptions = {
          zoom: 6,
          center: myLatlng<?php echo $location[0]['location_id']; ?>,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        
        var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
        
        var bound   =   new google.maps.LatLngBounds();
        
        <?php foreach($location as $locations) { ?>
        //  Loop through our Array and place all the markers onto the map
        var contentString7 = '<div id="address">'+
            '<h2><?php echo $locations['name']; ?></h2>'+
            '<div id="bodyContent">'+
            '<p> <?php echo $locations['address_1'] .   "<br />"; ?>' +
            '<?php echo $locations['address_2'] .   "<br />"; ?> '+
            '<?php echo $locations['city'] .   "<br />"; ?> '+
            '<?php echo $locations['postcode']  .   "<br />"; ?> '+
            '</div>'+
            '</div>';

        var infowindow<?php echo $locations['location_id']; ?> = new google.maps.InfoWindow({
            content: contentString7,
            maxWidth: 100
        });

        var marker<?php echo $locations['location_id']; ?> = new google.maps.Marker({
            position: myLatlng<?php echo $locations['location_id']; ?>,
            map: map,
            animation: google.maps.Animation.DROP,
            title: '<?php echo $locations['name']; ?>'
        });
        
        google.maps.event.addListener(marker<?php echo $locations['location_id']; ?>, 'click', function() {
          if(openedInfoWindow != null) openedInfoWindow.close();
          infowindow<?php echo $locations['location_id']; ?>.open(map,marker<?php echo $locations['location_id']; ?>);
          openedInfoWindow  =   infowindow<?php echo $locations['location_id']; ?>;
          google.maps.event.addListener(infowindow<?php echo $locations['location_id']; ?>, 'closeclick', function() {
              openedInfoWindow = null;
          });
        });
        
        //  Setting up bounds so that it extends the viewport
        bound.extend(marker<?php echo $locations['location_id']; ?>.getPosition());
        <?php } ?>    
        //  Fit map to bounds so you can see all the markers
        map.fitBounds(bound);    
      }
            
      function loadScript() {
        //  Script for loading up within document  
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' + 'callback=initialize';
        document.body.appendChild(script);
      }
      
      window.onload = loadScript;
    </script>
    
<!-- Google Mapssss -->    
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <h2><?php echo $text_location; ?></h2>
    <div class="contact-info">
      <div class="content">  
      <!--    Google Maps embed test  -->          
      <div id="map_canvas""></div>
      <br />
      <!-- Google Location Map Addresses -->
      <?php foreach($location as $locations) { ?>
      <div class="left">      
        <b><?php echo $locations['name']; ?></b><br />
           <?php echo $locations['address_1']; ?><br />
           <?php echo $locations['address_2']; ?><br />
           <?php echo $locations['city']; ?><br />
           <?php echo $locations['postcode'];  ?><br /><br /> 
           
           <?php // Only show opening times if they are set ?>
           <?php //if(!empty($locations['times'])) ?>
           <?php //{ ?>
           <b><?php echo $text_time; ?></b><br/>
           <?php //} ?>
           <?php echo $locations['times']; ?><br />
           <?php echo $locations['comment']; ?><br /><br /><br />     
      </div>          
      <?php  } ?> <!-- End of Location display -->
      </div>
    </div>
    </div>
    <h2><?php echo $text_contact; ?></h2>
    <div class="content">
    <b><?php echo $entry_name; ?></b><br />
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
      <div class="right"><input type="submit" value="<?php echo $button_continue; ?>" class="button" /></div>
    </div>
  </form>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>