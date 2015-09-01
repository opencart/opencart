<div class="list-group">
<?php if ($manufacturers) { ?>
    <ul class="nav nav-tabs nav-stacked">
          <?php foreach ($manufacturers as $manufacturer) { ?>
			<li>
            	<a href="<?php echo $manufacturer['href']; ?>" target="_blank" title="<?php echo $manufacturer['name']; ?>">
                	<?php echo $manufacturer['name']; ?>
                </a>
            </li>
		<?php } ?>
    </ul>
    <?php } ?>
</div>
