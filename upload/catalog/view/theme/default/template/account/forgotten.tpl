<?php echo $header; ?>

<!-- Breadcrumb -->
<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    
    <li>
        <a href="<?php echo $breadcrumb['href']; ?>">
            <?php echo $breadcrumb['text']; ?>
        </a>
    </li>
    <?php } ?>
</ul>

<?php if ($error_warning) { ?>
<div class="alert alert-error"><?php echo $error_warning; ?></div>
<?php } ?>

<div class="row">
    <?php echo $column_left; ?>
    <div class="span9">
        <div id="content">
          <?php echo $content_top; ?>

          <h1><?php echo $heading_title; ?></h1>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
            <p><?php echo $text_email; ?></p>
            <h3><?php echo $text_your_email; ?></h3>
            <div class="content">
              <table class="form">
                <tr>
                  <td><?php echo $entry_email; ?></td>
                  <td><input type="text" name="email" value="" /></td>
              </tr>
          </table>
      </div>
      <div class="buttons clearfix">
          <div class="pull-left">
            <a href="<?php echo $back; ?>" class="btn"><?php echo $button_back; ?></a>
          </div>
          <div class="pull-right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
        </div>
    </div>
</form>
<?php echo $content_bottom; ?>
</div>
</div>
<?php echo $column_right; ?>
</div>

<?php echo $footer; ?>