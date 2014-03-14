<?php echo $header; ?>

      <h1><?php echo $heading_title; ?></h1>
      <?php if ($profiles) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_profile_id; ?></td>
              <td class="text-left"><?php echo $column_created; ?></td>
              <td class="text-left"><?php echo $column_status; ?></td>
              <td class="text-left"><?php echo $column_product; ?></td>
              <td class="text-right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($profiles as $profile) { ?>
              <tr>
                <td class="text-left">#<?php echo $profile['id']; ?></td>
                <td class="text-left"><?php echo $profile['created']; ?></td>
                <td class="text-left"><?php echo $status_types[$profile['status']]; ?></td>
                <td class="text-left"><?php echo $profile['name']; ?></td>
                <td class="text-right"><a href="<?php echo $profile['href']; ?>"><img src="catalog/view/theme/default/image/info.png" alt="<?php echo $button_view; ?>" title="<?php echo $button_view; ?>" /></a></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>

<?php echo $footer; ?>