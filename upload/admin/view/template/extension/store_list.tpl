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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-puzzle-piece"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="input-group">
            <input type="text" name="search" value="" placeholder="Search for extensions" class="form-control" />
            <div class="input-group-btn">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">All Categories <span class="caret"></span></button>
              <ul class="dropdown-menu">
                <li><a href="#">All Categories</a></li>
                <li><a href="#">Themes</a></li>
                <li><a href="#">Something else here</a></li>
              </ul>
              <button type="button" class="btn btn-primary">Search</button>
            </div>
          </div>
        </div>
        Sort
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 