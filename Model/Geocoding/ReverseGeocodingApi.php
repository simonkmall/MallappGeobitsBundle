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

namespace Mallapp\GeobitsBundle\Model\Geocoding;

/**
 * Class for generating a name at a given coordinate.
 *
 * @author Simon Mall
 */
class ReverseGeocodingApi {
    
    private static function getUrl($lat, $long) {
        return "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$long."&sensor=false";
    }
    
    /**
     * Returns a location object containing the infos for a given coordinate.
     * @param type $lat Latitude in deg
     * @param type $long Longitude in deg
     * @return SimpleLocation location object
     */
    public static function getLocationFromCoordinate($lat, $long)
    {

        $data = self::getData($lat, $long);

        $country = "";
        $adminAreas = array();
        $topAdminArea = "";
        $locality = "";
        $sublocality = "";
        $postalCode = "";
        $route = "";
        $formattedAddress = "";

        if (count($data['results']) > 0) {
    		 
    		foreach($data['results'][0]['address_components'] as $current_entry) {
    			 
    			foreach($current_entry['types'] as $current_entry2) {
    				 
    				if ($current_entry2 == 'route') {
                                    if ($route == "") { $route = $current_entry['long_name']; }
    				}
    				elseif ($current_entry2 == 'sublocality') {
                                    if ($sublocality == "") { $sublocality = $current_entry['long_name']; }
    				}
    				elseif ($current_entry2 == 'locality') {
                                    if ($locality == "") { $locality = $current_entry['long_name']; }
    				}
                                elseif ($current_entry2 == 'postal_code') {
                                    if ($postalCode == "") { $postalCode = $current_entry['long_name']; }
                                }
    				elseif ($current_entry2 == 'country') {
                                    if ($country == "") { $country = $current_entry['short_name']; }
    				}
                                elseif (substr($current_entry2, 0, 25) == 'administrative_area_level') {
                                    
                                    $key = substr($current_entry2, -1);
                                    $adminAreas[$key] = $current_entry['long_name'];
                                    
                                }
    			}
    			 
    		}
                
                if (array_key_exists('formatted_address', $data['results'][0])) {
                    
                    $formattedAddress = $data['results'][0]['formatted_address'];
                    
                }
    		 
    	}
        
        if (array_key_exists('1', $adminAreas)) {
            $topAdminArea = $adminAreas['1'];
        }
        else {
            
            if (array_key_exists('2', $adminAreas)) {
                $topAdminArea = $adminAreas['2'];
            }
            else {
                
                if (array_key_exists('3', $adminAreas)) {
                    $topAdminArea = $adminAreas['3'];
                }
                else {
                    
                    if (array_key_exists('4', $adminAreas)) {
                        $topAdminArea = $adminAreas['4'];
                    }
                    else {
                        
                        if (array_key_exists('5', $adminAreas)) {
                            $topAdminArea = $adminAreas['5'];
                        }

                    }

                }

            }
            
        }
        
        $nickname = "";
                
        if($route != "") { $nickname = $route." "; }
        if($locality != "") { $nickname = $nickname.$locality." "; }
        if($sublocality != "") { $nickname = $nickname."- ".$sublocality." "; }
    	 
    	if (strlen($nickname) < 2) {
    		$characters1 = 'aeiou';
    		$characters2 = 'bcdfghjklmnpqrstvwxyz';
    		$nickname = '';
    		for ($j = 0; $j < 3; $j++) {
    			$nickname .= $characters1[rand(0, strlen($characters1) - 1)];
    			$nickname .= $characters2[rand(0, strlen($characters2) - 1)];
    		}
    		$nickname .= " ";
    		$nickname = ucfirst($nickname);
    		 
    	}
    	 
        if($country != "") { $nickname = $nickname."(".$country.")"; }

    	$locationObject = new SimpleLocation();
        
        $locationObject->latitude = $lat;
        $locationObject->longitude = $long;
        $locationObject->administrativeArea = $topAdminArea;
        $locationObject->city = $locality;
        $locationObject->countryCode = $country;
        $locationObject->postalCode = $postalCode;
        $locationObject->route = $route;
        $locationObject->nickname = $nickname;
        $locationObject->formattedAddress = $formattedAddress;
        
        return $locationObject;
    
    }
    

    private static function getData($lat, $long)
    {
        
        $ch = curl_init();

    	$google_url = self::getUrl($lat, $long);
        
    	curl_setopt($ch, CURLOPT_URL, $google_url);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    	$raw_data = curl_exec($ch);
        
        curl_close($ch);
        
    	return json_decode($raw_data, TRUE);
        
    }
            
    
    
}
