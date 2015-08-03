<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */

defined('BASEPATH') OR exit('Forbidden!');

class Places extends CI_Controller
{
    public function index()
    {
        echo "Default Controller Action For Deals.";
    }
    
    public function GetCities(){
        if(preg_match("/[a-zA-Z]{2}/", $countryCode = isset($_POST['countryCode']) ? trim($_POST['countryCode']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Country $countryCode.", "Code" => "400")));
            exit;
        }
        
        if(preg_match("/[a-zA-Z\s]{1,15}/", $input = isset($_POST['input']) ? trim($_POST['input']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Search Prefix.", "Code" => "400")));
            exit;
        }
        
        $this->load->model('Places_model');
        echo json_encode($this->Places_model->ReadAllCities($countryCode, $input));
    }
}