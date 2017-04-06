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
            <input type="text" name="search" value="" placeholder="<?php echo $text_search; ?>" class="form-control" />
            <div class="input-group-btn">
              <?php foreach ($categories as $category) { ?>
              <?php if ($category['value'] == $filter_category) { ?>
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
              <?php $text_category; ?>
              (
              <?php $category['text']; ?>
              ) <span class="caret"></span></button>
              <?php } ?>
              <?php } ?>
              <ul class="dropdown-menu">
                <?php foreach ($categories as $category) { ?>
                <li><a href="<?php echo $category['href']; ?>"><?php echo $category['text']; ?></a></li>
                <?php } ?>
              </ul>
              <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-filter"></i></button>
            </div>
          </div>
        </div>
        </fieldset>
        <div class="row">
          <div class="col-sm-9 col-xs-7">
            <div class="btn-group">
              <?php foreach ($licenses as $license) { ?>
              <?php if ($license['value'] == $filter_license) { ?>
              <a href="<?php echo $license['href']; ?>" class="btn btn-default active"><?php echo $license['text']; ?></a>
              <?php } else { ?>
              <a href="<?php echo $license['href']; ?>" class="btn btn-default"><?php echo $license['text']; ?></a>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class="col-sm-3 col-xs-5">
            <div class="input-group pull-right">
              <div class="input-group-addon"><i class="fa fa-sort-amount-asc"></i></div>
              <select onchange="location = this.value;" class="form-control">
                <?php foreach ($sorts as $sorts) { ?>
                <?php if ($sorts['value'] == $sort) { ?>
                <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <br />
        <?php if ($extensions) { ?>
        <?php foreach (array_chunk($extensions, 3) as $row) { ?>
        <div class="row">
          <?php foreach ($row as $extension) { ?>
          <div class="col-md-4">
            <section>
              <div class="extension-preview"> <a href="<?php echo $extension['href']; ?>">
                <div class="extension-description"><?php echo $extension['description']; ?></div>
                <img src="<?php echo $extension['image']; ?>" alt="<?php echo $extension['name']; ?>" title="<?php echo $extension['name']; ?>" class="img-responsive" /> </a> </div>
              <div class="extension-name">
                <h4><a href="<?php echo $extension['href']; ?>"><?php echo $extension['name']; ?></a></h4>
                <?php echo $extension['price']; ?> </div>
              <div>
                <div class="row">
                  <div class="col-xs-6">
                    <div>
                      <?php for ($i = 1; $i < 5; $i++) { ?>
                      <?php if ($i < $extension['rating']) { ?>
                      <i class="fa fa-star"></i>
                      <?php } else { ?>
                      <i class="fa fa-star-o"></i>
                      <?php } ?>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="col-xs-6">
                    <div class="text-right"><span><?php echo $extension['review_total']; ?> reviews</span></div>
                  </div>
                </div>
              </div>
            </section>
          </div>
          <?php } ?>
        </div>
        <?php } ?>
        <div class="row">
          <div class="col-sm-12 text-center"><?php echo $pagination; ?></div>
        </div>
        <?php } else { ?>
        <div><?php echo $text_no_results; ?></div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 