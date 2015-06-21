<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */

defined('BASEPATH') OR exit('Forbidden!');

class User extends CI_Controller
{
    public function index()
    {
        echo "Default Controller Action For User.";
    }
    
    public function Registration(){
        if(preg_match("/^\w[a-zA-Z\s]{1,20}/", $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid First Name.", "Code" => "400")));
            exit;
        }
        
        if(preg_match("/^\w[a-zA-Z\s]{1,20}/", $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Last Name.", "Code" => "400")));
            exit;
        }
        
        if(preg_match("/^[a-z][a-z0-9\.\_]*@[a-z][a-z0-9\.]+[a-z]$/", $email = isset($_POST['email']) ? trim($_POST['email']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Email Address.", "Code" => "400")));
            exit;
        }
        
        if(preg_match("/[0-9]{10}/", $mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Mobile No.", "Code" => "400")));
            exit;
        }
        
        if(preg_match("/[a-zA-Z]{20}/", $country = isset($_POST['country']) ? trim($_POST['country']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Country Name.", "Code" => "400")));
            exit;
        }
        
        if(preg_match("/[a-zA-Z]{20}/", $city = isset($_POST['city']) ? trim($_POST['city']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid City Name.", "Code" => "400")));
            exit;
        }
        
        if(preg_match("/[a-zA-Z0-9\'\"\s\.\,\-\+\/\\\]{4,250}/", $password = isset($_POST['password']) ? trim($_POST['password']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Password.", "Code" => "400")));
            exit;
        }
        
        $GCMID = isset($_POST['GCMID']) ? $_POST['GCMID'] : "-";    //later this field will be mandatory
        $fbStatus = 0;
        
        $this->load->model('UserModel');
        echo json_encode($this->UserModel->CreateUser($GCMID, $firstName, $lastName, $email, $mobile, $country, $city, $password, $fbStatus));
    }
    
    public function FacebookShare(){
        if(preg_match("/[0-9]{1,10}/", $userId = isset($_POST['userId']) ? trim($_POST['userId']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid User ID.", "Code" => "400")));
            exit;
        }
        
        $this->load->model('UserModel');
        echo json_encode($this->UpdateFacebookStatus($userId));
    }
    
    public function Login(){
        if(preg_match("/^[a-z][a-z0-9\.\_]*@[a-z][a-z0-9\.]+[a-z]$/", $email = isset($_POST['email']) ? trim($_POST['email']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Email Address.", "Code" => "400")));
            exit;
        }
        
        if(preg_match("/[a-zA-Z0-9\'\"\s\.\,\-\+\/\\\]{4,250}/", $password = isset($_POST['password']) ? trim($_POST['password']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Password.", "Code" => "400")));
            exit;
        }
        
        $this->load->model('UserModel');
        echo json_encode($this->Login($email, $password));
    }
}