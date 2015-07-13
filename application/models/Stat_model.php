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
        //$stat->totalUser = count($allUser);
        $stat->users = array();
        for($i = 0; $i < count($allUser); $i++)
        {
            //$totalShare += intval($allUser[$i]->getFbstatus());
            
            $user = new stdClass();
            $user->id = $allUser[$i]->getID();
            $user->firstName = $allUser[$i]->getFirstname();
            $user->lastName = $allUser[$i]->getLastname();
            $user->mobile = $allUser[$i]->getMobile();
            $user->os = $allUser[$i]->getOs();
            $user->city = $allUser[$i]->getCity();
            $user->subscribed = count($this->doctrine->em->getRepository('Entities\Subscriptions')->findBy(array("userid" => $allUser[$i]->getID())));
            
            $stat->users[$i] = $user;
        }
        //$stat->totalShare = $totalShare;
        
        return array("status" => "success", "data" => $stat);
    }
    
    public function ReadDealsStat(){
        $allDeals = $this->doctrine->em->getRepository('Entities\Deals')->findAll();
        
        $activDeals = 0;
        $stat = new stdClass();
        //$stat->totalDeals = count($allDeals);
        
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
            $deal->status = $allDeals[$i]->getStatus();
            
            $stat->deals[$i] = $deal;
        }
        $stat->active = $activDeals;
        return array("status" => "success", "data" => $stat);
    }
    
    public function ReadVendorStat(){
        $allVendors = $this->doctrine->em->getRepository('Entities\Vendor')->findAll();
        
        $stat = new stdClass();
        $stat->vendors = array();
        for($i = 0; $i < count($allVendors); $i++){
            $vendor = new stdClass();
            $thisVendor = $this->doctrine->em->getRepository('Entities\Vendorinfo')->find($allVendors[$i]);
            $vendor->id = $allVendors[$i]->getId();
            $vendor->firstName = $thisVendor->getFirstname();
            $vendor->lastName = $thisVendor->getLastname();
            $vendor->businessTitle = $thisVendor->getBusinesstitle();
            $vendor->registeredOn = $thisVendor->getRegisteredon();
            
            $deals = $this->doctrine->em->getRepository('Entities\Deals')->findBy(array("vendorid" => $allVendors[$i]));
            $vendor->totalDeals = $deals == NULL ? 0 : count($deals);
            
            $totalViews = 0;
            $categories = array();
            foreach($deals as $deal){
                $totalViews += $deal->getViews();
                if(!in_array($deal->getCategoryid()->getId(), $categories))
                        $categories[] = $deal->getCategoryid()->getId();
            }
            
            $vendor->totalCategories = count($categories);
            $vendor->totalViews = $totalViews;
            
            $stat->vendors[$i] = $vendor;
        }
        return array("status" => "success", "data" => $stat);
    }
    
    public function ReadDashBoardStat(){
        $allUser = $this->doctrine->em->getRepository('Entities\User')->findAll();
        $allCategory = $this->doctrine->em->getRepository('Entities\Category')->findAll();
        $allDeals = $this->doctrine->em->getRepository('Entities\Deals')->findAll();
        $allVendors = $this->doctrine->em->getRepository('Entities\Vendor')->findAll();
        
        $totalShare = 0;
        $totalAndroid = 0;
        $totalIos = 0;
        $monthlyUser = array();
        
        //calculation last 6 months
        for($i = 0, $m = date("n"), $y = date("Y"); $i <6 ; $i++){
            $monthlyUser[$y][$m--] = 0;
            if($m == 0){
                $m = 12;
                $y--;
            }
        }
        $monthlyVendor = $monthlyUser;
        
        foreach($allUser as $user){
            $totalShare += intval($user->getFbstatus());
            stristr($user->getOs(), "android") != FALSE ? ++$totalAndroid : ++$totalIos;
            
            //monthly registration calculation
            if(in_array($user->getRegisteredon()->format("Y"), array_keys($monthlyUser)) && in_array($user->getRegisteredon()->format("n"), array_keys($monthlyUser[$user->getRegisteredon()->format("Y")])))
                ++$monthlyUser[$user->getRegisteredon()->format("Y")][$user->getRegisteredon()->format("n")];
        }
        
        foreach($allVendors as $vendor){
            $vendorInfo = $this->em->getRepository('Entities\Vendorinfo')->find($vendor);
            //monthly registration calculation
            if(in_array($vendorInfo->getRegisteredon()->format("Y"), array_keys($monthlyVendor)) && in_array($vendorInfo->getRegisteredon()->format("n"), array_keys($monthlyVendor[$vendorInfo->getRegisteredon()->format("Y")])))
                ++$monthlyVendor[$vendorInfo->getRegisteredon()->format("Y")][$vendorInfo->getRegisteredon()->format("n")];
        }
        
        //var_dump($monthlyUser);exit;
        
        $data = new stdClass();
        
        $data->totalUser = $allUser == NULL ? 0 : count($allUser);
        $data->totalCategory = $allCategory == NULL ? 0 : count($allCategory);
        $data->totalDeal = $allDeals == NULL ? 0 : count($allDeals);
        $data->totalVendor = $allVendors == NULL ? 0 : count($allVendors);
        $data->totalShare = $totalShare;
        $data->totalAndroid = $totalAndroid;
        $data->totalIos = $totalIos;
        
        
        $data->monthlyUserRegistration = $monthlyUser;
        $data->monthlyVendorRegistration = $monthlyVendor;
        
        return array("status" => "success", "data" => $data);
    }
}