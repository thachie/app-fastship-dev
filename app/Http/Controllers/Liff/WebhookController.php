<?php

namespace App\Http\Controllers\Liff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\Fastship\FS_Customer;
use App\Lib\Fastship\FS_Shipment;
use App\Lib\Fastship\Fastship;
use App\Lib\Line\LineManager;
use App\Lib\Fastship\FS_Line;
use App\Events\LineEvent;

class WebhookController extends Controller
{
    
    public function webhook(Request $request)
    {
        
        file_put_contents(storage_path('app/public/liff_log.txt'), file_get_contents('php://input') . PHP_EOL, FILE_APPEND);
        
        //file_put_contents(storage_path('app/public/liff_log.txt'), print_r($request->headers->all(),true), FILE_APPEND);


        $datas = file_get_contents('php://input');
        $deCode = json_decode($datas,true);
        
        //check content-type
        if(isset($deCode['events'][0]['replyToken'])){
            $replyToken = $deCode['events'][0]['replyToken'];
        }else{
            exit(); //unfollow
        }
        $userId = $deCode['events'][0]['source']['userId'];
        if(isset($deCode['events'][0]['postback'])){
            $messageType = "postback";
            $messageId = $deCode['events'][0]['timestamp'];
        }else if(isset($deCode['events'][0]['message'])){
            $messageType = $deCode['events'][0]['message']['type'];
            $messageId = $deCode['events'][0]['message']['id'];
        }else{
            $messageType = $deCode['events'][0]['type'];
            $messageId = "";
        }

        Fastship::getToken();
        
        if($messageType == "image"){
            
            $content = LineManager::getContent($messageId,"image");
            
            $imageUrl = "<img src='" . $content . "' style='width:200px;'/>";
            
            //file_put_contents(storage_path('app/public/liff_log.txt'), "Content " . $messageId . ": " . print_r($imageUrl,true), FILE_APPEND);

            $params = LineManager::mappingResponse($deCode);
            $params['Text'] = $content;

            //file_put_contents(storage_path('app/public/liff_log.txt'), "Params : " . print_r($params,true), FILE_APPEND);
            
            if(empty($params)) exit();

        }else if($messageType == "location"){

            $params = LineManager::mappingResponse($deCode);
                
            //file_put_contents(storage_path('app/public/liff_log.txt'), "Params : " . print_r($params,true), FILE_APPEND);
                
            if(empty($params)) exit();
            
        }else if($messageType == "video"){
            
            $content = LineManager::getContent($messageId,"video");
            
            $videoUrl = "<video width='320' height='240' controls>";
            $videoUrl.= "<source src='" . $content . "' type='video/mp4'>";
            $videoUrl.= "<a href='" . $content . "'>video</a>";
            $videoUrl.= "</audio>";
            
            //file_put_contents(storage_path('app/public/liff_log.txt'), "Content " . $messageId . ": " . print_r($imageUrl,true), FILE_APPEND);
            
            $params = LineManager::mappingResponse($deCode);
            $params['Text'] = $videoUrl;
            
            //file_put_contents(storage_path('app/public/liff_log.txt'), "Params : " . print_r($params,true), FILE_APPEND);
            
            if(empty($params)) exit();
            
        }else if($messageType == "audio"){
            
            $content = LineManager::getContent($messageId,"audio");

            $audioUrl = "<audio controls>";
            $audioUrl.= "<source src='" . $content . "' type='audio/mpeg'>";
            $audioUrl.= "<a href='" . $content . "'>audio</a>";
            $audioUrl.= "</audio>";

            $params = LineManager::mappingResponse($deCode);
            $params['Text'] = $audioUrl;
            
           // file_put_contents(storage_path('app/public/liff_log.txt'), "Content " . $messageId . ": " . print_r($audioUrl,true), FILE_APPEND);
            
            if(empty($params)) exit();

        }else if($messageType == "postback"){

            $params = LineManager::mappingResponse($deCode);
    
            //file_put_contents(storage_path('app/public/liff_log.txt'), "Params : " . print_r($params,true), FILE_APPEND);
            
            if(empty($params)) exit();

        }else{

            $params = LineManager::mappingResponse($deCode);
           
            //file_put_contents(storage_path('app/public/liff_log.txt'), "Params:" . print_r($params,true), FILE_APPEND);
            
            if(empty($params)) exit();

    
            /*
            //get command
            if(isset($deCode['queryResult']['parameters']['command'])){
                $command = $deCode['queryResult']['parameters']['command'];
            }else{
                $command = "";
            }
 
            switch ($command){
                case 'shipment-detail': $messages = $this::doShipmentDetail($deCode); break;
                case 'create-pickup': $messages = $this::doCreatePickup($deCode); break;
                default: 
                    //$messages = array();
                    //$messages['messages'][0] = LineManager::getFormatTextMessage("");
                    exit();
                break;
            }
    
            $encodeJson = json_encode($messages);
            
            file_put_contents(storage_path('app/public/liff_log.txt'), print_r($encodeJson,true), FILE_APPEND);
    
            $results = LineManager::replyMessage($encodeJson);
            
            file_put_contents(storage_path('app/public/liff_log.txt'), print_r($results,true) , FILE_APPEND);
            */

        }
        
        //get profile
        $profile = LineManager::getProfile($params['UserId']);
        if(isset($profile->pictureUrl)){
            $params['ProfileImage'] = $profile->pictureUrl;
            $params['Linename'] = $profile->displayName;
        }else{
            $params['ProfileImage'] = "https://www.w3schools.com/howto/img_avatar.png";
            $params['Linename'] = "Unknown";
        }
        
        $lineId = FS_Line::create($params);

        //get line for trigger event
        $line = FS_Line::get($lineId);
        
        file_put_contents(storage_path('app/public/liff_log.txt'), print_r($line,true) . PHP_EOL, FILE_APPEND);
        
        // Notify client-side listeners with the
        // Laravel `event` helper function.
        $params = array(
            "id" => $line['ID'],
            "type" => $line['Type'],
            "userId" => $line['UserId'],
            "adminId" => $line['AdminId'],
            "params" => $line['Params'],
            "message" => $line['Text'],
            "createDate" => $line['CreateDate'],
            "profileImage" => $line['ProfileImage'],
            "linename" => $line['Linename'],
        );
        event(new LineEvent($params));

        http_response_code(200);
        
    }
    
    function doShipmentDetail($deCode)
    {
        
        $replyToken = $deCode['originalDetectIntentRequest']['payload']['data']['replyToken'];
        $lineUserId = $deCode['originalDetectIntentRequest']['payload']['data']['source']['userId'];
        $shipmentId = $deCode['queryResult']['parameters']['shipmentId'];
        //$lineUserId = "U06ef2762df7dbe7aa4a38c6f947350f9";
        
        $json = $this->getShipmentDetailJson($shipmentId);

        if($json != ""){
            
            $messages = [];
            $messages['replyToken'] = $replyToken;
            $altText = "รายละเอียดพัสดุ ".$shipmentId;
            $messages['messages'][0] = LineManager::getFormatFlexMessage($json,$altText);
            
        }else{
            $messages = [];
            $messages['replyToken'] = $replyToken;
            $messages['messages'][0] = LineManager::getFormatTextMessage("ไม่พบพัสดุที่ต้องการ");
        }

        return $messages;
    }
    
    function doCreatePickup($deCode)
    {
        
        $replyToken = $deCode['originalDetectIntentRequest']['payload']['data']['replyToken'];
        $lineUserId = $deCode['originalDetectIntentRequest']['payload']['data']['source']['userId'];
        //$lineUserId = "U06ef2762df7dbe7aa4a38c6f947350f9";

        $json = $this::getCreatePickupJson();
            
        $messages = [];
        $messages['replyToken'] = $replyToken;
        $altText = "create pickup";
        $messages['messages'][0] = LineManager::getFormatFlexMessage($json,$altText);

        return $messages;
    }
    
    function doDefaultReply($deCode)
    {
        $replyToken = $deCode['originalDetectIntentRequest']['payload']['data']['replyToken'];
        
        $messages = [];
        $messages['replyToken'] = $replyToken;
        $messages['messages'][0] = LineManager::getFormatTextMessage("...");
        
        return $messages;
    }

    private function getShipmentDetailJson($shipmentId)
    {
        
        Fastship::getToken();
        $customerId = FS_Customer::checkLineUserId($lineUserId);
        
        Fastship::getToken($customerId);
        $shipObj = FS_Shipment::get($shipmentId);

        $json = "";
        if($shipObj){
            
            $createDate = date("d/m/Y",strtotime($shipObj["CreateDate"]['date']));
            $agent = $shipObj["ShipmentDetail"]["ShippingAgent"];
            $agentName = $shipObj["ShipmentDetail"]["ShippingAgent"];
            $agentDuration = "3-5 days";
            $agentType = $shipObj["ShipmentDetail"]["ShippingType"];
            $weight = $shipObj["ShipmentDetail"]["Weight"];
            if( $shipObj["ShipmentDetail"]["Width"] != "" && $shipObj["ShipmentDetail"]["Height"] != "" && $shipObj["ShipmentDetail"]["Width"] != ""){
                $dimension = $shipObj["ShipmentDetail"]["Width"] ."x". $shipObj["ShipmentDetail"]["Height"] ."x". $shipObj["ShipmentDetail"]["Width"] . " cm.";
            }else{
                $dimension = "";
            }
            $rate = $shipObj["ShipmentDetail"]["ShippingRate"];
            
            if($shipObj["ReceiverDetail"]["Company"] != ""){
                $receiverName = $shipObj["ReceiverDetail"]["Company"];
                $receiverContact = $shipObj["ReceiverDetail"]["Custname"];
            }else{
                $receiverName = $shipObj["ReceiverDetail"]["Custname"];
                $receiverContact = "";
            }
            $receiverAddress1 = $shipObj["ReceiverDetail"]["AddressLine1"] . " ";
            $receiverAddress2 = $shipObj["ReceiverDetail"]["AddressLine2"] . " ";
            $receiverAddress3 = $shipObj["ReceiverDetail"]["City"] . " ";
            $receiverAddress3.= $shipObj["ReceiverDetail"]["State"] . " ";
            $receiverAddress3.= $shipObj["ReceiverDetail"]["Postcode"] . " ";
            $receiverCountry = $shipObj["ReceiverDetail"]["Country"] . " ";
            $receiverEmail = $shipObj["ReceiverDetail"]["Email"] . " ";
            $receiverTelephone = $shipObj["ReceiverDetail"]["PhoneNumber"] . " ";
            
            $declareTypes = explode(";",$shipObj["ShipmentDetail"]["DeclareType"]);
            $declareQtys = explode(";",$shipObj["ShipmentDetail"]["DeclareQty"]);
            $declareValues = explode(";",$shipObj["ShipmentDetail"]["DeclareValue"]);
            $declares = array();
            $productCount = 0;
            foreach($declareTypes as $key=>$declare){
                if($declareTypes[$key] == "") continue;
                $declares[] = array(
                    "type" => $declareTypes[$key],
                    "qty" => $declareQtys[$key],
                    "value" => $declareValues[$key],
                );
                $productCount++;
            }
            
            //"size": "giga",
            //"backgroundColor": "#f15a22ff",
            $json = '
            {
                "type": "bubble",
                "hero": {
                  "type": "image",
                  "url": "https://app.fastship.co/images/line/line_header.png?t=' . time() . '",
                  "margin": "none",
                  "size": "full",
                  "aspectRatio": "3:1",
                  "aspectMode": "cover"
                },
                "body": {
                  "type": "box",
                  "layout": "horizontal",
                  "spacing": "md",
                  "contents": [
                    {
                      "type": "box",
                      "layout": "vertical",
                      "flex": 10,
                      "contents": [
                        {
                          "type": "box",
                          "layout": "vertical",
                          "contents": [
                            {
                              "type": "text",
                              "text": "SHIPMENT#' . $shipmentId . '",
                              "margin": "none",
                              "size": "lg",
                              "weight": "bold",
                              "color": "#F15A22"
                            },
                            {
                              "type": "text",
                              "text": "created on ' . $createDate . '",
                              "margin": "none",
                              "size": "xs",
                              "align": "start",
                              "color": "#999999"
                            }
                          ]
                        },
                        {
                          "type": "separator",
                          "margin": "lg",
                          "color": "#DDDDDD"
                        },
                        {
                          "type": "separator",
                          "margin": "lg",
                          "color": "#FFFFFF"
                        },
                        {
                          "type": "box",
                          "layout": "horizontal",
                          "contents": [
                            {
                              "type": "image",
                              "url": "https://app.fastship.co/images/agent/' . $agent . '.gif",
                              "flex": 4,
                              "align": "start",
                              "gravity": "top",
                              "aspectRatio": "16:9",
                              "aspectMode": "cover"
                            },
                            {
                              "type": "box",
                              "layout": "vertical",
                              "flex": 6,
                              "spacing": "md",
                              "margin": "md",
                              "contents": [
                                {
                                  "type": "text",
                                  "text": "' . $agentName . '",
                                  "size": "md",
                                  "weight": "bold",
                                  "color": "#3A80BE"
                                },
                                {
                                  "type": "text",
                                  "text": "' . $agentDuration . '",
                                  "margin": "none",
                                  "size": "xs"
                                },
                                {
                                  "type": "text",
                                  "text": "' . $agentType . '",
                                  "margin": "none",
                                  "size": "xs",
                                  "weight": "regular",
                                  "color": "#555555"
                                }
                              ]
                            }
                          ]
                        },
                        {
                          "type": "separator",
                          "color": "#FFFFFF"
                        },
                        {
                          "type": "box",
                          "layout": "horizontal",
                          "margin": "md",
                          "contents": [
                            {
                              "type": "box",
                              "layout": "vertical",
                              "contents": [
                                {
                                  "type": "text",
                                  "text": "' . $weight . ' gram",
                                  "weight": "bold"
                                }';
            if($dimension != ""){
                $json .= ',
                                {
                                  "type": "text",
                                  "text": "' . $dimension . '",
                                  "margin": "none",
                                  "size": "xs"
                                }';
            }
            $json .= ']
                            },
                            {
                              "type": "text",
                              "text": "' . $rate . ' THB",
                              "size": "xl",
                              "align": "end",
                              "weight": "bold",
                              "color": "#24B718"
                            }
                          ]
                        },
                        {
                          "type": "separator",
                          "margin": "lg",
                          "color": "#FFFFFF"
                        },
                        {
                          "type": "text",
                          "text": "' . $receiverName . '",
                          "weight": "bold",
                          "color": "#F15A22"
                        }';
            if($receiverContact != ""){
                $json .= ',
                        {
                          "type": "text",
                          "text": "' . $receiverContact . '",
                          "size": "sm",
                          "weight": "bold"
                        }';
            }
            $json .= ',{
                          "type": "text",
                          "text": "' . $receiverAddress1 . ' ' . $receiverAddress2 . ' ' . $receiverAddress3 . '",
                          "size": "sm",
                          "wrap": true
                        },
                        {
                          "type": "text",
                          "text": "' . $receiverCountry . '",
                          "size": "sm",
                          "weight": "regular"
                        },
                        {
                          "type": "text",
                          "text": "' . $receiverEmail . '",
                          "size": "sm",
                          "color": "#AAAAAA"
                        },
                        {
                          "type": "text",
                          "text": "' . $receiverTelephone . '",
                          "size": "sm",
                          "color": "#AAAAAA"
                        },
                        {
                          "type": "separator",
                          "margin": "md",
                          "color": "#FFFFFF"
                        },
                        {
                          "type": "text",
                          "text": "' . $productCount . ' product(s) in this shipment",
                          "margin": "none",
                          "size": "xs",
                          "color": "#2C468F"
                        },
                        {
                          "type": "separator"
                        },';
            foreach($declares as $declare){
                $json .= '
                        {
                          "type": "box",
                          "layout": "horizontal",
                          "margin": "md",
                          "contents": [
                            {
                              "type": "box",
                              "layout": "vertical",
                              "flex": 7,
                              "margin": "md",
                              "contents": [
                                {
                                  "type": "text",
                                  "text": "' . $declare['type'] . '",
                                  "margin": "md",
                                  "size": "sm",
                                  "weight": "bold",
                                  "color": "#666666"
                                },
                                {
                                  "type": "text",
                                  "text": "' . $declare['qty'] . ' pcs.",
                                  "flex": 2,
                                  "margin": "none",
                                  "size": "xs",
                                  "color": "#1DA024"
                                }
                              ]
                            },
                            {
                              "type": "box",
                              "layout": "vertical",
                              "flex": 3,
                              "contents": [
                                {
                                  "type": "text",
                                  "text": "' . $declare['value'] . ' THB",
                                  "flex": 7,
                                  "size": "md",
                                  "align": "end",
                                  "weight": "bold",
                                  "color": "#666666"
                                }
                              ]
                            }
                          ]
                        },';
            }
            $json .= ' {
                          "type": "separator",
                          "margin": "md",
                          "color": "#FFFFFF"
                        }
                      ]
                    }
                  ]
                },
                "footer": {
                  "type": "box",
                  "layout": "horizontal",
                  "contents": [
                    {
                      "type": "button",
                      "action": {
                        "type": "uri",
                        "label": "Create Pickup",
                        "uri": "line://app/1600551303-AG30JpJq"
                      },
                      "style": "primary"
                    }
                  ]
                }
              }
            ';

        }
        
        return $json;
    }
    
    function pushMessage(Request $request){
        
        //รับ id ของผู้ใช้
        $id = "U06ef2762df7dbe7aa4a38c6f947350f9";
        
        $arrayPostData['to'] = $id;
        //         $arrayPostData['messages'][0]['type'] = "text";
        //         $arrayPostData['messages'][0]['text'] = "สวัสดีจ้าาา";
        //         $arrayPostData['messages'][1]['type'] = "sticker";
        //         $arrayPostData['messages'][1]['packageId'] = "2";
        //         $arrayPostData['messages'][1]['stickerId'] = "34";
        
        $json = $this::getCreatePickupJson();
        
        $altText = "create pickup";
        $arrayPostData['messages'][0] = LineManager::getFormatFlexMessage($json,$altText);
        
        LineManager::pushMessage($arrayPostData);
        
        
        exit;
    }
    
    private function getCreatePickupJson()
    {
        
        $json = '
            {
    "type": "bubble",
                "hero": {
                  "type": "image",
                  "url": "https://app.fastship.co/images/line/line_header.png?t=' . time() . '",
                  "margin": "none",
                  "size": "full",
                  "aspectRatio": "3:1",
                  "aspectMode": "cover"
                },
    "body": {
      "type": "box",
      "layout": "horizontal",
      "spacing": "md",
      "contents": [
        {
          "type": "box",
          "layout": "vertical",
          "flex": 10,
          "contents": [
            {
              "type": "text",
              "text": "pickup list",
              "margin": "none",
              "size": "xs",
              "color": "#2C468F"
            },
            {
              "type": "separator",
              "margin": "xs",
              "color": "#DDDDDD"
            },
            {
              "type": "separator",
              "margin": "lg",
              "color": "#FFFFFF"
            },
            {
              "type": "box",
              "layout": "horizontal",
              "contents": [
                {
                  "type": "text",
                  "text": "SHIPMENT#15215232",
                  "flex": 10,
                  "size": "lg",
                  "weight": "bold",
                  "color": "#F15A22"
                }
              ]
            },
            {
              "type": "box",
              "layout": "horizontal",
              "contents": [
                {
                  "type": "image",
                  "url": "https://app.fastship.co/images/agent/UPS.gif",
                  "flex": 3,
                  "align": "start",
                  "gravity": "top",
                  "aspectRatio": "16:9",
                  "aspectMode": "cover"
                },
                {
                  "type": "box",
                  "layout": "vertical",
                  "flex": 4,
                  "margin": "sm",
                  "contents": [
                    {
                      "type": "text",
                      "text": "1200 gram",
                      "weight": "bold"
                    },
                    {
                      "type": "text",
                      "text": "10x10x10 cm.",
                      "margin": "none",
                      "size": "xs"
                    }
                  ]
                },
                {
                  "type": "box",
                  "layout": "vertical",
                  "flex": 4,
                  "margin": "sm",
                  "contents": [
                    {
                      "type": "text",
                      "text": "760.-",
                      "margin": "none",
                      "size": "xl",
                      "align": "end",
                      "weight": "bold",
                      "color": "#24B718"
                    }
                  ]
                }
              ]
            },
            {
              "type": "separator",
              "color": "#FFFFFF"
            },
            {
              "type": "text",
              "text": "Receiver Name",
              "size": "sm",
              "weight": "bold"
            },
            {
              "type": "text",
              "text": "Address1 City state postcode ",
              "size": "sm",
              "wrap": true
            },
            {
              "type": "text",
              "text": "United States",
              "size": "sm",
              "weight": "regular"
            },
            {
              "type": "separator",
              "margin": "md",
              "color": "#DDDDDD"
            }
          ]
        }
      ]
    },
    "footer": {
      "type": "box",
      "layout": "horizontal",
      "contents": [
        {
          "type": "box",
          "layout": "vertical",
          "contents": [
            {
              "type": "button",
              "action": {
                "type": "postback",
                "label": "ยืนยันการส่ง",
                "text": "ให้ฟาสต์ชิปไปรับถึงบ้าน",
                "data": "Pickup_AtHome"
              },
              "flex": 10,
              "style": "primary"
            },
            {
              "type": "separator",
              "margin": "md",
              "color": "#FFFFFF"
            },
            {
              "type": "button",
              "action": {
                "type": "uri",
                "label": "สร้างพัสดุเพิ่ม",
                "uri": "https://linecorp.com"
              },
              "flex": 10,
              "style": "secondary"
            }
          ]
        }
      ]
    }
  }
            ';
        
        return $json;
    }

    
}
