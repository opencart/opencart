<?php  
class ControllerReportAffiliateActivity extends Controller {  
  	public function index() {
		$this->language->load('report/affiliate_activity');
		
    	$this->document->setTitle($this->language->get('heading_title'));
		
		if (isset($this->request->get['filter_affiliate'])) {
			$filter_affiliate = $this->request->get['filter_affiliate'];
		} else {
			$filter_affiliate = NULL;
		}		
		
		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = NULL;
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}
						
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
																		
		$url = '';
		
		if (isset($this->request->get['filter_affiliate'])) {
			$url .= '&filter_affiliate=' . urlencode($this->request->get['filter_affiliate']);
		}
		
		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
						
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
						
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
       		'text' => $this->language->get('text_home')
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href' => $this->url->link('report/affiliate_activity', 'token=' . $this->session->data['token'] . $url, 'SSL'),
       		'text' => $this->language->get('heading_title')
   		);
		
		$this->load->model('report/affiliate');
		
		$this->data['activities'] = array();

		$data = array(
			'filter_affiliate'   => $filter_affiliate, 
			'filter_ip'         => $filter_ip, 
			'filter_date_start'	=> $filter_date_start, 
			'filter_date_end'	=> $filter_date_end, 			
			'start'             => ($page - 1) * 20,
			'limit'             => 20
		);
		
		$activity_total = $this->model_report_affiliate->getTotalAffiliateActivities($data);
		
		$results = $this->model_report_affiliate->getAffiliateActivities($data);
    	
		foreach ($results as $result) {
      		$this->data['activities'][] = array(
				'affiliate'  => $result['affiliate'],
				'comment'    => $result['comment'],
				'ip'         => $result['ip'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}	
		
 		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');
		
		$this->data['column_affiliate'] = $this->language->get('column_affiliate');
		$this->data['column_comment'] = $this->language->get('column_comment');
		$this->data['column_ip'] = $this->language->get('column_ip');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		
		$this->data['entry_affiliate'] = $this->language->get('entry_affiliate');	
		$this->data['entry_ip'] = $this->language->get('entry_ip');			
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		
		$this->data['button_filter'] = $this->language->get('button_filter');
				
		$this->data['token'] = $this->session->data['token'];
		
		$url = '';
		
		if (isset($this->request->get['filter_affiliate'])) {
			$url .= '&filter_affiliate=' . urlencode($this->request->get['filter_affiliate']);
		}
		
		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
						
		$pagination = new Pagination();
		$pagination->total = $activity_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->url = $this->url->link('report/affiliate_activity', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
	
		$this->data['results'] = sprintf($this->language->get('text_pagination'), ($activity_total) ? (($page - 1) * $this->config->get('config_admin_limit')) + 1 : 0, ((($page - 1) * $this->config->get('config_admin_limit')) > ($activity_total - $this->config->get('config_admin_limit'))) ? $activity_total : ((($page - 1) * $this->config->get('config_admin_limit')) + $this->config->get('config_admin_limit')), $activity_total, ceil($activity_total / $this->config->get('config_admin_limit')));
		
		$this->data['filter_affiliate'] = $filter_affiliate;
		$this->data['filter_ip'] = $filter_ip;	
		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;				
				
		$this->template = 'report/affiliate_activity.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render());
  	}
}
?>