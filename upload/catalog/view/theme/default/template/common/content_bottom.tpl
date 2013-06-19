<?php if ($modules) {
  foreach ($modules as $module) {
    $class_left = '<div class="';
	
    if ($module['responsive']['phone']) {
       $class_left .= 'visible-phone';
    } else {
       $class_left .= 'hidden-phone';
    }
    
    $class_left .= ' ';
    
    if ($module['responsive']['tablet']) {
       $class_left .= 'visible-tablet';
    } else {
       $class_left .= 'hidden-tablet';
    }
    
    $class_left .= ' ';
    
    if ($module['responsive']['desktop']) {
       $class_left .= 'visible-desktop';
    } else {
       $class_left .= 'hidden-desktop';
    }
	
    $class_left .= '">';

    $class_right = '</div>';
	
    echo $class_left . $module['content'] . $class_right;
  
  }
} ?>