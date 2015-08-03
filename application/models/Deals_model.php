<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */
use \Library\ImageResize;       // Use Namespace for Image resize
class Deals_model extends CI_Model
{
    public $em;                         //doctrine entity manager

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
        $this->load->file('application/classes/ImageResize.php');     // Load file for Image resize
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
    
    public function gcm(){   
        /*
        // Select brodcast receivers depending upon type of broadcast notification
            $data = array(
                'type' => 'event',
                'event_id' => 72
            );
            $this->load->model('Notification_model', 'notification');
            $reg_ids = $this->notification->get_tokens($data);
            var_dump($reg_ids);exit;
        */
        // -----------------------------------------------------------------------------------------
        
        
        //  Fetch from db
        $this->em = $this->doctrine->em;        // Doctrine initialization
        $gcm_users = $this->em->getRepository('Entities\NotificationIds')->findAll();
        
        //var_dump($gcm_users);exit;
        
        $registration_ids = [];
        
        if($gcm_users)
        {
             foreach($gcm_users as $gcm_user){
                 $registration_ids[] = $gcm_user->getToken();    
             }
        }
        
        //$registration_ids[] = $this->input->post('registration_id');
        
        if($registration_ids[0] == null){
            echo 'error No Registration Ids found';
            exit;
        }
        
        $message = array(
            "title" => "New Deals Waiting",
            "body" => 'Recieved New Notification..'
         );
        
        $data = array(
            "type" => 'event',
            "msg" => 'New Notification',
            "description" => "Type can be anything from event/meeting/news/favorites/tables indicating whats this notification is for. Depending on type, app should reload/refresh respective view.",
         );
        
        
        // GCM Call
        $this->load->file('application/classes/GCM.php');
        $gcm = new GCM();
        $gcm_res = $gcm->send_notification($registration_ids, $message, $data);
        
        // APN Call
        $this->load->file('application/classes/APN.php');
        $apn = new APN();
        $apn_res = $apn->send_notification($registration_ids, $message, $data);
        
        $response =new Response();
        $response->setSuccess('true');
        $response->setdata(array('gcm_response'=>$gcm_res, 'apn_response'=>$apn_res));
        $response->setError(null);
                
        $response->respond();
        exit;
        
    }
    //---------------------
    
    public function CreateDeals($name, $categoryId, $vendorId, $country, $state, $city, $shortDesc, $longDesc, $likes, $views, $pseudoViews, $expiresOn, $status){
        
        $category = $this->em->getRepository('Entities\Category')->find($categoryId);
        $vendor = $this->em->getRepository('Entities\Vendor')->find($vendorId);
        if($category == NULL)
            return array("status" => "error", "message" => array("Title" => "Category not found.", "Code" => "404"));
        if($vendor == NULL)
            return array("status" => "error", "message" => array("Title" => "Vendor not found.", "Code" => "404"));
        
        //var_dump(new \DateTime((string)$expiresOn));exit;
        $deals = new Entities\Deals;
        
        $deals->setName($name);
        $deals->setCategoryid($category);
        $deals->setVendorid($vendor);
        $deals->setCreatedon(new \DateTime("now"));
        $deals->setThumbnailimg("/public/images/deal/thumb/default.png");
        $deals->setBigimg("/public/images/deal/big/default.png");
        
        $deals->setShortdesc($shortDesc);
        $deals->setLongdesc($longDesc);
        $deals->setLikes($likes);
        $deals->setViews($views);
        $deals->setPseudoviews($pseudoViews);
        $deals->setExpireson(new \DateTime((string)$expiresOn));
        $deals->setStatus($status);
        
        $deal_region = new Entities\DealRegion;
        //var_dump($deals);exit;
        try{
            $this->em->persist($deals);
            $this->em->flush();
             if(isset($_FILES['dealImg'])){   
                $pic = explode('.', $_FILES['dealImg']['name']);
                
                // upload event photo to server & set image URLs
                $config['upload_path'] = 'public/images/deal/big';
                $config['allowed_types'] = 'gif|jpeg|jpg|png';
                $config['max_size']	= '10000';
                $config['overwrite'] = true;
                $config['file_name'] = $deals->getId(). "." .$pic[count($pic)-1];
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                
                if(!$this->upload->do_upload('dealImg')){
                    //return 'error '.$this->upload->display_errors();
                    return array("status" => "error", "message" => array("Title" => "Deal Added, but failed to upload Image.", "Code" => "404"));
                }
                else{
                    $data = $this->upload->data();
                    $thumb_url  = $big_url = '/public/images/deal/big/' . $data['file_name'];
                    
                    $image = new ImageResize('public/images/deal/big/'.$data['file_name']);
                    //var_dump($image);exit;
                    $image->scale(20);
                    $image->save('public/images/deal/thumb/'.$data['file_name']);
                    $thumb_url = '/public/images/deal/thumb/' . $data['file_name'];
                    
                    $deals->setThumbnailimg($thumb_url);
                    $deals->setBigimg($big_url);
                    $this->em->flush();
                    $gcmToken = array();
                    $apnToken = array();
                    $gcm_res = "GCM Failure";
                    $apn_res = "APN Failure";
                    //Send Push Notification
                    $subscribed = $this->em->getRepository('Entities\Subscriptions')->findBy(array('categoryid' => $deals->getCategoryid()->getId()));
                    //var_dump($subscribed);exit;
                    if(is_array($subscribed) && !empty($subscribed)){
                        foreach($subscribed as $subscription){
                            stristr($subscription->getUserid()->getOs(), 'android') ? $gcmToken[] = $subscription->getUserid()->getToken() : $apnToken[] = $subscription->getUserid()->getToken();
                        }
                    }
                    
                    $message = array(
                        "title" => "New Deals Added",
                        "body" => 'Recieved New Notification..'
                     );

                    /*$data = array(
                        "type" => 'event',
                        "msg" => 'New Notification',
                        "description" => "Type can be anything from event/meeting/news/favorites/tables indicating whats this notification is for. Depending on type, app should reload/refresh respective view.",
                     );*/
                    
                    $data = array(
                        "title" => $deals->getName(),
                        "body" => $deals->getShortdesc(),
                        "deal" => $deals->getId()
                     );
                    
                    // GCM Call
                    if(is_array($gcmToken) && !empty($gcmToken)){
                        $this->load->file('application/classes/GCM.php');
                        $gcm = new GCM();
                        $gcm_res = $gcm->send_notification($gcmToken, $message, $data);
                    }

                    // APN Call
                    if(is_array($apnToken) && !empty($apnToken)){
                        $this->load->file('application/classes/APN.php');
                        $apn = new APN();
                        $apn_res = $apn->send_notification($apnToken, $message, $data);
                    }
                }
            }
            //print_r($gcmToken);
            //return array("status" => "success", "data" => array("Deal Added Successfully."));//'\nPush Notification sent to " . count($gcmToken) . " Android users and " . count($apnToken) . " iOs users.\nGCM Result: " . $gcm_res . "\nAPN Result: " . $apn_res_res));
            return array("status" => "success", "data" => array("Deal Added Successfully.\nPush Notification sent to " . count($gcmToken) . " Android users and " . count($apnToken) . " iOs users.\nGCM Result: " . $gcm_res . "\nAPN Result: " . $apn_res));
        }
        catch(Exception $exc)
        {
            //return array("status" => "error", "message" => array("Title" => "Exception Occured", "Code" => "503"));
            return array("status" => "error", "message" => array("Title" => $exc->getTraceAsString()), "Code" => "503");
        }
    }
    
    public function UpdateDeal($updateFields, $dealId){
        $deal = new Entities\Deals;
        try
        {
            $this->db->update('deals', $updateFields, array("id" => $dealId));
            return array("status" => "success", "data" => array("Deal Updated Successfully."));
        }
        catch(Exception $exc)
        {
            return array("status" => "error", "message" => array("Title" => $exc->getTraceAsString(), "Code" => "503"));
        }
    }
    
    public function ReadUserDeals($userId){
        if(($user = $this->doctrine->em->getRepository('Entities\User')->find($userId)) == NULL)
            return array("status" => "error", "message" => array("Title" => "Invalid User ID.", "Code" => "503"));
        $con = $this->em->getConnection();
        $query = $con->prepare("SELECT deals.id, deals.name, deals.categoryId, deals.vendorId, deals.createdOn, deals.thumbnailImg, deals.bigImg, deals.region, deals.shortDesc, deals.longDesc, deals.likes, deals.views, deals.pseudoViews, deals.expiresOn, deals.status from deals INNER JOIN category ON deals.categoryId = category.id WHERE category.id IN (SELECT categoryId FROM subscriptions WHERE userId = $userId) And deals.status = 1 And deals.expiresOn >= CURDATE()");
        $query->execute();
        $data = $query->fetchAll();
        //var_dump($data);exit;
        //-----------------------------------------
        
        /*$mySubscriptions = $this->doctrine->em->getRepository('Entities\Subscriptions')->findBy(
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
                    $data[$j]->dealImg = $deal->getThumbnailimg();
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
        }*/
        
        if(isset($data) && count($data) > 0)
            return array("status" => "success", "data" =>$data);
        else
            return array("status" => "error", "message" => array("Title" => "No Data Found.", "Code" => "200"));
    }
    
    public function ReadSingleDeals($dealId){
        $deal = $this->em->getRepository('Entities\Deals')->find($dealId);
        
        if($deal != NULL){
            $data = new stdClass();
            $data->id = $deal->getId();
            $data->name = $deal->getName();
            $data->shortDesc = $deal->getShortdesc();
            $data->longDesc = $deal->getLongdesc();
            $data->pseudoViews = $deal->getPseudoviews();
            $data->region = $deal->getRegion();
            $data->expiresOn = $deal->getExpireson();
            $data->status = $deal->getStatus();
            
            return array("status" => "success", "data" =>$data);
        }
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