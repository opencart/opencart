<div class="list-group">

<?php foreach ($categories as $category) { ?>
<?php if ($category['category_id'] == $category_id) { ?>
<a href="<?php echo $category['href']; ?>" class="list-group-item active"><?php echo $category['name']; ?></a>

	<?php if ($category['children']) { ?>
		<?php foreach ($category['children'] as $child) { ?>
		<?php if ($child['category_id'] == $child_id) { ?>
		<a href="<?php echo $child['href']; ?>" class="list-group-item active">&nbsp;&nbsp;+ <?php echo $child['name']; ?></a>
  
			<?php if ($child['sub_children']) { ?>
				<?php foreach ($child['sub_children'] as $sub_child) { ?>
					<?php if ($sub_child['category_id'] == $sub_child_id) { ?>
						<a href="<?php echo $sub_child['href']; ?>" class="list-group-item active">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <?php echo $sub_child['name']; ?></a>
					<?php } else { ?>
						<a href="<?php echo $sub_child['href']; ?>" class="list-group-item">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <?php echo $sub_child['name']; ?></a>
					<?php } ?>
				<?php } ?>
			<?php } ?>
				

		<?php } else { ?>
		<a href="<?php echo $child['href']; ?>" class="list-group-item">&nbsp;&nbsp;&nbsp;+ <?php echo $child['name']; ?></a>
		<?php } ?>
		<?php } ?>
	<?php } ?>
	
<?php } else { ?>
<a href="<?php echo $category['href']; ?>" class="list-group-item"><?php echo $category['name']; ?></a>
<?php } ?>

<?php } //foreach?>
</div>

