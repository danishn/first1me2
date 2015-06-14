<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */

defined('BASEPATH') OR exit('Forbidden!');

class Category extends CI_Controller
{
    public function index()
    {
        echo "Default Controller Action For Category.";
    }
    
    public function Add(){
        /*echo $this->input->post('displayName');
        print_r($_POST);
        echo "displayName = " . $this->input->post('displayName') . ".\n";*/
        if(preg_match("/^\w[a-zA-Z0-9\-\_\.\,\s\\\]{1,30}/", $displayName = isset($_POST['displayName']) ? trim($_POST['displayName']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Display Name.", "Code" => "400")));
            exit;
        }
        
        if(preg_match("/^\w[a-zA-Z0-9\-\_\.\,\s\\\]{1,255}/", $shortDesc = isset($_POST['shortDesc']) ? trim($_POST['shortDesc']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Short Description.", "Code" => "400")));
            exit;
        }
                
        if(preg_match("/^\w[a-zA-Z0-9\-\_\.\,\s\\\]{1,}/", $longDesc = isset($_POST['longDesc']) ? trim($_POST['longDesc']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Long Description.", "Code" => "400")));
            exit;
        }
        
        /*$createdOn = addcslashes(mysqli_real_escape_string($mysqli, $_POST['createdOn']), "%_");
        if(($status = addcslashes(mysqli_real_escape_string($mysqli, $_POST['status']), "%_")) == "")
            return json_encode(array("status" => "error", "message" => "Short Description Not Found."));*/
        $status = 1;
        $pseudoSubscriptionCount = 0;
        
        $this->load->model('CategoryModel');
        echo json_encode($this->CategoryModel->CreateCategory($displayName, $shortDesc, $longDesc, $status, $pseudoSubscriptionCount));
    }
    
    public function GetAll(){
        $this->load->model('CategoryModel');
        echo json_encode($this->CategoryModel->ReadAllCategory());
    }
    
    public function Subscribe(){
        if(preg_match("/[0-9]{1,10}/", $userId = isset($_POST['userId']) ? trim($_POST['userId']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid User ID.", "Code" => "400")));
            exit;
        }
        
        if(preg_match("/[0-9]{1,5}/", $categoryId = isset($_POST['categoryId']) ? trim($_POST['categoryId']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Category ID.", "Code" => "400")));
            exit;
        }
        
        $this->load->model('CategoryModel');
        echo json_encode($this->CategoryModel->CreateSubscription($userId, $categoryId));
    }
}