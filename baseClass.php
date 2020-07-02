<?php

//we call this class so  that we can be able to be connected to the database 
use AfricasTalking\SDK\AfricasTalking;
require_once 'DBClass.php';
require_once 'vendor/autoload.php';
class API extends DB{
   
    public $at;
    public function __construct()
    {
        parent::__construct();
        
        $this->at = new AfricasTalking($username, $apiKey);
    }

    //collecting parcel details

    /**
     * ender name, parcel description, sender mobile, sender ID No, recepient name, mobile number, Town/Destination.
     *  */ 

     public function parcel_info($payload){
        try {
            $save_parcel_info = $this->con->prepare("INSERT INTO parcel_info(
                sender_name, 
                sender_id, 
                parcel_origin, 
                parcel_description, 
                sender_mobile, 
                recipient_name,
                recipient_mobile, 
                destination, 
                cost_of_delivery, 
                is_payed_for) 
                VALUES(
                    :sender_name, 
                    :sender_id, 
                    :parcel_origin, 
                    :parcel_description, 
                    :sender_mobile, 
                    :recipient_name,
                    :recipient_mobile, 
                    :destination, 
                    :cost_of_delivery, 
                    :is_payed_for
                    )"
                );
            
            $save_parcel_info->execute(
                [
                   ":sender_name" => $payload['sender_name'], 
                   ":sender_id" => $payload['sender_id'], 
                   ":parcel_origin" => $payload['parcel_origin'], 
                   ":parcel_description" => $payload['parcel_description'], 
                   ":sender_mobile" =>$payload['sender_mobile'], 
                   ":recipient_name" => $payload['recipient_name'],
                   ":recipient_mobile" => $payload['recipient_mobile'], 
                   ":destination" => $payload['destination'], 
                   ":cost_of_delivery" => $payload['cost_of_delivery'], 
                   ":is_payed_for" => $payload['is_payed_for']
               ]);
               
               if ( $this->con->lastInsertId() !== null ) {
                   //call the sms template  function and pass data to its parameters
                   $sms =$this->send_parcel_sms_template($payload['sender_name'], $payload['recipient_name'], $payload['parcel_origin'], $payload['destination'],  $this->con->lastInsertId() );

                //    $send_sms = $this->$sms->send([ 
                       
                //    ]);
                return ['code' => 1, 'message' => "Insert was successful record  id " .$this->con->lastInsertId(), 'sms' =>$sms];
               }else{
                   return ['code' => 0];
               }
              
        } catch (PDOException $e) {
            return ['code' => 0, 'message' => $e->getMessage()];
        } 
     }
     //message
     public function send_parcel_sms_template($sender, $recipient, $origin, $destination, $parcel_id){
         return "Hello {$recipient}, {$sender} has dispatched a parcel ID {$parcel_id} from {$origin} to {$destination}. We will inform you when the parcel has arrived. \nCleoCTech ";
     }
     //informing the recepient that the parcel is at the collection point
     public function parcel_at_colllection_point($payload){
         
    }
     //informing the recepient
     public function collect_parcel($payload){
         
    }
     //informing the recepient
     public function payments($payload){
         
    }
     
}