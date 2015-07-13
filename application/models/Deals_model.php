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
        $seenDeals =  $this->doctrine->em->getRepository('Entities\Seen')->findBy(array('userid' => $userId));
        if($seenDeals == NULL || !is_array($seenDeals))
            return array();
        for($i = 0; $i < count($seenDeals); $i++)
        {
            $dealIds[$i] = $seenDeals[$i]->getDealid()->getId();
        }
        
        return $dealIds;
    }
    //---------------------
    
    public function CreateDeals($name, $categoryId, $vendorId, $thumbnailImg, $bigImg, $region, $shortDesc, $longDesc, $likes, $views, $pseudoViews, $expiresOn, $status){
        $deals = new Entities\Deals;
        
        $deals->setName($name);
        $deals->setCategoryid($categoryId);
        $deals->setVendorid($vendorId);
        $deals->setCreatedon(new \DateTime("now"));
        //$deals->setThumbnailimg($thumbnailimg);
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
            
            if(isset($_FILES["thumbnailImg"]))
            {
                $target_path = "images/deals/" . $_FILES["thumbnailImg"]["name"];
                $allowedExts = array("jpg", "jpeg", "png", "bmp");
                $fileExt = explode(".", $_FILES["thumbnailImg"]["name"]);
                if(in_array($fileExt[count($fileExt)-1], $allowedExts))
                {
                    if ($_FILES["thumbnailImg"]["error"] > 0)
                    {
                        echo "Error: " . $_FILES["thumbnailImg"]["error"];
                    }
                    else
                    {
                        if(file_exists("images/deals/" . $_FILES["thumbnailImg"]["name"]))
                            unlink("images/deals/".$_FILES["thumbnailImg"]["name"]);

                        move_uploaded_file($_FILES["thumbnailImg"]["tmp_name"], "images/deals/".$deals->getId());
                        //move_uploaded_file($_FILES["thumbnailImg"]["tmp_name"], "images/deals/".$_FILES["thumbnailImg"]["name"]);
                    }
                }
                else
                    echo "Error : Unsupported file.";
            }
            else
                echo "Error : File not found.";
            
            return array("status" => "success", "data" => array("Deal Added Successfully."));
        }
        catch(Exception $exc)
        {
            return array("status" => "error", "message" => array("Title" => $exc->getTraceAsString()), "Code" => "503");
        }
    }
    
    public function ReadUserDeals($userId){
        if(($user = $this->doctrine->em->getRepository('Entities\User')->find($userId)) == NULL)
            return array("status" => "error", "message" => array("Title" => "Invalid User ID.", "Code" => "503"));
        
        $mySubscriptions = $this->doctrine->em->getRepository('Entities\Subscriptions')->findBy(
                array('userid' => $userId)
                );
        if($mySubscriptions == NULL)
            return array("status" => "error", "message" => array("Title" => "Please subscribe atleast one Category first.", "Code" => "503"));
        
        //var_dump(count($this->doctrine->em->getRepository('Entities\Deals')->findBy(array('categoryid' => 1))));exit;
        
        for($i = 0; $i < count($mySubscriptions); $i++)
            $myDeals[$i] = $this->doctrine->em->getRepository('Entities\Deals')->findBy(array('categoryid' => $mySubscriptions[$i]->getCategoryid()->getId()));
        
        if(is_array($myDeals) && empty($myDeals))
            return array("status" => "error", "message" => array("Title" => "No Deals Found.", "Code" => "404"));
        
        $j = 0;
        for($i = 0; $i < count($myDeals); $i++){
            if(!empty($myDeals[$i]))
            {
                foreach($myDeals[$i] as $deal)
                {
                    $data[$j] = new stdClass();

                    $data[$j]->id = $deal->getId();
                    $data[$j]->name = $deal->getName();
                    $data[$j]->categoryId = $deal->getCategoryid()->getId();
                    $data[$j]->vendorId = $deal->getVendorid()->getId();
                    $data[$j]->createdOn = $deal->getCreatedon();
                    $data[$j]->thumbnailImg = $deal->getThumbnailimg();
                    $data[$j]->bigImg = $deal->getBigimg();
                    $data[$j]->region = $deal->getRegion();
                    $data[$j]->shortDesc = $deal->getShortdesc();
                    $data[$j]->longDesc = $deal->getLongdesc();
                    $data[$j]->likes = $deal->getLikes();
                    $data[$j]->views = $deal->getViews();
                    $data[$j]->pseudoViews = $deal->getPseudoviews();
                    $data[$j]->expiresOn = $deal->getExpireson();
                    $data[$j]->status = $deal->getStatus();

                    $data[$j]->seen = in_array($deal->getId(), self::getSeenDealIds($userId));
                    $j++;
                }
            }
        }
        
        if(isset($data) && count($data) > 0)
            return array("status" => "success", "data" =>$data);
        else
            return array("status" => "error", "message" => array("Title" => "No Data Found.", "Code" => "200"));
    }
    
    public function UpdateSeen($userId, $dealId){
        if(($user = $this->doctrine->em->getRepository('Entities\User')->find($userId)) == NULL)
            return array("status" => "error", "message" => array("Title" => "Invalid User ID.", "Code" => "503"));
        
        if(($deal = $this->doctrine->em->getRepository('Entities\Deals')->find($dealId)) == NULL)
            return array("status" => "error", "message" => array("Title" => "Invalid Deal ID.", "Code" => "503"));
        
        //implement - mark as seen + view count for each deal
        
        //incrementing view count
        //$theDeal = $this->doctrine->em->getRepository('Entities\Deals')->find($dealId);
        $currentView = $deal->getViews();
        try
        {
            $this->db->update('deals', array("views" => ++$currentView), array("id" => $dealId));
        }
        catch(Exception $exc)
        {
            return array("status" => "error", "message" => array("Title" => "increment ".$exc->getTraceAsString(), "Code" => "503"));
        }
        
        //implementing mark as seen
        if($this->doctrine->em->getRepository('Entities\Seen')->findOneBy(array("userid" => $userId, "dealid" => $dealId)) == NULL)
        {
            $seen = new Entities\Seen;
            try
            {
                $seen->setUserid($user);
                $seen->setDealid($deal);
                $seen->setFavourite(0);
                $seen->setRating(0);
                //$seen->setDealid($this->doctrine->em->getRepository('Entities\Deals')->find($category));

                $this->em->persist($seen);
                $this->em->flush();
            }
            catch(Exception $exc)
            {
                return array("status" => "error", "message" => array("Title" => "Error Occured While Marking as Seen Deal.\n" . $exc->getTraceAsString(), "Code" => "401"));
                //return array("status" => "success", "data" => array("Deal Marked as Seen."));
            }
        }
        
        return array("status" => "success", "data" => array("Deal Seen Updated."));
    }
    
    public function UpdateFavourite($userId, $dealId, $favourite){
        if(($user = $this->doctrine->em->getRepository('Entities\User')->find($userId)) == NULL)
            return array("status" => "error", "message" => array("Title" => "Invalid User ID.", "Code" => "503"));
        
        if(($deal = $this->doctrine->em->getRepository('Entities\Deals')->find($dealId)) == NULL)
            return array("status" => "error", "message" => array("Title" => "Invalid Deal ID.", "Code" => "503"));
        
        if(($seen = $this->doctrine->em->getRepository('Entities\Seen')->findOneBy(array("userid" => $userId, "dealid" => $dealId))) == NULL)
            return array("status" => "error", "message" => array("Title" => "Deal not seen yet.", "Code" => "503"));
        
        try
        {
            $this->db->update('seen', array("favourite" => $favourite), array("userid" => $userId, "dealid" => $dealId));
        }
        catch(Exception $exc)
        {
            return array("status" => "error", "message" => array("Title" => "Error while adding to favourite.", "Code" => "503"));
        }
        
        return array("status" => "success", "data" => array("Deal Favourite Status Updated."));
    }
}