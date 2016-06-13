<?php $translation_rows = 0; ?>
<?php foreach ($translations as $translation) { ?>
<fieldset>
  <legend><?php echo $translation['key']; ?></legend>
  <div class="table-responsive">
  <table id="translation" class="table table-bordered table-hover">
    <thead>
      <tr>
        <td><?php echo $entry_default; ?></td>
        <td><?php echo $entry_value; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><textarea class="form-control" disabled="disabled"><?php echo $translation['default']; ?></textarea></td>
        <td><textarea name="translation[<?php echo $translation_row; ?>][value]" class="form-control"><?php echo $translation['value']; ?></textarea></td>
      </tr>
    </tbody>
  </table>
</fieldset>
</div>
<?php $translation_rows++; ?>
<?php } ?>
<div class="pull-right">
  <button type="button" id="button-save" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-floppy-o"></i> <?php echo $button_save; ?></button>
</div>
<script type="text/javascript"><!--

//--></script> 