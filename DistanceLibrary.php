<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class DistanceLibrary {

		function get_distance_haversine($lat1, $lon1, $lat2, $lon2, $unit='K') {
		
		  $theta = $lon1 - $lon2;
		  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		  $dist = acos($dist);
		  $dist = rad2deg($dist);
		  $miles = $dist * 60 * 1.1515;
		  $unit = strtoupper($unit);
		
// 		  if ($unit == "K") {
// 		    return ($miles * 1.609344);
// 		  } else if ($unit == "N") {
// 		    return ($miles * 0.8684);
// 		  } else {
// 		  	return $miles;
// 		  }

		  return $miles * 1.61; // in Kms
		}
		
		function get_distance_googledistmatrix($lat0, $lon0, $lat1, $lon1){
			
			$g_map_query="";
			$g_map_query="origins={$lat0},{$lon0}&destinations={$lat1},{$lon1}";
			 
			$url = "https://maps.googleapis.com/maps/api/distancematrix/json?".$g_map_query."&language=en&key=AIzaSyBsZol4eVtTq4cjwd3UDATW9vQcfQugF28";
			 
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Receive server response
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			 
			$response = curl_exec($curl);
		
			$resp_arr= json_decode($response, true);
		
			//return $resp_arr['rows'][0]['elements'][0]['distance']['text'];
			//print_r($resp_arr);
			if(!empty($resp_arr['rows'])){
				return $resp_arr['rows'][0]['elements'][0]['distance'];
			}
			else{
				// get haversine
				$hd=$this->get_distance_haversine($lat0, $lon0, $lat1, $lon1);
				return array('value'=>round($hd*1000, 0), 'text'=>round($hd, 1).' Km');
			}
		}
		
}