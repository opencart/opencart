<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_install) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_install; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php foreach ($rows as $row) { ?>
    <div class="row">
      <?php foreach ($row as $dashboard) { ?>
      <?php if ($dashboard['width'] == 1) { ?>
      
      <?php $class = 'col-lg-3 col-md-3 col-sm-6'; ?>
      
      <?php } elseif ($dashboard['width'] == 2) { ?>
      
      <?php } elseif ($dashboard['width'] == 3) { ?>
      
      <?php } elseif ($dashboard['width'] == 4) { ?>
      
      <?php } elseif ($dashboard['width'] == 4) { ?>
      
      <?php } elseif ($dashboard['width'] == 4) { ?>
      
      <?php } elseif ($dashboard['width'] == 4) { ?>
      
      <?php } elseif ($dashboard['width'] == 4) { ?>
      
      <?php } elseif ($dashboard['width'] == 4) { ?>
      
      <?php } else { ?>
      
      <?php } ?>
//col-lg-3 col-md-3 col-sm-6
//col-lg-6 col-md-12 col-sx-12 col-sm-12

//col-lg-4 col-md-12 col-sm-12 col-sx-12
//col-lg-8 col-md-12 col-sm-12 col-sx-12      
      
      
      <div class="col-lg-<?php echo $dashboard['width']; ?> col-md-3 col-sm-<?php echo ($dashboard['width'] * 2); ?>"><?php echo $dashboard['output']; ?></div>
      <?php } ?>
    </div>
    <?php } ?>
  </div>
</div>
<?php echo $footer; ?>