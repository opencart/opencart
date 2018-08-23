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
                "src" => "/upload/system/storage/vendor/twbs/bootstrap/dist/js/",
                "dst" => "/upload/catalog/view/javascript/bootstrap/js/"
            ),
            
            ### Popper.js
            
            #//copy Popper.js to admin
            #array(
            #    "src" => "/node_modules/popper.js/dist/popper.min.js",
            #    "dst" => "/upload/admin/view/javascript/popper.min.js"
            #),
            #
            #//copy Popper.js to catalog
            #array(
            #    "src" => "/node_modules/popper.js/dist/popper.min.js",
            #    "dst" => "/upload/catalog/view/javascript/bootstrap/js/popper.min.js"
            #),
            
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
            #array(
            #    "src" => "/node_modules/jquery-ui/",
            #    "dst" => "/upload/admin/view/javascript/jquery/jquery-ui/"
            #),
            
            ### copy jquery-ui to catalog
            #array(
            #    "src" => "/node_modules/jquery-ui/",
            #    "dst" => "/upload/catalog/view/javascript/jquery/jquery-ui/"
            #),
            
            ### datetimepicker
            
            // copy datetimepicker css to admin
            array(
                "src" => "/node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css",
                "dst" => "/upload/admin/view/javascript/jquery/datetimepicker/tempusdominus-bootstrap-4.min.css"
            ),
            
            // copy datetimepicker js to admin
            array(
                "src" => "/node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js",
                "dst" => "/upload/admin/view/javascript/jquery/datetimepicker/tempusdominus-bootstrap-4.min.js"
            ),
            
            // copy datetimepicker css to catalog
            array(
                "src" => "/node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css",
                "dst" => "/upload/catalog/view/javascript/jquery/datetimepicker/tempusdominus-bootstrap-4.min.css"
            ),
            
            // copy datetimepicker js to catalog
            array(
                "src" => "/node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js",
                "dst" => "/upload/catalog/view/javascript/jquery/datetimepicker/tempusdominus-bootstrap-4.min.js"
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
            //array(
            //    "src" => "/node_modules/float/min/",
            //    "dst" => "/upload/admin/view/javascript/jquery/datetimepicker/moment/"
            //),
            
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
