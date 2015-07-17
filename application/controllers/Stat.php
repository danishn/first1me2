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
<<<<<<< HEAD
        //@header("content-type:application/json");
        //$this->load->model("User_model");
=======
>>>>>>> eb1de31ba976afdb1fdeb89d6597609e1c718c7a
    }
    
    public function index()
    {
        echo "Default Controller Action For Stat.";
    }
    
    public function Category(){
        echo json_encode($this->Stat_model->ReadCategoryStat());
    }
    
    public function User(){
        echo json_encode($this->Stat_model->ReadUserStat());
    }
    
    public function Deals(){
        echo json_encode($this->Stat_model->ReadDealsStat());
    }
    
    public function Vendor(){
        echo json_encode($this->Stat_model->ReadVendorStat());
    }
    
    public function DashBoard(){
		//echo "welcome";exit;
        echo json_encode($this->Stat_model->ReadDashBoardStat());
    }
    
    public function Test(){
        echo "hello";
    }
}