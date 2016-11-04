<?php

/*
 * The MIT License
 *
 * Copyright 2016 Simon Mall.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Mallapp\GeobitsBundle\Model;

/**
 * Class for generating a name at a given coordinate.
 *
 * @author Simon Mall
 */
class NameGenerator {
    
    static public function createName($lat, $long)
    {
    	 
    	$ch = curl_init();

    	$google_url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$long."&sensor=false";
    	curl_setopt($ch, CURLOPT_URL, $google_url);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	 
    	$my_route = "";
    	$my_sublocality = "";
    	$my_locality = "";
    	$my_country = "";
    	$final_name = "";
    	 
    	$raw_data = curl_exec($ch);
    	$data = json_decode($raw_data, TRUE);
    	 
    	if (count($data['results']) > 0) {
    		 
    		foreach($data['results'][0]['address_components'] as $current_entry) {
    			 
    			foreach($current_entry['types'] as $current_entry2) {
    				 
    				if ($current_entry2 == 'route') {
                                        if ($my_route == "") { $my_route = $current_entry['long_name']; }
    				}
    				elseif ($current_entry2 == 'sublocality') {
    					if ($my_sublocality == "") { $my_sublocality = $current_entry['long_name']; }
    				}
    				elseif ($current_entry2 == 'locality') {
                                        if ($my_locality == "") { $my_locality = $current_entry['long_name']; }
    				}
    				elseif ($current_entry2 == 'country') {
                                        if ($my_country == "") { $my_country = $current_entry['short_name']; }
    				}
    			}
    			 
    		}
    		 
    	}
    	 
        if($my_route != "") { $final_name = $my_route." "; }
        if($my_locality != "") { $final_name = $final_name.$my_locality." "; }
        if($my_sublocality != "") { $final_name = $final_name."- ".$my_sublocality." "; }
    	 
    	if (strlen($final_name) < 2) {
    		$characters1 = 'aeiou';
    		$characters2 = 'bcdfghjklmnpqrstvwxyz';
    		$final_name = '';
    		for ($j = 0; $j < 3; $j++) {
    			$final_name .= $characters1[rand(0, strlen($characters1) - 1)];
    			$final_name .= $characters2[rand(0, strlen($characters2) - 1)];
    		}
    		$final_name .= " ";
    		$final_name = ucfirst($final_name);
    		 
    	}
    	 
        if($my_country != "") { $final_name = $final_name."(".$my_country.")"; }
    	 
    	curl_close($ch);
    	 
    	return $final_name;
    
    
    }
    
    
}
