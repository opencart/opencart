<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <?php if ($success) { ?>
        <div class="success"><?php echo $success; ?></div>
    <?php } ?>

    <div class="box" style="margin-bottom:130px;">
        <div class="heading">
            <h1><?php echo $lang_heading; ?></h1>
        </div>
        <div class="content">
            <div class="openbayLinks">
                <div class="openbayPod" onclick="location='<?php echo $links_settings; ?>'">
                    <img src="<?php echo $image['icon1']; ?>" title="" alt="" border="0" />
                    <h3>Settings</h3>
                </div>
                <div class="openbayPod" onclick="location='<?php echo $links_pricing; ?>'">
                    <img src="<?php echo $image['icon13']; ?>" title="" alt="" border="0" />
                    <h3>Pricing report</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
$(document).ready(function() {

});
//--></script>
<?php echo $footer; ?>
