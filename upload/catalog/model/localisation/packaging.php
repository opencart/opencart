<?php
final class Packaging {
	private $packages = array();
	private $box  = array();
	
	public function __construct($product) {
		$this->db = Registry::get('db');
		
		$boxesToShip = $this->packProducts($product);
		
		
		
	}
	
	public function addItem($length, $width, $height, $weight) {
        if ((float)$weight < 1.0) {
            $weight = 1;
        } else {
            $weight = round($weight, 1);
        }
        
		$index = $this->items_qty;
		
        $this->item[$index]['item_length'] = ($length ? (string)$length : '0' );
        $this->item[$index]['item_width'] = ($width ? (string)$width : '0' );
        $this->item[$index]['item_height'] = ($height ? (string)$height : '0' );
        $this->item[$index]['item_weight'] = ($weight ? (string)$weight : '0' );
        $this->item[$index]['item_price'] = $price;
        
		$this->items_qty++;		
	}
	
	public function getPackagesByVol() {
		$i = 0;
		
		$package_data = array();
		
		$query = $this->db->query("SELECT *, (length * width * height) as volume FROM packaging ORDER BY volume");
	
		foreach ($query->rows as $result) {
			$package_data[$i] = array(
				'packaging_id' => $result['package_id'],
				'name'         => $result['name'],
				'description'  => $result['description'],
				'length'       => $result['length'],
				'width'        => $result['width'],
				'height'       => $result['height'],
				'weight'       => $result['weight'],
				'volume'       => $result['volume']								  
			);
			
			$dimensions = array($result['length'], $result['width'], $result['height']);
			
			sort($dimensions);
			
			foreach($dimensions as $key => $value) {
				if ($key == 0) { 
					$package_data[$i]['x'] = $value; 
				}
				
				if ($key == 1) {
					$package_data[$i]['y'] = $value;
				}
				
				if ($key == 2) {
					$package_data[$i]['z'] = $value;
				}
			}
			
			$i++;			
		}

		$box_empty_data = array();
		 
        for ($i = 0; $i < count($packages); $i++) {
            $box = $packages[$i];
            $box['remaining_volume'] = $box['volume'];
            $box['current_weight'] = $box['empty_weight'];
           
		   	$box_empty[] = $box;
        }


		return $packages;
	}

	public function packProducts($products) {
		$packages = $this->getPackagesByVol();
		
		$box_empty = array();
		 
        for ($i = 0; $i < count($packages); $i++) {
            $box = $packages[$i];
            $box['remaining_volume'] = $box['volume'];
            $box['current_weight'] = $box['empty_weight'];
           
		   	$box_empty[] = $box;
        }
		
        $box_packed = array();
        $box_current = NULL;
        $box_largest = count($box_empty) - 1;
		
		$products_remaining = array();
		
		for ($i = 0; $i < count($products); $i++) {
			$product = $products[$i];
			
			if ((int)$product['ready_to_ship'] == 0) {
				$product['ready_to_ship'] = '1';
				
				for ($x = 0; $x <= $box_largest; $x++) {
					if ($this->fitsInBox($product, $box_empty[$x])) {
		 				$product['ready_to_ship'] = '0';
						$product['largest_box_it_will_fit'] = $x;
					} 
				}
			}
		
			for ($j = 0; $j < $products[$i]['quantity']; $j++) {
				$products_remaining[] = $product;
			}
		}	
		
        usort($products_remaining, ready_to_shipCmp);
        
        $index_of_largest_box_to_use = count($box_empty) -1;
		
        while (count($products_remaining)) {
            if ($products_remaining[0]['ready_to_ship'] == '1') {
                $box_packed[] = array(
					'length'         => $products_remaining[0]['length'],
					'width'          => $products_remaining[0]['width'],
					'height'         => $products_remaining[0]['height'],
					'current_weight' => $products_remaining[0]['weight'],
					'price'          => $products_remaining[0]['final_price']
				);
                
				$products_remaining = array_slice($products_remaining, 1);
                
				continue;
            }

			for ($b = 0; $b < count($box_empty) && $products_remaining; $b++) {
				$result = $this->fitProductsInBox($products_remaining, $box_empty[$b], $box_packed, $b, $index_of_largest_box_to_use);
				
				$box_packed = $result['packed_boxes'];
				$products_remaining = $result['remaining'];
				
				if (isset($result['index_of_largest_box_to_use']) && $result['index_of_largest_box_to_use'] >= 0) {
					$index_of_largest_box_to_use = $result['index_of_largest_box_to_use'];
				}
			}
        }

        return $box_packed;
	}
	
    function fitsInBox($product, $box) {
        if ($product['x'] > $box['x'] || $product['y'] > $box['y'] || $product['z'] > $box['z']) {
            return FALSE;
        } 

        if ($product['volume'] <= $box['remaining_volume']) {
            if ($box['max_weight'] == 0 || ($box['current_weight'] + $product['weight'] <= $box['max_weight'])) {
                return TRUE;
            }
        }
		
        return FALSE;
    }	
	
    function putProductInBox($product, $box) {
        $box['remaining_volume'] -= $product['volume'];
        $box['products'][] = $product;
        $box['current_weight'] += $product['weight'];
        $box['price'] += $product['final_price'];
        
		return $box;
    } 
	
    function fitProductsInBox($products_remaining, $box_empty, $box_packed, $box_no, $index_of_largest_box) { 
        $currentBox = $emptyBox;
		
        $productsRemainingSkipped = array();
        
		$productsRemainingNotSkipped = array();
        
		$largest_box_in_skipped_products = -1;
        
		// keep apart products that will not fit this box anyway
		for ($p = 0; $p < count($productsRemaining); $p++) {
			if ($productsRemaining[$p]['largest_box_it_will_fit'] < $box_no) {
				$productsRemainingSkipped[] = $productsRemaining[$p];
		
				if ($productsRemaining[$p]['largest_box_it_will_fit'] > $largest_box_in_skipped_products) {
		  			$largest_box_in_skipped_products = $productsRemaining[$p]['largest_box_it_will_fit'];
				}
			} else {
				$productsRemainingNotSkipped[] = $productsRemaining[$p];
			}
		}

        unset($productsRemaining);
        $productsRemaining = $productsRemainingNotSkipped;
        unset($productsRemainingNotSkipped);
        if (count($productsRemaining) == 0) {
          // products remaining are the ones that will not fit this box (productsRemaimingSkipped)
            $result_array = array('remaining' => $productsRemainingSkipped, 'box_no' => $box_no, 'packed_boxes' => $packedBoxesArray, 'index_of_largest_box_to_use' => $largest_box_in_skipped_products);
            return ($result_array);
        }

        //Try to fit each product that can fit in box
        for ($p = 0; $p < count($productsRemaining); $p++) {
            if ($this->fitsInBox($productsRemaining[$p], $currentBox)) {
                //It fits. Put it in the box.
                $currentBox = $this->putProductInBox($productsRemaining[$p], $currentBox);
                if ($p == count($productsRemaining) - 1) {
                    $packedBoxesArray[] = $currentBox;
                    $productsRemaining = array_slice($productsRemaining, $p + 1);
                    $productsRemaining = array_merge($productsRemaining, $productsRemainingSkipped);

                    $result_array = array('remaining' => $productsRemaining, 'box_no' => $box_no, 'packed_boxes' => $packedBoxesArray);
                    return ($result_array);
                }
            } else {
                if ($box_no == $index_of_largest_box) {
                    //We're at the largest box already, and it's full. Keep what we've packed so far and get another box.
                    $packedBoxesArray[] = $currentBox;
                    $productsRemaining = array_slice($productsRemaining, $p);
                    $productsRemaining = array_merge($productsRemaining, $productsRemainingSkipped);
                    $result_array = array('remaining' => $productsRemaining, 'box_no' => $box_no, 'packed_boxes' => $packedBoxesArray);
                    return ($result_array);
                }
                // Not all of them fit. Stop packing remaining products and try next box.
                $productsRemaining = array_merge($productsRemaining, $productsRemainingSkipped);
                $result_array = array('remaining' => $productsRemaining, 'box_no' => $box_no, 'packed_boxes' => $packedBoxesArray);
                return ($result_array);
            } // end else
        } // end for ($p = 0; $p < count($productsRemaining); $p++)
    } // end function fitProductsInBox
    
// ******************************
  function more_dimensions_to_productsArray($productsArray) {
$counter = 0;
foreach ($productsArray as $key => $product) {
// in case by accident or by choice length, width or height is not set
// we will estimate it by using a set density and the product['weight'] variable
// will only be used in the check for whether it fits the largest box
// after that it will already be set, if product['weight'] is set at least
if ($product['length'] == 0 || $product['width'] == 0 || $product['height'] == 0) {
$density = 0.7;
if ($this->unit_length == 'CM') {
	$product['length']=$product['width']=$product['height']= round(10*(pow($product['weight']/$density, 1/3)),1);
} else {
	// non-metric: inches and pounds
	$product['length']=$product['width']=$product['height']= round(pow($product['weight']*27.67/$density, 1/3),1);
}
} // end if ($product['length'] == 0 || $product['width'] == 0 etc.
// sort dimensions from low to high, used in the function fitsInBox
$dimensions = array($product['length'], $product['width'], $product['height']);
sort($dimensions);
foreach($dimensions as $key => $value) {
if ($key == 0 ) { $productsArray[$counter]['x'] = $value; }
if ($key == 1 ) { $productsArray[$counter]['y'] = $value; }
if ($key == 2 ) { $productsArray[$counter]['z'] = $value; }
}
$productsArray[$counter]['volume'] = $product['length'] * $product['width'] * $product['height'];
$counter++;
} // end foreach ($productsArray as $key => $product)
return($productsArray);
  }

	public function getPackedBoxes() {
		return $this->item;
	}
	
	public function getTotalWeight() {
		return $this->totalWeight;
	}
	
	public function getNumberOfBoxes() {
		return $this->items_qty;
	}
}
?>