<?php
// author wazzzar

class checkErrors {
	
	private $error;
	
	public function __construct( $error ){
		
		$this->error = $error;
		
	}
	
	public function check( $data, $vars ){
		
		foreach( $vars as $var ){
			
			if ( isset( $this->error[ $var ] ) ) {
				
				$data[ 'error_'. $var ] = $this->error[ $var ];
				
			} else {
				
				$data[ 'error_'. $var ] = '';
				
			}
			
		}
		
	}
	
}

/* example

$ce = new checkErrors( $this->error );
$ce->check( $data, array( 'var1', 'var2', 'var3', 'var4', 'var5' ) );
// we have all errors in data

*/
