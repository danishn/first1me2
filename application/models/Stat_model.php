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
        
        for($i = 0; $i < count($allUser); $i++){
            $stat[$i] = new stdClass();
            $stat[$i]->totalUser = count($allUser);
            //$stat[$i]->totalFavourite = ;
        }
    }
}