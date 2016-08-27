<div class="list-group">
      <?php foreach ($categories as $category) { ?>
        <?php if ($category['category_id'] == $category_id) { ?>
        <a href="<?php echo $category['href']; ?>" class="list-group-item active"><?php echo $category['name']; ?></a>
        <?php } else { ?>
        <a href="<?php echo $category['href']; ?>" class="list-group-item"><?php echo $category['name']; ?></a>
        <?php } ?>
        <?php if (($category['children']) && ($category['category_id'] == $category_id)) { ?>
          <?php foreach ($category['children'] as $child) { ?>
            <?php if ($child['category_id'] == $child_id) { ?>
            <a href="<?php echo $child['href']; ?>" class="list-group-item active">&nbsp;- <?php echo $child['name']; ?></a>
            <?php } else { ?>
            <a href="<?php echo $child['href']; ?>" class="list-group-item">&nbsp;- <?php echo $child['name']; ?></a>
            <?php } ?>
			        <?php if (($child['children2']) &&  ($category['category_id'] == $category_id)) { ?>
						  <?php foreach ($child['children2'] as $child2) { ?>
							<?php if ($child2['category_id'] == $child_id2) { ?>
							<a href="<?php echo $child2['href']; ?>" class="list-group-item active">&nbsp;&nbsp;&nbsp;- <?php echo $child2['name']; ?></a>
							<?php } else { ?>
							<a href="<?php echo $child2['href']; ?>" class="list-group-item">&nbsp;&nbsp;&nbsp;- <?php echo $child2['name']; ?></a>
							<?php } ?>
								<?php if (($child2['children3']) &&  ($category['category_id'] == $category_id)) { ?>
									  <?php foreach ($child2['children3'] as $child3) { ?>
										<?php if ($child3['category_id'] == $child_id3) { ?>
										<a href="<?php echo $child3['href']; ?>" class="list-group-item active">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <?php echo $child3['name']; ?></a>
										<?php } else { ?>
										<a href="<?php echo $child3['href']; ?>" class="list-group-item">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <?php echo $child3['name']; ?></a>
										<?php } ?>
									  <?php } ?>
								 <?php } ?>
						  <?php } ?>
					 <?php } ?>
          <?php } ?>
        <?php } ?>
      <?php } ?>
</div>
