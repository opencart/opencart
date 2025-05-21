<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Dashboard
 *
 * Can be loaded using $this->load->controller('common/dashboard');
 *
 * @package Opencart\Admin\Controller\Common
 */
class Dashboard extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('common/dashboard');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		// Dashboard Extensions
		$dashboards = [];

		$this->load->model('setting/extension');

		// Get a list of installed modules
		$extensions = $this->model_setting_extension->getExtensionsByType('dashboard');

		// Add all the modules which have multiple settings for each module
		foreach ($extensions as $extension) {
			if ($this->config->get('dashboard_' . $extension['code'] . '_status') && $this->user->hasPermission('access', 'extension/' . $extension['extension'] . '/dashboard/' . $extension['code'])) {
				$output = $this->load->controller('extension/' . $extension['extension'] . '/dashboard/' . $extension['code'] . '.dashboard');

				if (!$output instanceof \Exception) {
					$dashboards[] = [
						'code'       => $extension['code'],
						'width'      => $this->config->get('dashboard_' . $extension['code'] . '_width'),
						'sort_order' => $this->config->get('dashboard_' . $extension['code'] . '_sort_order'),
						'output'     => $output
					];
				}
			}
		}

		$sort_order = [];

		foreach ($dashboards as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $dashboards);

		// Split the array so the columns width is not more than 12 on each row.
		$width = 0;
		$column = [];
		$data['rows'] = [];

		foreach ($dashboards as $dashboard) {
			$column[] = $dashboard;

			$width = ($width + $dashboard['width']);

			if ($width >= 12) {
				$data['rows'][] = $column;

				$width = 0;
				$column = [];
			}
		}

		if ($column) {
			$data['rows'][] = $column;
		}

		if ($this->user->hasPermission('access', 'common/developer')) {
			$data['developer_status'] = true;
		} else {
			$data['developer_status'] = false;
		}

		$data['security'] = $this->load->controller('common/security');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/dashboard', $data));
	// Load necessary models
    $this->load->model('sale/order');
    $this->load->model('customer/customer');
    
    // Generate URLs with current token
    $data['order_link'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'], true);
    $data['customer_link'] = $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'], true);
    $data['report_link'] = $this->url->link('report/report', 'user_token=' . $this->session->data['user_token'], true);
    
    // Get actual data
    $data['total_orders'] = $this->model_sale_order->getTotalOrders();
    $data['total_sales'] = $this->currency->format($this->model_sale_order->getTotalSales(), $this->config->get('config_currency'));
    $data['total_customers'] = $this->model_customer_customer->getTotalCustomers();
    
    // Pass to view
    $this->response->setOutput($this->load->view('common/dashboard', $data));
}
	
public function getChartData() {
    $json = array();
    $rawData = array();
    $maxOrders = 0;

    // Get complete status IDs with fallback
    $complete_statuses = $this->config->get('config_complete_status');
    if (empty($complete_statuses)) {
        $complete_statuses = array(5); // Default status ID
    }

    // Get order counts for last 7 days
    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        
        $query = $this->db->query("SELECT COUNT(*) as order_count 
                                 FROM `" . DB_PREFIX . "order` 
                                 WHERE DATE(date_added) = '" . $this->db->escape($date) . "' 
                                 AND order_status_id IN(" . implode(',', $complete_statuses) . ")");
        
        $count = (int)$query->row['order_count'];
        $rawData[$date] = $count;
        
        if ($count > $maxOrders) {
            $maxOrders = $count;
        }
    }

    // Normalize data
    foreach ($rawData as $date => $count) {
        $json['labels'][] = date('D', strtotime($date));
        $json['data'][] = ($maxOrders > 0) ? min(100, round(($count / $maxOrders) * 100)) : 0;
        $json['raw_counts'][] = $count;
    }

    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode($json));
}

	public function clearSystemCache() {
    $this->load->language('common/dashboard');
    
    // Clear system cache (files in system/storage/cache/)
    $files = glob(DIR_CACHE . '*');
    
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode(['success' => true]));
}

public function clearImageCache() {
    $this->load->language('common/dashboard');
    
    // Clear image cache (files in system/storage/image/cache/)
    $files = glob(DIR_IMAGE . 'cache/*');
    
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    
    $this->response->addHeader('Content-Type: application/json');
    $this->response->setOutput(json_encode(['success' => true]));
}
}
