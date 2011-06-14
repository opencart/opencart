<?php
class ControllerReportVisitor extends Controller { 
	public function index() {  
		$this->load->language('report/visitor');

		$this->document->setTitle($this->language->get('heading_title'));

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/sale', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		
		$request  = 'Email=blueyon@gmail.com';
		$request .= '&Passwd=mexico44';
		$request .= '&accountType=GOOGLE';
		$request .= '&source=curl-dataFeed-v2';
		$request .= '&service=analytics';
		
		// Login to Google Analytics
		if (!isset($this->session->data['analytics'])) {
			$curl = curl_init('https://www.google.com/accounts/ClientLogin');
			
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_HEADER, 0);	
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
					
			$response = curl_exec($curl);
			
			curl_close($curl);
			
			if (!$response) {
				echo curl_error($curl) . '(' . curl_errno($curl) . ')';
			}
			
			// Get the data
			preg_match('/Auth=(.*)/', $response, $matches);
			
			if (isset($matches[1])) {
				$token = $matches[1];
			} else {
				$token = '';
			}
		} else {
			$token = $this->session->data['analytics'];
		}
		
		
		//echo $token;
			  
		$curl = curl_init('https://www.google.com/analytics/feeds/data
?ids=47109635
&start-date=2008-10-0147109635
&end-date=2008-10-3147109635
&dimensions=ga:source,ga:medium
&metrics=ga:visits,ga:bounces
&sort=-ga:visits
&filters=ga:medium%3D%3Dreferral
&max-results=5
&prettyprint=true
Email=blueyon@gmail.com
&Passwd=mexico44
&accountType=GOOGLE
&source=curl-dataFeed-v2
&service=analytics'

);
		
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_HEADER, 'Authorization: AuthSub token="' . $token . '"' . "\n");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		
		$response = curl_exec($curl);
		
		curl_close($curl);
		
		if (!$response) {
			echo curl_error($curl) . '(' . curl_errno($curl) . ')';
		}		
		
		echo $response;
		
		
		$this->layout = 'common/layout';
		$this->template = 'report/visitor.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
}
?>