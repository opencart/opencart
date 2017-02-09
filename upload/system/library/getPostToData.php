<?php
// author wazzzar

class getPostToData {
	
	private $request;
	
	public function __construct( $request ){
		
		$this->request = $request;
		
	}
	
	public function add( $data, $vars ){
		
		foreach( $vars as $var ){
			
			if ( isset( $this->request->post[ $var ] ) ) {
				
				$data[ $var ] = $this->request->post[ $var ];
				
			} elseif ( isset( $this->request->get[ $var ] ) ) {
				
				$data[ $var ] = $this->request->get[ $var ];
				
			} else {
				
				$data[ $var ] = '';
				
			}
			
		}
		
	}
	
}

/* example

$gptd = new getPostToData( $this->request );
$gptd->add( $data, array( 'getvar1', 'getvar2', 'getvar3', 'getvar4', 'getvar5' ) );
// we have all vars in data

*/
