<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */

class Places_model extends CI_Model
{
    public $em;                         //doctrine entity manager

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }
    
    public function ReadAllCities($countryCode, $input){
        $url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=" . $input . "&types=(cities)&components=country:" . $countryCode . "&key=AIzaSyC9_4QA8rEHKMcsMG700uPTOxvqEz3oxCE";
        
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, false);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $data = json_decode(curl_exec($ch));
        if ($data === FALSE) {
            die('error Curl failed: ' . curl_error($ch));
        }
        
        //print_r($data->predictions);exit;
 
        // Close connection
        curl_close($ch);        
        //return $result;
        
        foreach($data->predictions as $suggestion)
            $array[] = $suggestion->description;
        
        if(isset($data) && count($data) > 0)
            return array("status" => "success", "data" => $array);
        else
            return array("status" => "error", "message" => array("Title" => "No Data Found.", "Code" => "200"));
    }
}