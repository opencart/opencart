<div id="stats">
  <ul>
    <li>
      <div><?php echo $text_complete_status; ?> <span class="pull-right"><?php echo $complete_status; ?>%</span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $complete_status; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
      </div>
    </li>
    <li>
      <div><?php echo $text_processing_status; ?> <span class="pull-right"><?php echo $processing_status; ?>%</span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $processing_status; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
      </div>
    </li>
    <li>
      <div><?php echo $text_other_status; ?> <span class="pull-right"><?php echo $other_status; ?>%</span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="<?php echo $other_status; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
      </div>
    </li>
  </ul>
</div>
