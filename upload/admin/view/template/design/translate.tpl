<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right"><a href="<?php echo $refresh; ?>" data-toggle="tooltip" title="<?php echo $button_refresh; ?>" class="btn btn-info"><i class="fa fa-refresh"></i></a></div>
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
					<h3 class="panel-title"><i class="fa fa-list"></i> Language Pack </h3>
				</div>

				<div class="panel-body">

					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<tbody>
								<?php foreach ($language_data as $language) { ?>
									<tr>
										<td class="text-left">
											<img src="https://d1ztvzf22lmr1j.cloudfront.net/images/flags/<?php echo $language->code; ?>.png" style = "width:48px; height:48px;"><?php echo $language->name; ?> 
										</td>
										<td class="text-left"><?php echo $language->code; ?></td>
										<td>

											<?php if ( $language->translated_progress > 75) { ?>
												<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:<?php echo  $language->translated_progress; ?>%"><?php echo  $language->translated_progress; ?>% Complete (success)</div>
												<?php }else if ( $language->translated_progress >25 && $language->translated_progress < 75) { ?>
													<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:<?php echo $language->translated_progress; ?>%"><?php echo $language->translated_progress; ?>% Complete (success)</div>
													<?php }else if ($language->translated_progress < 25) { ?>
														<div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:<?php echo $language->translated_progress; ?>%"><?php echo $language->translated_progress; ?>% </div>
														<?php } ?>

													</td>
													
														<td class="text-right" onclick="language.download('<?php echo $language->code; ?>')" >Download</td>
														<td class="text-right" onclick="language.install('<?php echo $language->code; ?>')" >Install</td>
														<td class="text-right" onclick="language.uninstall('<?php echo $language->code; ?>')" >Uninstall</td>
													
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>	

								</div>			
							</div>

							<script type="text/javascript">

								var language = {
									'download' : function(code){
										var language_code = code;
										$.ajax({
											method: "POST",
											url: "index.php?route=design/translate/download&token=" + getURLVar('token'),
											data: { code: language_code }
										})
										.success(function( msg ) {
											alert( "ajax success" );
										});
									},
									'install' : function(code){
										var language_code = code;
										$.ajax({
											method: "POST",
											url: "index.php?route=design/translate/install&token=" + getURLVar('token'),
											data: { code: language_code }
										})
										.success(function( msg ) {
											alert( "ajax success" );
										});
									},
									'uninstall' : function(code){
										var language_code = code;
										$.ajax({
											method: "POST",
											url: "index.php?route=design/translate/uninstall&token=" + getURLVar('token'),
											data: { code: language_code }
										})
										.success(function( msg ) {
											alert( "ajax success" );
										});
									}
								}

							</script>

							<?php echo $footer; ?> 

