<?php
  $column_left = $load->controller('common/column_left');
  $column_right = $load->controller('common/column_right');
?>
<?= $load->controller('common/header') ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?= $breadcrumb['href'] ?>"><?= $breadcrumb['text'] ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?= $success ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= $error_warning ?></div>
  <?php } ?>
  <div class="row"><?= $column_left ?>
    <?php
      if ($column_left && $column_right) {
        $class = 'col-sm-6';
      } elseif ($column_left || $column_right) {
        $class = 'col-sm-9';
      } else {
        $class = 'col-sm-12';
      }
    ?>
    <div id="content" class="<?= $class ?>"><?= $load->controller('common/content_top') ?>
      <div class="row">
        <div class="col-sm-6">
          <div class="well">
            <h2><?= $lng->get('text_new_customer') ?></h2>
            <p><strong><?= $lng->get('text_register') ?></strong></p>
            <p><?= $lng->get('text_register_account') ?></p>
            <a href="<?= $url->link('account/register', '', 'SSL') ?>" class="btn btn-primary"><?= $lng->get('button_continue') ?></a></div>
        </div>
        <div class="col-sm-6">
          <div class="well">
            <h2><?= $lng->get('text_returning_customer') ?></h2>
            <p><strong><?= $lng->get('text_i_am_returning_customer') ?></strong></p>
            <form action="<?= $url->link('account/login', '', 'SSL') ?>" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label class="control-label" for="input-email"><?= $lng->get('entry_email') ?></label>
                <input type="text" name="email" value="<?= $request->post('email') ?>" placeholder="<?= $lng->get('entry_email') ?>" id="input-email" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-password"><?= $lng->get('entry_password') ?></label>
                <input type="password" name="password" value="<?= $request->post('password') ?>" placeholder="<?= $lng->get('entry_password') ?>" id="input-password" class="form-control" />
                <a href="<?= $url->link('account/forgotten', '', 'SSL') ?>"><?= $lng->get('text_forgotten') ?></a></div>
              <input type="submit" value="<?= $lng->get('button_login') ?>" class="btn btn-primary" />
              <?php if ($redirect) { ?>
              <input type="hidden" name="redirect" value="<?= $redirect ?>" />
              <?php } ?>
            </form>
          </div>
        </div>
      </div>
      <?= $load->controller('common/content_bottom') ?></div>
    <?= $load->controller('common/column_right') ?></div>
</div>
<?= $load->controller('common/footer') ?>