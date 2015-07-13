<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */

defined('BASEPATH') OR exit('Forbidden!');

class Vendor extends CI_Controller
{
    public function index()
    {
        echo "Default Controller Action For Category.";
    }
    
    public function Add(){
        
    }
    
    public function Listing(){
        /*if(isset($_SESSION['vendorId']) && ($vendorId = $_SESSION['vendorId']) != "")
        {*/
            $this->load->model('Vendor_model');
            echo json_encode($this->Vendor_model->ListAllVendor());
        /*}
        else
            echo json_encode(array("status" => "error", "message" => array("Title" => "Authentication Failure.", "Code" => "401")));*/
    }
}