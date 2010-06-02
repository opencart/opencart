//-----------------------------------------
// Author: Qphoria@gmail.com
// Web: http://www.unbannable.com/ocstore
// Confirm Actions (delete, uninstall)
//-----------------------------------------
$(document).ready(function(){
	
    // Confirm Delete
    $('#form').submit(function(){
        if ($(this).attr('action').indexOf('delete',1) != -1) {
            if (!confirm ('Are you sure you want to do this?')) {
                return false;
            }
        }
    });
    
    // Confirm Uninstall
    $('a').click(function(){
        if ($(this).attr('href').indexOf('uninstall',1) != -1) {
            if (!confirm ('Are you sure you want to do this?')) {
                return false;
            }
        }
    });   
});