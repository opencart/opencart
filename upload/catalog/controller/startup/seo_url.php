<?php
class ControllerStartupSeoUrl extends Controller {
    private $regex   = array();
    private $keyword = array();

    public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}

		$this->load->model('design/seo_url');
		$this->load->model('design/seo_regex');

		// Load all regexes in the var so we are not accessing the db so much.
		$results = $this->model_design_seo_regex->getSeoRegexes();

		foreach ($results as $result) {
		    //$this->regex[$result['key']][] = '/' . $result['regex'] . '/';
		}

		// Decode URL
		if (isset($this->request->get['_route_'])) {
		    /*
            echo __METHOD__ . ": route: '{$this->request->get['_route_']}'<br />\n";
            echo __METHOD__ . ": req dmp:\n";
            var_dump($this->request);
            echo "<br />\n";
            */

			$parts = explode('/', $this->request->get['_route_']);

			//echo "arr parts: '" . print_r($parts, true) . "'<br />\n";

			// remove any empty arrays from trailing
			if (utf8_strlen(end($parts)) == 0) {
				array_pop($parts);
			}

			foreach ($parts as $part) {
			    $results = $this->model_design_seo_url->getSeoUrlsByKeyword($part);

			    //echo "rs seo url: '" . print_r($results, true) . "'<br />\n";

			    if ($results) {
			        foreach ($results as $result) {
			            $data = array();

			            // Push additional query string vars into GET data
			            parse_str($result['push'], $data);

			            foreach ($data as $key => $value) {
			                $this->request->get[$key] = $value;
			            }
			        }
				} else {
					$this->request->get['route'] = 'error/not_found';

					break;
				}
			}    //foreach ($parts as $part)

			if (isset($this->request->get['product_id']))
			    $this->request->get['route'] = 'product/product';

			//echo "rs arr get: '" . print_r($this->request->get, true) . "'<br />\n";

		} //if (isset($this->request->get['_route_']))
	}

	public function rewrite($link) {
        //echo __METHOD__ . ": link: '$link'<br />\n";

		$url_info = parse_url(str_replace('&amp;', '&', $link));

        //echo "url info: '" . print_r($url_info, true) . "'<br />\n";

		$url = '';

		$data = array();

		parse_str($url_info['query'], $data);

        //echo "query data: '" . print_r($data, true) . "'<br />\n";

        if(isset($data['route']))
        {
          switch($data['route'])
          {
            case "product/product":
              if (isset($data['path'])) {
                  $categories = explode('_', $data['path']);
                  $categorysearch = '';

                  foreach ($categories as $category) {
                      if ($categorysearch !== '')
                          $categorysearch .= '_';

                      $categorysearch .= $category;

                      $skeyword = $this->model_design_seo_url->getKeywordByQuery('path=' . $categorysearch);

                      if ($skeyword !== '') {
                          $url .= '/' . $skeyword;
                      } else {
                          //The URL is not defined yet
                          //Leave it unchanged
                          $url = '';

                          break;
                      }  //if ($skeyword !== '')
                  }  //foreach ($categories as $category)

                unset($data['path']);
              } //if (isset($data['path']))

              foreach ($data as $key => $value) {
                  //echo "key: '$key': value: '$value'\n";

                if (isset($data['route'])) {
                  if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
                    $skeyword = $this->model_design_seo_url->getKeywordByQuery($key . '=' . (int)$value);

                    if ($skeyword !== '') {
                        $url .= '/' . $skeyword;

                      unset($data[$key]);
                    }
                  } elseif ($key == 'path') {
                    $categories = explode('_', $value);
                    $categorysearch = '';

                    foreach ($categories as $category) {
                        if ($categorysearch !== '')
                            $categorysearch .= '_';

                        $categorysearch .= $category;

                        $skeyword = $this->model_design_seo_url->getKeywordByQuery('path=' . $categorysearch);

                        if ($skeyword !== '') {
                            $url .= '/' . $skeyword;
                        } else {
                            //The URL is not defined yet
                            //Leave it unchanged
                            $url = '';

                            break;
                        }  //if ($skeyword !== '')
                    }  //foreach ($categories as $category)

                    unset($data[$key]);
                  }
                }   //if (isset($data['route']))
              } //foreach ($data as $key => $value)
              break;

            case "product/category":
              if (isset($data['path'])) {
                  $categories = explode('_', $data['path']);
                  $categorysearch = '';

                  foreach ($categories as $category) {
                      if ($categorysearch !== '')
                          $categorysearch .= '_';

                      $categorysearch .= $category;

                      $skeyword = $this->model_design_seo_url->getKeywordByQuery('path=' . $categorysearch);

                      if ($skeyword !== '') {
                          $url .= '/' . $skeyword;
                      } else {
                          //The URL is not defined yet
                          //Leave it unchanged
                          $url = '';

                          break;
                      }
                  }  //foreach ($categories as $category)

                unset($data['path']);
              } //if (isset($data['path']))
              break;

            case "product/manufacturer/info":
            case "information/information":
                foreach ($data as $key => $value) {
                    if (isset($data['route'])) {
                  if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
                    $skeyword = $this->model_design_seo_url->getKeywordByQuery($key . '=' . (int)$value);

                    if ($skeyword !== '') {
                        $url .= '/' . $skeyword;

                      unset($data[$key]);
                    }
                  } elseif ($key == 'path') {
                    $categories = explode('_', $value);
                    $categorysearch = '';

                    foreach ($categories as $category) {
                        if ($categorysearch !== '')
                            $categorysearch .= '_';

                        $categorysearch .= $category;

                        $skeyword = $this->model_design_seo_url->getKeywordByQuery('path=' . $categorysearch);

                        if ($skeyword !== '') {
                            $url .= '/' . $skeyword;
                        } else {
                            //The URL is not defined yet
                            //Leave it unchanged
                            $url = '';

                            break;
                        } //if ($query->num_rows && $query->row['keyword'])
                    }  //foreach ($categories as $category)

                    unset($data[$key]);
                  }
                }
              } //foreach ($data as $key => $value)
              break;

            default:
                $skeyword = $this->model_design_seo_url->getKeywordByQuery($data['route']);

                if ($skeyword !== '') {
                    $url .= '/' . $skeyword;
                }
              break;

          } //switch($data['route'])
        } //if(isset($data['route']))

            //echo "res url: '$url'<br />\n";

    		if ($url) {
    			unset($data['route']);

            $link = "";
			$query = '';

			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . rawurlencode((string)$key) . '=' . rawurlencode((is_array($value) ? http_build_query($value) : (string)$value));
				}

				if ($query) {
					$query = '?' . str_replace('&', '&amp;', trim($query, '&'));
				}
			}

            if(!empty($url_info['scheme']))
                $link = $url_info['scheme'];

            if(!empty($url_info['host']))
            {
                if(!empty($link))
                  $link .= ":";

                $link .= "//" . $url_info['host'];
            } //if(!empty($url_info['host']))

            if(isset($url_info['port']))
                $link .= $url_info['port'];

            $link = str_replace('/index.php', '', $url_info['path']) . $url . $query;

			return $link;
		} else {
			return $link;
		}
	}
}
