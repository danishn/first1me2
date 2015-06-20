<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */

class Deals_model extends CI_Model
{
    public $em;                         //doctrine entity manager

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }
    
    //-----Helper functions
    private function isSeen($dealId)
    {
        return ($this->doctrine->em->getRepository('Entities\Seen')->findBy(array('dealId' => $dealId)) == null) ? FALSE : TRUE;
    }
    
    private function getSeenDealIds($userId){
        $seenDeals =  $this->doctrine->em->getRepository('Entities\Seen')->findBy(array('userId' => $userId));
        if(!is_array($seenDeals))
            return null;
        for($i = 0; $i < count($seenDeals); $i++)
        {
            $dealIds[$i] = $seenDeals[$i]->getDealid();
        }
        
        return $dealIds;
    }
    //---------------------
    
    public function CreateDeals($categoryId, $vendorId, $thumbnailImg, $bigImg, $region, $shortDesc, $longDesc, $likes, $views, $pseudoViews, $expiresOn, $status){
        $deals = new Entities\Deals;
        
        $deals->setCategoryid($categoryId);
        $deals->setVendorid($vendorId);
        $deals->setCreatedon(new \DateTime("now"));
        $deals->setThumbnailimg($thumbnailimg);
        $deals->setBigimg($bigimg);
        $deals->setRegion($region);
        $deals->setShortdesc($shortdesc);
        $deals->setLongdesc($longdesc);
        $deals->setLikes($likes);
        $deals->setViews($views);
        $deals->setPseudoviews($pseudoviews);
        $deals->setExpireson($expireson);
        $deals->setStatus($status);
        
        try
        {
            $this->em->persist($deals);
            $this->em->flush();
            return array("status" => "success", "data" => array("Deal Added Successfully."));
        }
        catch(Exception $exc)
        {
            return array("status" => "error", "message" => array("Title" => $exc->getTraceAsString()), "Code" => "503");
        }
    }
    
    public function ReadUserDeals($userId){   
        $mySubscriptions = $this->doctrine->em->getRepository('Entities\Subscriptions')->findBy(
                array('userId' => $userId)
                );
        
        $i = 0;
        foreach($mySubscriptions as $subscription)
        {
            $myDeals[$i++] = $this->doctrine->em->getRepository('Entities\Deals')->findBy(
                array('categoryId' => $subscription->getCategoryid())
                );
        }
        
        for($i = 0; $i < count($myDeals); $i++)
        {
            $data[$i] = new stdClass();
            
            $data[$i]->id = $myDeals[$i]->getId();
            $data[$i]->categoryId = $myDeals[$i]->getCategoryid();
            $data[$i]->vendorId = $myDeals[$i]->getVendorid();
            $data[$i]->createdOn = $myDeals[$i]->getCreatedon();
            $data[$i]->thumbnailImg = $myDeals[$i]->getThumbnailimg();
            $data[$i]->bigImg = $myDeals[$i]->getBigimg();
            $data[$i]->region = $myDeals[$i]->getRegion();
            $data[$i]->shortDesc = $myDeals[$i]->getShortdesc();
            $data[$i]->longDesc = $myDeals[$i]->getLongdesc();
            $data[$i]->likes = $myDeals[$i]->getLikes();
            $data[$i]->views = $myDeals[$i]->getViews();
            $data[$i]->pseudoViews = $myDeals[$i]->getPseudoviews();
            $data[$i]->expiresOn = $myDeals[$i]->getExpireson();
            $data[$i]->status = $myDeals[$i]->getStatus();
            
            $data[$i]->seen = in_array($myDeals[$i]->getId(), getSeenDealIds($userId));
        }
        
        if(isset($data) && count($data) > 0)
            return array("status" => "success", "data" =>$data);
        else
            return array("status" => "error", "message" => array("Title" => "No Data Found.", "Code" => "200"));
    }
}