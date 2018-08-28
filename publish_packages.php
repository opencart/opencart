<?php

class PublishPackages
{
    public function __construct()
    {
        $use_system = true;
        $copy_dirs = array(
            
            ### Bootstrap
                
            //copy Bootstrap 4 scss to admin
            array(
                "src" => "/node_modules/bootstrap/scss/",
                "dst" => "/upload/admin/view/stylesheet/scss/"
            ),
            
            //copy Bootstrap 4 scss to catalog
            array(
                "src" => "/node_modules/bootstrap/scss/",
                "dst" => "/upload/catalog/view/theme/default/stylesheet/scss/"
            ),
            
            //copy Bootstrap 4 css dist to admin
            array(
                "src" => "/node_modules/bootstrap/dist/css/",
                "dst" => "/upload/admin/view/javascript/bootstrap/css/"
            ),
            
            //copy Bootstrap 4 js dist to admin
            array(
                "src" => "/node_modules/bootstrap/dist/js/",
                "dst" => "/upload/admin/view/javascript/bootstrap/js/"
            ),
            
            //copy Bootstrap 4 css dist to catalog
            array(
                "src" => "/node_modules/bootstrap/dist/css/",
                "dst" => "/upload/catalog/view/javascript/bootstrap/css/"
            ),
            
            //copy Bootstrap 4 js dist to catalog
            array(
                "src" => "/node_modules/bootstrap/dist/js/",
                "dst" => "/upload/catalog/view/javascript/bootstrap/js/"
            ),
            
            ### Popper.js
            
            //copy Popper.js to admin
            array(
                "src" => "/node_modules/popper.js/dist/umd/popper.min.js",
                "dst" => "/upload/admin/view/javascript/popper.min.js"
            ),
            
            //copy Popper.js to catalog
            array(
                "src" => "/node_modules/popper.js/dist/umd/popper.min.js",
                "dst" => "/upload/catalog/view/javascript/bootstrap/js/popper.min.js"
            ),
            
            ### FontAwesome 
            
            //copy Font Awesome to admin
            array(
                "src" => "/node_modules/@fortawesome/fontawesome-free/",
                "dst" => "/upload/admin/view/javascript/font-awesome/"
            ),
            
            //copy Font Awesome to catalog
            array(
                "src" => "/node_modules/@fortawesome/fontawesome-free/",
                "dst" => "/upload/catalog/view/javascript/font-awesome/"
            ),
            
            #### jQuery
            
            //copy jquery.min.js to admin
            array(
                "src" => "/node_modules/jquery/dist/jquery.min.js",
                "dst" => "/upload/admin/view/javascript/jquery/jquery.min.js"
            ),
            
            //copy jquery.min.js to catalog
            array(
                "src" => "/node_modules/jquery/dist/jquery.min.js",
                "dst" => "/upload/catalog/view/javascript/jquery/jquery.min.js"
            ),
            
            ### copy jquery-ui to admin
            array(
                "src" => "/node_modules/jquery-ui-dist/",
                "dst" => "/upload/admin/view/javascript/jquery/jquery-ui/"
            ),
            
            ### datetimepicker
            
            // copy datetimepicker css to admin
            array(
                "src" => "/node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css",
                "dst" => "/upload/admin/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css"
            ),
            
            // copy datetimepicker js to admin
            array(
                "src" => "/node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js",
                "dst" => "/upload/admin/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js"
            ),
            
            // copy datetimepicker css to catalog
            array(
                "src" => "/node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css",
                "dst" => "/upload/catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css"
            ),
            
            // copy datetimepicker js to catalog
            array(
                "src" => "/node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js",
                "dst" => "/upload/catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js"
            ),
            
            ### Moment
            
            // copy moment to admin
            array(
                "src" => "/node_modules/moment/min/",
                "dst" => "/upload/admin/view/javascript/jquery/datetimepicker/moment/"
            ),
            
            // copy moment to catalog
            array(
                "src" => "/node_modules/moment/min/",
                "dst" => "/upload/catalog/view/javascript/jquery/datetimepicker/moment/"
            ),
            
            ### Flot
            
            // copy flot to admin
            array(
                "src" => "/node_modules/jquery.flot/",
                "dst" => "/upload/admin/view/javascript/jquery/flot/"
            ),
            
            ### jqvmap
            
            // copy jqvmap to admin
            array(
                "src" => "/node_modules/jqvmap/dist/",
                "dst" => "/upload/admin/view/javascript/jquery/jqvmap/"
            ),
            
            ### magnific-popup
            
            // copy magnific-popup to admin
            array(
                "src" => "/node_modules/magnific-popup/dist/",
                "dst" => "/upload/admin/view/javascript/jquery/magnific/"
            ),
            
            // copy magnific-popup to catalog
            array(
                "src" => "/node_modules/magnific-popup/dist/",
                "dst" => "/upload/catalog/view/javascript/jquery/magnific/"
            ),
            
            ### ckeditor
            
            ### // copy ckeditor to admin
            ### array(
            ###     "src" => "/node_modules/ckeditor-codemirror-plugin/codemirror",
            ###     "dst" => "/upload/admin/view/javascript/ckeditor/plugins/"
            ### ),
            ### 
            ### // copy ckeditor codemirror plugin to admin
            ### array(
            ###     "src" => "/upload/admin/view/javascript/ckeditor_build/plugins/",
            ###     "dst" => "/upload/admin/view/javascript/ckeditor/plugins/"
            ### ),
            ### 
            ### // copy ckeditor opencart && codemirror plugins to admin
            ### array(
            ###     "src" => "/upload/admin/view/javascript/ckeditor_build/plugins/",
            ###     "dst" => "/upload/admin/view/javascript/ckeditor/plugins/"
            ### ),
            ### 
            ### // copy custom ckeditor config.js to admin
            ### array(
            ###     "src" => "/upload/admin/view/javascript/ckeditor_build/config.js",
            ###     "dst" => "/upload/admin/view/javascript/ckeditor/config.js"
            ### ),
            ### 
            ### ### codemirror
            ### 
            ### // copy codemirror to admin
            ### array(
            ###     "src" => "/node_modules/codemirror/",
            ###     "dst" => "/upload/admin/view/javascript/codemirror/"
            ### ),
        );
        
        foreach ($copy_dirs as $copy_dir) {
            $src = dirname(__FILE__).$copy_dir["src"];
            $dst = dirname(__FILE__).$copy_dir["dst"];
            if ($use_system) {
                system('cp -Rf '.$src.' '.$dst, $retval);
            }
            else {
                $this->_recurse_copy($src,$dst);
            }
        }
        
        //remove sass.php generated files so they are built on the first load http://catalog/[admin]
        system('rm -f '.dirname(__FILE__).'/upload/admin/view/stylesheet/bootstrap.css');
        system('rm -f '.dirname(__FILE__).'/upload/catalog/view/theme/default/stylesheet/bootstrap.css');
        system('rm -f '.dirname(__FILE__).'/upload/catalog/view/theme/default/stylesheet/stylesheet.css');
        
    }
    
    private function _recurse_copy($src,$dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
