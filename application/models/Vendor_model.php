<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */

class Vendor_model extends CI_Model
{
    public $em;                         //doctrine entity manager

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }
    
    public function ListAllVendor(){
        $allVendor = $this->doctrine->em->getRepository('Entities\Vendor')->findAll();
        if($allVendor != NULL){
            for($i = 0; $i < count($allVendor); $i++)
            {
                $vendorList[$i] = new stdClass();
                $vendorList[$i]->id = $allVendor[$i]->getId();
                $vendorList[$i]->name = $allVendor[$i]->getName();
            }
            return array("status" => "success", "data" => $vendorList);
        }
        else
            return array("status" => "error", "message" => array("Title" => "No Vendor Found.", "Code" => "200"));
    }
}