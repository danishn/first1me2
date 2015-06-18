<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */

class Category_model extends CI_Model
{
    public $em;                         //doctrine entity manager

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }
    
    public function CreateCategory($displayName, $shortDesc, $longDesc, $status, $pseudoSubscriptionCount){
        $category = new Entities\Category;
        
        $category->setDisplayname($displayName);
        $category->setShortdesc($shortDesc);
        $category->setLongdesc($longDesc);
        $category->setCreatedon(new \DateTime("now"));
        
        $category->setStatus($status);
        $category->setPseudosubscriptioncount($pseudoSubscriptionCount);
        
        try
        {
            $this->em->persist($category);
            $this->em->flush();
            return array("status" => "success", "data" => array("Category Added Successfully."));
        }
        catch(Exception $exc)
        {
            return array("status" => "error", "message" => array("Title" => $exc->getTraceAsString()));
        }
    }
    
    
    public function ReadAllCategory(){
        //return Doctrine::getTable('category')->findAll();
        //$data = $this->doctrine->em->find('Entities\Category', 1);
        $allCategory = $this->doctrine->em->getRepository('Entities\Category')->findAll();
        for($i = 0; $i < count($allCategory); $i++)
        {
            $data[$i] = new stdClass();
            $data[$i]->id = $allCategory[$i]->getId();
            $data[$i]->displayName = $allCategory[$i]->getDisplayname();
            $data[$i]->shortDescription = $allCategory[$i]->getShortDesc();
            $data[$i]->longDesc = $allCategory[$i]->getLongdesc();
            $data[$i]->createdOn = $allCategory[$i]->getCreatedon();
            $data[$i]->status = $allCategory[$i]->getStatus();
            $data[$i]->pseudoSubscriptionCount = $allCategory[$i]->getPseudosubscriptioncount();
        }
        
        if(isset($data) && count($data) > 0)
            return array("status" => "success", "data" =>$data);
        else
            return array("status" => "error", "message" => array("Title" => "No Data Found.", "Code" => "200"));
    }
    
    public function CreateSubscription($userId, $categoryId){
        $subscription = new Entities\Subscriptions;
        
        $subscription->setUserid($userId);
        $subscription->setCategoryid($categoryId);
        
        date_default_timezone_set("Asia/Kolkata");
        $subscription->setSubscribedon(new \DateTime("now"));
        
        try
        {
            $this->em->persist($subscription);
            $this->em->flush();
            return array("status" => "success", "data" => array("Category Subscribed Successfully."));
        }
        catch(Exception $exc)
        {
            return array("status" => "error", "message" => array("Title" => $exc->getTraceAsString()));
        }
    }
}
