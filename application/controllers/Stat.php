<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */

defined('BASEPATH') OR exit('Forbidden!');

class Stat extends CI_Controller
{
    function __construct(){
        parent::__construct(); 

        $this->load->model("Stat_model", "Stat_model");
        header("content-type:application/json");
        //$this->load->model("User_model");
    }
    
    public function index()
    {
        echo "Default Controller Action For Stat.";
    }
    
    public function Category(){
        echo json_encode($this->Stat_model->ReadCategoryStat());
    }
    
    public function User(){
        echo json_encode($this->Stat_model->ReadUserStat($start = (isset($_POST['start']) && $_POST['start'] != "") ? intval($_POST['start']) : 0));
    }
    
    public function Deals(){
        echo json_encode($this->Stat_model->ReadDealsStat($start = (isset($_POST['start']) && $_POST['start'] != "") ? intval($_POST['start']) : 0));
    }
    
    public function Vendor(){
        echo json_encode($this->Stat_model->ReadVendorStat());
    }
    
    public function DashBoard(){
        echo json_encode($this->Stat_model->ReadDashBoardStat());
    }
    
    public function Test(){
        echo "hello";
    }
}