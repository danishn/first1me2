<?php

/* 
 * FirstMe Server API
 * Author : Biswajit Bardhan  * 
 */

class User_model extends CI_Model
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
        $user->setPassword(crypt($password, strlen($email)));
        $user->setFbstatus($fbStatustatus);
        
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
            return array("status" => "success", "data" => array("Facebook Status Updated Successfully."));
        }
        catch(Exception $exc)
        {
            return array("status" => "error", "message" => array("Title" => $exc->getTraceAsString(), "Code" => "503"));
        }
    }
    
    public function Login($email, $password){
        $thisUser = $this->doctrine->em->getRepository('Entities\Users')->findBy(array('email' => $email));
        
        if(is_array($thisUser) && !empty($thisUser))
        {
            if(crypt($password, strlen($email)) == $thisUser[0]->getPassword())
            {
                return array("status" => "success", "data" => array("Logged in Successfully.", "userId" => $thisUser->getId()));
            }
            else
                return array("status" => "error", "message" => array("Title" => "Email / Password mismatch.", "Code" => "401"));
        }
        else
            return array("status" => "error", "message" => array("Title" => "User not found.", "Code" => "401"));
        
    }
}