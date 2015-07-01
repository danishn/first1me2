<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */

class Stat_model extends CI_Model
{
    public $em;                         //doctrine entity manager

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }
    
    public function ReadCategoryStat(){
        $allCategory = $this->doctrine->em->getRepository('Entities\Category')->findAll();
        $stat = array();
        
        if($allCategory == NULL)
            return array("status" => "error", "message" => array("Title" => "No Category Found.", "Code" => "503"));

        for($i = 0, $totalViews = 0; $i < count($allCategory); $totalViews = 0,$i++){
            $stat[$i] = new stdClass();
            $stat[$i]->id = $allCategory[$i]->getId();
            $stat[$i]->displayName = $allCategory[$i]->getDisplayname();
            $stat[$i]->createdOn = $allCategory[$i]->getCreatedon();

            $subscriptions = $this->doctrine->em->getRepository('Entities\Subscriptions')->findBy(array("categoryid" => $allCategory[$i]));
            $stat[$i]->subscribed = $subscriptions == NULL ? 0 : count($subscriptions);

            $allDeals = $this->doctrine->em->getRepository('Entities\Deals')->findBy(array("categoryid" => $allCategory[$i]->getId()));
            $stat[$i]->deals = count($allDeals);

            foreach($allDeals as $deal)
                $totalViews += $deal->getViews();
            $stat[$i]->totalViews = $totalViews;

            $stat[$i]->status = $allCategory[$i]->getStatus();
        }

        return array("status" => "success", "data" => $stat);
    }
    
    public function ReadUserStat(){
        $allUser = $this->doctrine->em->getRepository('Entities\User')->findAll();
        
        if($allUser == NULL)
            return array("status" => "error", "message" => array("Title" => "No registered user found.", "Code" => "503"));
        
        $stat = new stdClass();
        $stat->totalUser = count($allUser);
        $stat->users = array();
        for($i = 0, $totalShare = 0, $user = new stdClass(); $i < count($allUser); $totalShare = 0, $i++)
        {
            $totalShare += intval($allUser[$i]->getFbstatus());
            
            $user->id = $allUser[$i]->getID();
            $user->firstName = $allUser[$i]->getFirstname();
            $user->lastName = $allUser[$i]->getLastname();
            $user->mobile = $allUser[$i]->getMobile();
            $user->os = $allUser[$i]->getOs();
            $user->city = $allUser[$i]->getCity();
            $user->subscribed = count($this->doctrine->em->getRepository('Entities\Subscriptions')->findBy(array("userid" => $allUser[$i]->getID())));
            
            $stat->users[$i] = $user;
        }
        $stat->totalShare = $totalShare;
        
        return array("status" => "success", "data" => $stat);
    }
    
    public function ReadDealsStat(){
        $allDeals = $this->doctrine->em->getRepository('Entities\Deals')->findAll();
        
        $activDeals = 0;
        $stat = new stdClass();
        $stat->totalDeals = count($allDeals);
        
        $stat->deals = array();
        for($i = 0; $i < count($allDeals); $i++){
            $deal = new stdClass();
            
            $deal->id = $allDeals[$i]->getId();
            $deal->thumbnailImg = $allDeals[$i]->getThumbnailimg();
            $deal->shortDesc = $allDeals[$i]->getShortdesc();
            $deal->region = $allDeals[$i]->getRegion();
            $deal->views = $allDeals[$i]->getViews();
            
            $interval = strtotime($allDeals[$i]->getExpireson()->format('Y-m-d H:i:s')) - strtotime(date("Y-m-d H:i:s"));
            //echo "\nExpires " . $allDeals[$i]->getExpireson() . "\n" . $interval . "\n";
            if($allDeals[$i]->getStatus() == 1 && $interval > 0)
                ++$activDeals;
            
            $deal->expiresOn = $allDeals[$i]->getExpireson();
            
            $stat->deals[$i] = $deal;
        }
        $stat->active = $activDeals;
        return array("status" => "success", "data" => $stat);
    }
}