<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */
session_start();
defined('BASEPATH') OR exit('Forbidden!');

class Deals extends CI_Controller
{
    public function index()
    {
        echo "Default Controller Action For Deals.";
    }
    
    public function Add(){
        if(isset($_SESSION['vendorId']) && ($vendorId = $_SESSION['vendorId']) != "")
        {
            if(preg_match("/[0-9]{1,5}/", $categoryId = isset($_POST['categoryId']) ? trim($_POST['categoryId']) : "") == 0)
            {
                echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Category ID.", "Code" => "400")));
                exit;
            }

            if(preg_match("/[0-9a-zA-Z\.\_\/\\\]{1,160}/", $thumbnailImg = isset($_POST['thumbnailImg']) ? trim($_POST['thumbnailImg']) : "") == 0)
            {
                echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Thumbnail Image Link.", "Code" => "400")));
                exit;
            }

            if(preg_match("/[0-9a-zA-Z\.\_\/\\\]{1,160}/", $bigImg = isset($_POST['bigImg']) ? trim($_POST['bigImg']) : "") == 0)
            {
                echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Large Image Link.", "Code" => "400")));
                exit;
            }

            if(preg_match("/^\w[a-zA-A0-9\.\,\s\/\\\]{1,30}/", $region = isset($_POST['region']) ? trim($_POST['region']) : "") == 0)
            {
                echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Region.", "Code" => "400")));
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
            
            if(preg_match("/[a-zA-Z0-9\-\_\.\,\s\\\]{0,50}/", $expiresOn = isset($_POST['expiresOn']) ? trim($_POST['expiresOn']) : "") == 0)
            {
                echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid Expiry Details (YYYY-MM-DD hh:mm:ss).", "Code" => "400")));
                exit;
            }
            
            $likes = 0;
            $views = 0;
            $pseudoViews = 0;
            $status = 1;

            $this->load->model('Deals_model');
            echo json_encode($this->Deals_model->CreateDeals($categoryId, $vendorId, $thumbnailImg, $bigImg, $region, $shortDesc, $longDesc, $likes, $views, $pseudoViews, $expiresOn, $status));
        }
        else
            echo json_encode(array("status" => "error", "message" => array("Title" => "Authentication Failure.", "Code" => "401")));
    }
    
    public function GetMyDeals(){
        if(preg_match("/[0-9]{1,10}/", $userId = isset($_POST['userId']) ? trim($_POST['userId']) : "") == 0)
        {
            echo json_encode(array("status" => "error", "message" => array("Title" => "Invalid User ID.", "Code" => "400")));
            exit;
        }
        $this->load->model('Deals_model');
        return json_encode($this->Deals_model->ReadUserDeals($userId));
    }
}