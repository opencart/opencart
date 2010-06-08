<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/review.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'pd.name') { ?>
              <a href="<?php echo $sort_product; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_product; ?>"><?php echo $column_product; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'r.author') { ?>
              <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
              <?php } ?></td>
            <td class="right"><?php if ($sort == 'r.rating') { ?>
              <a href="<?php echo $sort_rating; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_rating; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_rating; ?>"><?php echo $column_rating; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'r.status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?></td>
            <td class="left"><?php if ($sort == 'r.date_added') { ?>
              <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($reviews) { ?>
          <?php foreach ($reviews as $review) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($review['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $review['review_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $review['name']; ?></td>
            <td class="left"><?php echo $review['author']; ?></td>
            <td class="right"><?php echo $review['rating']; ?></td>
            <td class="left"><?php echo $review['status']; ?></td>
            <td class="left"><?php echo $review['date_added']; ?></td>
            <td class="right"><?php foreach ($review['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<?php echo $footer; ?>