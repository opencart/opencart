<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></div>
</div>
<div class="tabs"><a tab="#tab_general"><?php echo $tab_general; ?></a></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div id="tab_general" class="page"> <span class="required">*</span> <?php echo $entry_author; ?><br />
    <input type="text" name="author" value="<?php echo $author?>" />
    <br />
    <?php if ($error_author) { ?>
    <span class="error"><?php echo $error_author; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_product; ?><br />
    <select name="product_id">
      <?php foreach ($products as $product) { ?>
      <?php if ($product['product_id'] == $product_id) { ?>
      <option value="<?php echo $product['product_id']; ?>" selected="selected"><?php echo $product['name']; ?></option>
      <?php } else { ?>
      <option value="<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></option>
      <?php } ?>
      <?php } ?>
    </select>
    <br />
    <br />
    <span class="required">*</span> <?php echo $entry_text; ?><br />
    <textarea name="text" cols="60" rows="8"><?php echo $text; ?></textarea>
    <br />
    <?php if ($error_text) { ?>
    <span class="error"><?php echo $error_text; ?></span>
    <?php } ?>
    <br />
    <?php echo $entry_rating; ?><br />
    <b class="rating"><?php echo $entry_bad; ?></b>&nbsp;
    <?php if ($rating == 1) { ?>
    <input type="radio" name="rating" value="1" checked />
    <?php } else { ?>
    <input type="radio" name="rating" value="1" />
    <?php } ?>
    &nbsp;
    <?php if ($rating == 2) { ?>
    <input type="radio" name="rating" value="2" checked />
    <?php } else { ?>
    <input type="radio" name="rating" value="2" />
    <?php } ?>
    &nbsp;
    <?php if ($rating == 3) { ?>
    <input type="radio" name="rating" value="3" checked />
    <?php } else { ?>
    <input type="radio" name="rating" value="3" />
    <?php } ?>
    &nbsp;
    <?php if ($rating == 4) { ?>
    <input type="radio" name="rating" value="4" checked />
    <?php } else { ?>
    <input type="radio" name="rating" value="4" />
    <?php } ?>
    &nbsp;
    <?php if ($rating == 5) { ?>
    <input type="radio" name="rating" value="5" checked />
    <?php } else { ?>
    <input type="radio" name="rating" value="5" />
    <?php } ?>
    &nbsp; <b class="rating"><?php echo $entry_good; ?></b><br />
    <br />
    <?php echo $entry_status; ?><br />
    <select name="status">
      <?php if ($status) { ?>
      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
      <option value="0"><?php echo $text_disabled; ?></option>
      <?php } else { ?>
      <option value="1"><?php echo $text_enabled; ?></option>
      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
      <?php } ?>
    </select>
  </div>
</form>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>