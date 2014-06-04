<div class="tab-pane" id="tab-openbay">
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
      <tr>
        <td class="text-left"><?php echo $text_marketplace; ?></td>
        <td class="text-center"><?php echo $text_status; ?></td>
        <td class="text-right"><?php echo $text_option; ?></td>
      </tr>
      </thead>
      <tbody>
      <tr>
        <?php foreach ($markets as $market) { ?>
          <tr>
            <td class="text-left"><?php echo $market['name']; ?></td>
            <td class="text-center">
              <?php if ($market['status'] == 0) { ?>
                <span class="label label-warning"><?php echo $market['status_text']; ?></span>
              <?php } ?>
              <?php if ($market['status'] == 1) { ?>
                <span class="label label-success"><?php echo $market['status_text']; ?></span>
              <?php } ?>
              <?php if ($market['status'] == 2) { ?>
                <span class="label label-info"><?php echo $market['status_text']; ?></span>
              <?php } ?>
              <?php if ($market['status'] == 3) { ?>
                <span class="label label-danger"><?php echo $market['status_text']; ?></span>
              <?php } ?>
            </td>
            <td class="text-right">
              <?php if ($market['button_link'] != '') { ?>
                <a href="<?php echo $market['button_link']; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i> <?php echo $market['button_text']; ?></a>
              <?php } ?>
            </td>
          </tr>
        <?php } ?>
      </tr>
      </tbody>
    </table>
  </div>
</div>