<ul id="menu">
  <?php foreach ($navigations as $nav) { ?>
    <li id="<?php echo !empty($nav['id']) ? $nav['id'] : ''; ?>">
      <?php if (!empty($nav['icon'])) { ?>
        <a <?php echo !empty($nav['url']) ? 'href="' . $nav['url'] . '"' : ''; ?> class="<?php echo !empty($nav['child']) ? 'parent' : ''; ?> <?php echo !empty($nav['class']) ? $nav['class'] : ''; ?>"><i class="fa fa-<?php echo $nav['icon']; ?> fa-fw"></i> <span><?php echo $nav['title']; ?></span></a>
      <?php } else { ?>
        <a <?php echo !empty($nav['url']) ? 'href="' . $nav['url'] . '"' : ''; ?> class="<?php echo !empty($nav['child']) ? 'parent' : ''; ?> <?php echo !empty($nav['class']) ? $nav['class'] : ''; ?>"><?php echo $nav['title']; ?></a>
      <?php } ?>

      <?php if (!empty($nav['child'])) { ?>
        <ul>
          <?php foreach ($nav['child'] as $subnav) { ?>
            <li id="<?php echo !empty($subnav['id']) ? $subnav['id'] : ''; ?>">
              <?php if (!empty($subnav['icon'])) { ?>
                <a <?php echo !empty($subnav['url']) ? 'href="' . $subnav['url'] . '"' : ''; ?> class="<?php echo !empty($subnav['child']) ? 'parent' : ''; ?> <?php echo !empty($subnav['class']) ? $subnav['class'] : ''; ?>"><i class="fa fa-<?php echo $subnav['icon']; ?> fa-fw"></i> <span><?php echo $subnav['title']; ?></span></a>
              <?php } else { ?>
                <a <?php echo !empty($subnav['url']) ? 'href="' . $subnav['url'] . '"' : ''; ?> class="<?php echo !empty($subnav['child']) ? 'parent' : ''; ?> <?php echo !empty($subnav['class']) ? $subnav['class'] : ''; ?>"><?php echo $subnav['title']; ?></a>
              <?php } ?>

              <?php if (!empty($subnav['child'])) { ?>
                <ul>
                  <?php foreach ($subnav['child'] as $child) { ?>
                    <li id="<?php echo !empty($child['id']) ? $child['id'] : ''; ?>">
                      <?php if (!empty($child['icon'])) { ?>
                        <a <?php echo !empty($child['url']) ? 'href="' . $child['url'] . '"' : ''; ?> class="<?php echo !empty($child['child']) ? 'parent' : ''; ?> <?php echo !empty($child['class']) ? $child['class'] : ''; ?>"><i class="fa fa-<?php echo $child['icon']; ?> fa-fw"></i> <span><?php echo $child['title']; ?></span></a>
                      <?php } else { ?>
                        <a <?php echo !empty($child['url']) ? 'href="' . $child['url'] . '"' : ''; ?> class="<?php echo !empty($child['child']) ? 'parent' : ''; ?> <?php echo !empty($child['class']) ? $child['class'] : ''; ?>"><?php echo $child['title']; ?></a>
                      <?php } ?>

                      <?php if (!empty($child['child'])) { ?>
                        <ul>
                          <?php foreach ($child['child'] as $subchild) { ?>
                            <li id="<?php echo !empty($subchild['id']) ? $subchild['id'] : ''; ?>">
                              <?php if (!empty($subchild['icon'])) { ?>
                                <a <?php echo !empty($subchild['url']) ? 'href="' . $subchild['url'] . '"' : ''; ?> class="<?php echo !empty($subchild['child']) ? 'parent' : ''; ?> <?php echo !empty($subchild['class']) ? $subchild['class'] : ''; ?>"><i class="fa fa-<?php echo $subchild['icon']; ?> fa-fw"></i> <span><?php echo $subchild['title']; ?></span></a>
                              <?php } else { ?>
                                <a <?php echo !empty($subchild['url']) ? 'href="' . $subchild['url'] . '"' : ''; ?> class="<?php echo !empty($subchild['child']) ? 'parent' : ''; ?> <?php echo !empty($subchild['class']) ? $subchild['class'] : ''; ?>"><?php echo $subchild['title']; ?></a>
                              <?php } ?>

                              <?php if (!empty($subchild['child'])) { ?>
                                <ul>
                                  <?php foreach ($subchild['child'] as $grandchild) { ?>
                                    <li id="<?php echo !empty($grandchild['id']) ? $grandchild['id'] : ''; ?>">
                                      <?php if (!empty($grandchild['icon'])) { ?>
                                        <a <?php echo !empty($grandchild['url']) ? 'href="' . $grandchild['url'] . '"' : ''; ?> class="<?php echo !empty($grandchild['class']) ? $grandchild['class'] : ''; ?>"><i class="fa fa-<?php echo $grandchild['icon']; ?> fa-fw"></i> <span><?php echo $grandchild['title']; ?></span></a>
                                      <?php } else { ?>
                                        <a <?php echo !empty($grandchild['url']) ? 'href="' . $grandchild['url'] . '"' : ''; ?> class="<?php echo !empty($grandchild['class']) ? $grandchild['class'] : ''; ?>"><?php echo $grandchild['title']; ?></a>
                                      <?php } ?>

                                      <?php if (!empty($grandchild['child'])) { ?>
                                        <ul>
                                          <?php foreach ($grandchild['child'] as $subgrandchild) { ?>
                                            <li id="<?php echo !empty($subgrandchild['id']) ? $subgrandchild['id'] : ''; ?>">
                                              <?php if (!empty($subgrandchild['icon'])) { ?>
                                                <a <?php echo !empty($subgrandchild['url']) ? 'href="' . $subgrandchild['url'] . '"' : ''; ?> class="<?php echo !empty($subgrandchild['class']) ? $subgrandchild['class'] : ''; ?>"><i class="fa fa-<?php echo $grandchild['icon']; ?> fa-fw"></i> <span><?php echo $subgrandchild['title']; ?></span></a>
                                              <?php } else { ?>
                                                <a <?php echo !empty($subgrandchild['url']) ? 'href="' . $subgrandchild['url'] . '"' : ''; ?> class="<?php echo !empty($subgrandchild['class']) ? $subgrandchild['class'] : ''; ?>"><?php echo $subgrandchild['title']; ?></a>
                                              <?php } ?>
                                            </li>
                                          <?php } ?>
                                        </ul>
                                      <?php } ?>

                                    </li>
                                  <?php } ?>
                                </ul>
                              <?php } ?>

                            </li>
                          <?php } ?>
                        </ul>
                      <?php } ?>

                    </li>
                  <?php } ?>
                </ul>
              <?php } ?>

            </li>
          <?php } ?>
        </ul>
      <?php } ?>

    </li>
  <?php } ?>
</ul>