<?php echo $header; ?>
<div id="content">
  <div class="container-fluid"><br />
    <br />
    <div class="row">
      <div class="col-sm-offset-4 col-sm-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h1 class="panel-title"><i class="fa fa-lock"></i> <?php echo $text_login; ?></h1>
          </div>
          <div class="panel-body">
            <?php if ($success) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>
            
            <?php  $style = ($password_expired_error == '') ? 'display:none;':'display:block;'; ?>
            <div id="div_password_expired_error_id" class="warning" style="padding: 3px; <?php echo $style; ?>"><?php echo $password_expired_error; ?></div>
    
            <?php if ($security_alert != '') { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $security_alert; ?>
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } else { ?>        
                <?php if ($error_warning) { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <?php } ?>
            <?php } ?>
            <?php  $style = ($password_expires_warning == '') ? 'display:none;':'display:block;'; ?>
            <div id="div_password_expires_warning_id" class="alert alert-danger" style="padding: 3px; <?php echo $style; ?>"><i class="fa fa-exclamation-circle"></i><?php echo $password_expires_warning; ?></div>
            
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="input-username"><?php echo $entry_username; ?></label>
                <div class="input-group"><span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" onblur="getPasswordExpires();" />
                </div>
              </div>
              <div class="form-group">
                <label for="input-password"><?php echo $entry_password; ?></label>
                <div class="input-group"><span class="input-group-addon"><i class="fa fa-lock"></i></span>
                  <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                </div>
                <?php if ($forgotten) { ?>
                <span class="help-link"><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></span>
                <?php } ?>
                
                <span class="help-link"><a id="link_change_password_id" href="<?php echo $change_password_href; ?>" ><?php echo $change_password_text; ?></a></span>
              </div>               
              <?php 
              if (!$is_safe_ip) {
                echo('<div class="form-group"><label for="input-security-answer">'.$entry_security_question.'</label><div class="input-group"><span class="input-group-addon"><i class="fa fa-comment-o"></i></span><input type="password" name="security_answer" id="input-security-answer" class="form-control" value="" placeholder="'.$text_answer_security_question.'" /></div></div>');
              }
              ?>                
              <div class="text-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-key"></i> <?php echo $button_login; ?></button>
              </div>
              <?php if ($redirect) { ?>
              <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
              <?php } ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
    $('#password_id').keydown(function(e) {
        if (e.keyCode == 13) {
            $('#form').submit();
        }
    });

    function getPasswordExpires() {
        $.ajax({
            url: 'index.php?route=common/login/getPasswordExpires',
            type: 'POST',
            data: 'username=' + encodeURIComponent($('#username_id').val()),
            dataType: 'json',
            success: function(data) {
                $('#div_password_expired_error_id').hide();
                $('#div_password_expires_warning_id').hide();
                $('#form').show();
                if (data.password_expires_warning != '') {
                    $('#div_password_expires_warning_id').html(data.password_expires_warning).show();
                }
                if (data.password_expired_error != '') {
                    $('#div_password_expired_error_id').html(data.password_expired_error).show();
                    $('#form').hide();
                }
            }
        });
    }
//--></script> 

<?php echo $footer; ?>
