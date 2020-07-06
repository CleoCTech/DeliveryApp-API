<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Origin: http://localhost');
// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require_once 'baseClass.php';

$base =new API;

//we are testin this API with Postman, now the API requets user input that is why we use 'file_get_contents' php built in function and we store that data in a variable ->$user_request
$user_request = file_get_contents("php://input");
//now we decode this data held in $user_request using 'json_decode' function where we pass in the data held with this variable $user_requestand. And then store it in  $user_request_ variable
$user_request_ =json_decode($user_request, true);
//the variable endpoint will store which request/function i made to API
//since the above $user_request_ has data including the type of request made, so we extract the request type from $user_request_ array of data
$endpoint =$user_request_['request'];
//echo the extracted request made to confirm 
/// echo json_encode(['what' => $endpoint]);


//now here we use a switch case to loop through which type of request is made and do the neccessary .
//so we tell the API which request is made by the user or frontend, and the what the request made what to engage with which funtion or methid in the API 
//the switch case here behaves like a route. 
switch ($endpoint) {
    //case 1; request made is 'collect_parcel'
    //if that is the case, do the following 
    case 'collect_parcel':
        //here lets test by echoing the type of request made and any other data passed in $user_request_[]  array  
        echo l($user_request_['body']);
        $save = $base->parcel_info($user_request_['body']);
        echo l($save);
        break;

    case 'parcel_info': 
        //here lets test by echoing the type of request made and any other data passed in $user_request_[]  array  
       // echo l($user_request_['body']);
        $parcel_info = $base->get_parcel_info($user_request_['body']['id']);
        echo l($parcel_info);
        break;

    case 'collection_point': 
        //here lets test by echoing the type of request made and any other data passed in $user_request_[]  array  
       // echo l($user_request_['body']);
        $sms = $base->parcel_at_collection_point($user_request_['body']['id']);
        echo l($sms);
        break;

        
    default:
    //if the request made is not understood by the API it will throw an error message
       echo l(['request' => $endpoint, 'message' => "Request was not understood"]);
        break;
}

function l($payload)
{
   return json_encode($payload);
}