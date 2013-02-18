<?php

class ControllerSettingLocation extends Controller {
    
     private $error = array();
    
     public function index()
     {
         /*
          *   Load up our page 
          */
          
         $this->language->load('setting/location'); //  Load up language file
         
         $this->document->setTitle($this->language->get('heading_title'));  // Set the title and language
         
         $this->load->model('setting/location');    //  Load up our model file for locations
                       
         $this->getList();
     }
     
     public function insert()
     {
        $this->language->load('setting/location');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/location');
        
        //  Check if the form has been posted and if passes through validation, if so 
        //  proceed to redirection with success text.
        if(($this->request->server['REQUEST_METHOD']) == 'POST' && $this->validateForm())
        {   
            $this->model_setting_location->addLocation($this->request->post);
            
            //  Tell the user everything OK
            $this->session->data['success'] =   $this->language->get('text_success');
            
            $url    =    '';
            
            if(isset($this->request->get['sort']))
            {
                $url    .=  '&sort='    .   $this->request->get['sort'];
            }

            if(isset($this->request->get['order']))
            {
                $url    .=  '$order='   .   $this->request->get['order'];
            }

            if(isset($this->reqest->get['page']))
            {
                $url    .=  '&page='    .    $this->request->get['page'];
            }
            
            $this->redirect($this->url->link('setting/location', 'token='    .   $this->session->data['token']   .   $url, 'SSL'));
        }
        
        $this->getForm();   
     }

     public function update()
     {
         /*
          *     update() used to update the details of our form and page
          */
         $this->language->load('setting/location');
         
         $this->document->setTitle($this->language->get('heading_title'));
         
         $this->load->model('setting/location');
         
         if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm())
         {
             $this->model_setting_location->editLocation($this->request->get['location_id'], $this->request->post);
             
             $this->session->data['success']    =   $this->language->get('text_success');
             
             $url   =   '';
             
             if(isset($this->request->get['sort']))
             {
                 $url   .=  '&sort='    .   $this->request->get['sort'];
             }

             if(isset($this->request->get['order']))
             {
                 $url   .=  '&order='    .   $this->request->get['order'];
             }
             
             if(isset($this->request->get['page']))
             {
                 $url   .=  '&page='    .   $this->request->get['page'];
             }

             $this->redirect($this->url->link('setting/location', 'token='   .   $this->session->data['token']   .   $url,   'SSL'));
         }
         
         $this->getForm();
     }

     public function delete()
     {
         $this->language->load('setting/location');
         
         $this->document->setTitle($this->language->get('heading_title'));
         
         $this->load->model('setting/location');
         
         if(isset($this->request->post['selected']) && $this->validateDelete())
         {
             foreach($this->request->post['selected'] as $location_id)
             {
                 $this->model_setting_location->deleteLocation($location_id);
             }
             
             $this->session->data['success']    =   $this->language->get('text_success');
             
             $url   =   '';
             
             if(isset($this->request->get['sort']))
             {
                $url    .=  '&sort='    .   $this->request->get['sort'];
             }
             
             if(isset($this->request->get['order']))
             {
                 $url   .=  '&order'    .   $this->request->get['order'];
             }
                          
             if(isset($this->request->get['page']))
             {
                 $url   .=  '&page='    .   $this->request->get['page'];
             }

             $this->redirect($this->url->link('setting/location', 'token='   .   $this->session->data['token']   .$url,  'SSL'));   
         }
         
         $this->getList();
     }

     protected function getList()
     {                  
         if(isset($this->request->get['sort']))
         {
             $sort  =   $this->request->get['sort'];
         }  else {
             $sort  =   'name';
         }
         
         if(isset($this->request->get['order']))
         {
             $order =   $this->request->get['order'];
         }  else {
             $order =   'ASC';
         }
         
         if(isset($this->request->get['page']))
         {
             $page  =   $this->request->get['page'];
         }  else {
             $page  =   1;
         }
         
         $url   =   '';
         
         if(isset($this->request->get['sort']))
         {
             $url   .=  '&sort' .   $this->request->get['sort'];
         }
         
         if(isset($this->request->get['order']))
         {
             $url   .=  '&order'    .   $this->request->get['order'];
         }
                          
         if(isset($this->request->get['page']))
         {
             $url   .=  '&page='    .   $this->request->get['page'];         
         }
         
         $this->data['breadcrumbs'] =   array();
         
         $this->data['breadcrumbs'][] =   array(
            'text'      =>  $this->language->get('text_home'),
            'href'      =>  $this->url->link('common/home', 'token=' .   $this->session->data['token'],  'SSL'),
            'separator' =>  false   
         );
         
         $this->data['breadcrumbs'][] =   array(
            'text'      =>  $this->language->get('heading_title'),
            'href'      =>  $this->url->link('setting/location',   'token=' .   $this->session->data['token']   .   $url, 'SSL'),
            'separator' =>  '::'
         );
         
         // Link up buttons to our functions
         $this->data['insert']  =   $this->url->link('setting/location/insert',  'token='    .   $this->session->data['token']   .   $url, 'SSL');
         $this->data['delete']  =   $this->url->link('setting/location/delete',  'token='    .   $this->session->data['token']   .   $url, 'SSL');
         
         $this->data['location']  =   array();
         
         $data  =   array(
            'sort'  =>  $sort,
            'order' =>  $order,
            'start' =>  ($page - 1) * $this->config->get('config_admin_limit'),
            'limit' =>  $this->config->get('config_admin_limit')
         );
         
         // Retrieving array         
         $location_total  =   $this->model_setting_location->getTotalLocation();  //  Retrieve total number of locations from Model
         
         $results   =   $this->model_setting_location->getLocations($data); //  retrieve db information for locations from function in Model
         
          
         foreach($results as $result)
         {
             $action    =   array();
             
             $action[]  =   array(
                'text'  =>  $this->language->get('text_edit'),
                'href'  =>  $this->url->link('setting/location/update',  'token='    .   $this->session->data['token']   .   '&location_id='    .   $result['location_id']  .   $url,   'SSL')
             );
         
             $this->data['location'][] =   array(
                //'location_id'       =>  $result['location_id'],
                'name'              =>  $result['name'],
                'address_1'         =>  $result['address_1'],
                //'address_2'         =>  $result['address_2'],            
                //'city'              =>  $result['city'],      
                //'postcode'          =>  $result['postcode'],  
                //'times'             =>  $result['times'],
                //'comment'           =>  $result['comment'],                    
                'sort_order'        =>  $result['sort_order'],
                //'geocode'           =>  $result['geocode'],                
                'selected'          =>  isset($this->request->post['selected']) && in_array($result['location_id'],    $this->request->post['selected']),
                'action'            =>  $action
             );
         }

         $this->data['heading_title'] = $this->language->get('heading_title');
        
         $this->data['text_no_results'] = $this->language->get('text_no_results');

         $this->data['column_name'] = $this->language->get('column_name');
         $this->data['column_code'] = $this->language->get('column_code');
         $this->data['column_sort_order'] = $this->language->get('column_sort_order');
         $this->data['column_action'] = $this->language->get('column_action');

         $this->data['button_insert'] = $this->language->get('button_insert');
         $this->data['button_delete'] = $this->language->get('button_delete');

         if (isset($this->error['warning'])) {
             $this->data['error_warning'] = $this->error['warning'];
         } else {
             $this->data['error_warning'] = '';
         }

         if (isset($this->session->data['success'])) {
             $this->data['success'] = $this->session->data['success'];
        
             unset($this->session->data['success']);
         } else {
             $this->data['success'] = '';
         }
        
         $url = '';
        
         if ($order == 'ASC') {
             $url .= '&order=DESC';
         } else {
             $url .= '&order=ASC';
         }
        
         if (isset($this->request->get['page'])) {
             $url .= '&page=' . $this->request->get['page'];
         }
                    
         $this->data['sort_name'] = $this->url->link('setting/location', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
         $this->data['sort_code'] = $this->url->link('setting/location', 'token=' . $this->session->data['token'] . '&sort=code' . $url, 'SSL');
         $this->data['sort_sort_order'] = $this->url->link('setting/location', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

         $url = '';

         if (isset($this->request->get['sort'])) {
             $url .= '&sort=' . $this->request->get['sort'];
         }
                                                 
         if (isset($this->request->get['order'])) {
             $url .= '&order=' . $this->request->get['order'];
         }
                
         $pagination = new Pagination();
         $pagination->total = $location_total;
         $pagination->page = $page;
         $pagination->limit = $this->config->get('config_admin_limit');
         $pagination->text = $this->language->get('text_pagination');
         $pagination->url = $this->url->link('setting/location', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
            
         $this->data['pagination'] = $pagination->render();
        
         $this->data['sort'] = $sort;
         $this->data['order'] = $order;

         // Load up our template file!
         $this->template = 'setting/location_list.tpl';   
         $this->children = array(
             'common/header',
             'common/footer'
         );
                
         $this->response->setOutput($this->render());
     }
     
         
     protected function getForm()
     {
        //  Title
        $this->data['heading_title']    = $this->language->get('heading_title');
        //  Status
        $this->data['text_enabled']     = $this->language->get('text_enabled');
        $this->data['text_disabled']    = $this->language->get('text_disabled');
        //  Form fields
        $this->data['entry_name']       = $this->language->get('entry_name');
        $this->data['entry_store_img']  = $this->language->get('entry_store_img');
        $this->data['entry_address_1']  = $this->language->get('entry_address_1');
        $this->data['entry_address_2']  = $this->language->get('entry_address_2');
        $this->data['entry_city']       = $this->language->get('entry_city');
        $this->data['entry_postcode']   = $this->language->get('entry_postcode');
        $this->data['entry_geocode']    = $this->language->get('entry_geocode');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['entry_status']     = $this->language->get('entry_status');
        //  Opening Times
        $this->data['entry_times']      = $this->language->get('entry_times');        
        $this->data['entry_comment']    = $this->language->get('entry_comment');
        
        //  Buttons
        $this->data['button_save']      = $this->language->get('button_save');
        $this->data['button_cancel']    = $this->language->get('button_cancel');        
        $this->data['text_image_manager'] = $this->language->get('text_image_manager');
        $this->data['text_browse']      = $this->language->get('text_browse');
        $this->data['text_clear']       = $this->language->get('text_clear'); 

        //  Quality control
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $this->data['error_name'] = $this->error['name'];
        } else {
            $this->data['error_name'] = '';
        }

        if (isset($this->error['address_1'])) {
            $this->data['error_address_1'] = $this->error['address_1'];
        } else {
            $this->data['error_address_1'] = '';
        }
        
        if (isset($this->error['address_2'])) {
            $this->data['error_address_2'] = $this->error['address_2'];
        } else {
            $this->data['error_address_2'] = '';
        }       
        
        if (isset($this->error['city'])) {
            $this->data['error_city'] = $this->error['city'];
        } else {
            $this->data['error_city'] = '';
        }   
        
        if (isset($this->error['postcode'])) {
            $this->data['error_postcode'] = $this->error['postcode'];
        } else {
            $this->data['error_postcode'] = '';
        }   

        if (isset($this->error['geocode'])) {
            $this->data['error_geocode'] = $this->error['geocode'];
        } else {
            $this->data['error_geocode'] = '';
        }                 
        $url = '';
            
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        
        //  Breadcrumbs
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),            
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('setting/location', 'token=' . $this->session->data['token'] . $url, 'SSL'),           
            'separator' => ' :: '
        );
        
        if (!isset($this->request->get['location_id'])) {
            $this->data['action'] = $this->url->link('setting/location/insert', 'token='    .   $this->session->data['token']   .   $url, 'SSL');
        } else {
            $this->data['action'] = $this->url->link('setting/location/update', 'token='    .    $this->session->data['token']  .  '&location_id=' . $this->request->get['location_id']    .    $url, 'SSL');
        }
        
        $this->data['cancel'] = $this->url->link('setting/location', 'token=' . $this->session->data['token'] . $url, 'SSL');

        if (isset($this->request->get['location_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $location_info = $this->model_setting_location->getLocation($this->request->get['location_id']);
        }
        
        //  Get our images
        $store_image = $this->model_setting_location->getStoreImage($this->request->get['location_id']);
              

        if (isset($this->request->post['name'])) {
            $this->data['name'] = $this->request->post['name'];
        } elseif (!empty($location_info)) {
            $this->data['name'] = $location_info['name'];
        } else {
            $this->data['name'] =   '';
        }

        //  Load up our image tool
        $this->load->model('tool/image');

        $this->data['token'] = $this->session->data['token'];         

        if ($this->config->get('store_img') && file_exists(DIR_IMAGE . $this->config->get('store_img')) && is_file(DIR_IMAGE . $this->config->get('store_img'))) {
            $this->data['store_img'] = $this->model_tool_image->resize($this->config->get('store_img'), 100, 100);     
        } else {
            $this->data['store_img'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
        }

        if (isset($this->request->post['address_1'])) {
            $this->data['address_1'] = $this->request->post['address_1'];
        } elseif (!empty($location_info)) {
            $this->data['address_1'] = $location_info['address_1'];
        } else {
            $this->data['address_1'] = '';
        }

        if (isset($this->request->post['address_2'])) {
            $this->data['address_2'] = $this->request->post['address_2'];
        } elseif (!empty($location_info)) {
            $this->data['address_2'] = $location_info['address_2'];
        } else {
            $this->data['address_2'] = '';
        }
        
        if (isset($this->request->post['city'])) {
            $this->data['city'] = $this->request->post['city'];
        } elseif (!empty($location_info)) {
            $this->data['city'] = $location_info['city'];
        } else {
            $this->data['city'] = '';
        }

        if (isset($this->request->post['postcode'])) {
            $this->data['postcode'] = $this->request->post['postcode'];
        } elseif (!empty($location_info)) {
            $this->data['postcode'] = $location_info['postcode'];
        } else {
            $this->data['postcode'] = '';
        }

        if (isset($this->request->post['geocode'])) {
            $this->data['geocode'] = $this->request->post['geocode'];
        } elseif (!empty($location_info)) {
            $this->data['geocode'] = $location_info['geocode'];
        } else {
            $this->data['geocode'] = '';
        }

        //  Our Optional Opening times & Comments
        if (isset($this->request->post['times'])) {
            $this->data['times']    =   $this->request->post['times'];
        } elseif (!empty($location_info)) {
            $this->data['times']    =   $location_info['times'];        
        } else {
            $this->data['times']    =   '';
        }
        
        if (isset($this->request->post['comment'])) {
            $this->data['comment']  =   $this->request->post['comment'];
        } elseif (!empty($location_info)) {
            $this->data['comment']  =   $location_info['comment'];
        } else {
            $this->data['comment']  =   '';
        }

        if (isset($this->request->post['sort_order'])) {
            $this->data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($location_info)) {
            $this->data['sort_order'] = $location_info['sort_order'];
        } else {
            $this->data['sort_order'] = '';
        }

        if (isset($this->request->post['status'])) {
            $this->data['status'] = $this->request->post['status'];
        } elseif (!empty($location_info)) {
            $this->data['status'] = $location_info['status'];
        } else {
            $this->data['status'] = 1;
        }
        

        $this->template = 'setting/location_form.tpl';   //  Remember to create this template!!
        $this->children = array(
            'common/header',
            'common/footer'
        );
                
        $this->response->setOutput($this->render());         
     }           

     protected function validateForm() 
     {
         
        //  Check user permission has permission to modify form
        if (!$this->user->hasPermission('modify', 'setting/location')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (utf8_strlen($this->request->post['address_1']) < 2) {
            $this->error['address_1'] = $this->language->get('error_address_1');
        }
        
        if (utf8_strlen($this->request->post['address_2']) < 2) {
            $this->error['address_1'] = $this->language->get('error_address_2');
        }        
        
        if (!$this->request->post['city']) { 
            $this->error['city'] = $this->language->get('city'); 
        }

        if (!$this->request->post['postcode']) {
            $this->error['postcode'] = $this->language->get('error_postcode');
        }
        
        if (!$this->request->post['geocode']) {
            $this->error['geocode'] = $this->language->get('error_geocode');
        }
                        

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
     }

    protected function validateDelete() 
    {
        $this->load->model('setting/location');
        
        //  Check user permission has permission to modify form
        if (!$this->user->hasPermission('modify', 'setting/location')) 
        {
            $this->error['warning'] = $this->language->get('error_permission');
        }
                
        if ($this->model_setting_location->getTotalLocation() == 1) 
        {
            $this->error['warning'] = $this->language->get('error_delete');
        }
 
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
    
    protected function getGeocode($address)
    {
            //  Put our values into an array to use
            $geocode_address =   array(
                'address_1'     =>  $address['address_1'],
                'address_2'     =>  $address['address_2'],
                'city'          =>  $address['city'],
                'postcode'      =>  $address['postcode']
            );    
            
            //  Adding in our geocoding             
            $geocode_prep    =   str_replace(" ", "+", $geocode_address['address_1']  .   " "    .   $geocode_address['address_2']    .   " "    .   $geocode_address['city'] .   " "    .   $geocode_address['postcode']);
            
            //  Retreive Google Geocode
            $googleGeocode = "http://maps.googleapis.com/maps/api/geocode/json?address="    .   $geocode_prep .  "&sensor=false";
            $string = file_get_contents($googleGeocode); // get json content
            
            //  Decode JSON
            $json = json_decode($string, true); 
            
            // Decoded, merged Lat Lng value
            $locationReady  =   $json['results'][0]['geometry']['location']['lat']  .   "," .   $json['results'][0]['geometry']['location']['lng']; 
           
            $geocode_data = array(   
                'geocode'   =>    $locationReady
            );
            
            //  Merge our two arrays together ready for DB
            $mapped_address   =   array_merge((array)$address, (array)$geocode_data); 
            
            return  $mapped_address;
        
    }
}
