<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */

class UserModel extends CI_Model
{
    public $em;                         //doctrine entity manager

    public function __construct()
    {
        parent::__construct();
        $this->em = $this->doctrine->em;
    }
    
    public function CreateUser($GCMID, $firstName, $lastName, $email, $mobile, $country, $city, $password, $fbStatus)
    {
        $user = new Entities\User;
        
        $user->setGcmid($GCMID);
        $user->setFirstname($firstName);
        $user->setLastname($lastName);
        $user->setEmail($email);
        $user->setMobile($mobile);
        $user->setCountry($country);
        $user->setCity($city);
        $user->setPassword($password);
        $user->setFbstatus($fbstatus);
        
        try
        {
            $this->em->persist($user);
            $this->em->flush();
            
            $thisUser = $this->doctrine->em->getRepository('Entities\Users')->findBy(
                    array('email' => $email)
                );
            
            return array("status" => "success", "data" => array("User Added Successfully.", "userId" => $thisUser->getId()));
        }
        catch(Exception $exc)
        {
            return array("status" => "error", "message" => array("Title" => $exc->getTraceAsString(), "Code" => "503"));
        }
    }
    
    public function UpdateFacebookStatus($userId)
    {
        $user = new Entities\User;
        try
        {
            $this->db->update('user', array("fbStatus" => 1), array("id" => $userId));
            return array("status" => "success", "data" => array("Facebook Status Successfully."));
        }
        catch(Exception $exc)
        {
            return array("status" => "error", "message" => array("Title" => $exc->getTraceAsString(), "Code" => "503"));
        }
    }
}