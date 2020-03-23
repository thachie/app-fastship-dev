<?php
namespace App\Lib\Line;

use Illuminate\Support\Facades\Storage;

class LineManager
{
    //test
    //protected static $token = "qoKTDYIuzNnJNtu9st2u3v77vjoKRhaAGTfPj7aIX7/I1YGgE52h2Zbf8T1qbBO5IBn/88XGVtdyVia3/FFp4PnWlFzTu9b0PFobng0b1qY7wK8FT4t5m7JLDvlCgsh3n2u4I5438Q6lHKuE+gCZCAdB04t89/1O/w1cDnyilFU=";
    
    //production
    protected static $token = "MbmhkbBvXwTTMyz+/qG76l0ijwKXhOVg1UkjKPAphqkWt6essHynXjY2Qdx9IDndWl0xTzIs6iimBy1ngUQ97V3PptysZsYOO7slEIuPbfLgFyC3eB12tiaF3imFG4/Bckc22KJuWOHq9HjYetx26VGUYhWQfeY8sLGRXgo3xvw=";
    
    
    public static function mappingResponse($resp){
        
        $response = array();

        //Dialogflow
        if(isset($resp['responseId'])){
            
            $response['MessageId'] = $resp['responseId'];
            $response['Text'] = $resp['queryResult']['queryText'];
            if(isset($resp['queryResult']['parameters']['command'])){
                $response['Command'] = $resp['queryResult']['parameters']['command'];
            }else{
                $response['Command'] = "";
            }
            $response['ReplyToken'] = $resp['originalDetectIntentRequest']['payload']['data']['replyToken'];
            $response['Timestamp'] = $resp['originalDetectIntentRequest']['payload']['data']['timestamp'];
            $response['UserId'] = $resp['originalDetectIntentRequest']['payload']['data']['source']['userId'];
            $response['Parameters'] = json_encode($resp['queryResult']['parameters']);
            
        }else if(isset($resp['events'][0])){

            $type = $resp['events'][0]['type'];
            if($type == "message"){

                $messageType = $resp['events'][0]['message']['type'];
                $response['MessageId'] = $resp['events'][0]['message']['id'];
                if($messageType == "text"){
                    $response['Text'] = $resp['events'][0]['message']['text'];
                }else if($messageType == "location"){
                    if(isset($resp['events'][0]['message']['title'])){
                        $response['Text'] = $resp['events'][0]['message']['title'] . " " . $resp['events'][0]['message']['address'];
                    }else{
                        $response['Text'] = $resp['events'][0]['message']['address'];
                    }
                }else if($messageType == "sticker"){
                    if(isset($resp['events'][0]['message']['stickerId'])){
                        $response['Text'] = $resp['events'][0]['message']['id'] . " " . $resp['events'][0]['message']['stickerId'] . " " . $resp['events'][0]['message']['packageId'] ;
                    }else{
                        $response['Text'] = $resp['events'][0]['message']['stickerId'] . " " . $resp['events'][0]['message']['packageId'] ;
                    }
                }
                $response['Type'] = $messageType;
                $response['Command'] = "";
                $response['ReplyToken'] = $resp['events'][0]['replyToken'];
                $response['Timestamp'] = $resp['events'][0]['timestamp'];
                $response['UserId'] = $resp['events'][0]['source']['userId'];
                if($messageType == "location"){
                    
                    if(isset($resp['events'][0]['message']['title'])){
                        $response['Parameters'] = json_encode(
                        array(
                            "title" => $resp['events'][0]['message']['title'],
                            "address" => $resp['events'][0]['message']['address'],
                            "latitude" => $resp['events'][0]['message']['latitude'],
                            "longitude" => $resp['events'][0]['message']['longitude'],
                        ));
                    }else{
                        $response['Parameters'] = json_encode(
                        array(
                            "address" => $resp['events'][0]['message']['address'],
                            "latitude" => $resp['events'][0]['message']['latitude'],
                            "longitude" => $resp['events'][0]['message']['longitude'],
                        ));
                    }
                    
                }else{
                    $response['Parameters'] = "";
                }
            
            }else if($type == "postback"){
                
                if(isset($resp['events'][0]['postback']['params'])){
                    
                    $response['MessageId'] = $resp['events'][0]['timestamp'];
                    $response['Text'] = $resp['events'][0]['postback']['params']['datetime'];
                    $response['Type'] = $resp['events'][0]['postback']['data'];
                    $response['Command'] = "";
                    $response['ReplyToken'] = $resp['events'][0]['replyToken'];
                    $response['Timestamp'] = $resp['events'][0]['timestamp'];
                    $response['UserId'] = $resp['events'][0]['source']['userId'];
                    $response['Parameters'] = json_encode($resp['events'][0]['postback']['params']);
                    
                }else{
                    
                    $postbackData = $resp['events'][0]['postback']['data'];
                    list($type,$data) = explode("=",$postbackData);
                    
                    $response['MessageId'] = $resp['events'][0]['timestamp'];
                    $response['Text'] = $data;
                    $response['Type'] = $type;
                    $response['Command'] = "";
                    $response['ReplyToken'] = $resp['events'][0]['replyToken'];
                    $response['Timestamp'] = $resp['events'][0]['timestamp'];
                    $response['UserId'] = $resp['events'][0]['source']['userId'];
                    $response['Parameters'] = json_encode($resp['events'][0]['postback']['data']);
                }
                
   
            }else if($type == "follow"){
                
                $response['MessageId'] = $resp['events'][0]['timestamp'];
                $response['Text'] = "เพิ่งเพิ่ม @fastship.co เป็นเพื่อน";
                $response['Type'] = $type;
                $response['Command'] = "";
                $response['ReplyToken'] = $resp['events'][0]['replyToken'];
                $response['Timestamp'] = $resp['events'][0]['timestamp'];
                $response['UserId'] = $resp['events'][0]['source']['userId'];
                $response['Parameters'] = "";
                
            }else if($type == "unfollow"){
                
                $response['MessageId'] = $resp['events'][0]['timestamp'];
                $response['Text'] = "บล๊อค  @fastship.co ไปซะแล้ว";
                $response['Type'] = $type;
                $response['Command'] = "";
                $response['ReplyToken'] = "";
                $response['Timestamp'] = $resp['events'][0]['timestamp'];
                $response['UserId'] = $resp['events'][0]['source']['userId'];
                $response['Parameters'] = "";
                
            }
        }
        return $response;
        
    }
    
    public static function getFormatTextMessage($text)
    {
        $datas = [];
        $datas['type'] = 'text';
        $datas['text'] = $text;
        return $datas;
    }
    
    public static function getFormatStickerMessage($packageId,$stickerId)
    {
        $datas = [];
        $datas['type'] = 'sticker';
        $datas['packageId'] = $packageId;
        $datas['stickerId'] = $stickerId;
        return $datas;
    }
    
    public static function getFormatImageMessage($imageUrl)
    {
        $datas = [];
        $datas['type'] = 'image';
        $datas['contentProvider'] = array(
            'type' => 'external',
            'originalContentUrl' => $imageUrl,
            'previewImageUrl' => $imageUrl,
        );
        return $datas;

    }
    
    public static function getFormatFlexMessage($json,$altText){
        
        $datas = [];
        $datas['type'] = 'flex';
        $datas['altText'] = $altText;
        $datas['contents'] = json_decode($json,true);
        return $datas;
    }
    
    public static function replyMessage($encodeJson)
    {
        $datasReturn = [];
        
        $url = "https://api.line.me/v2/bot/message/reply";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $encodeJson,
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer ".self::$token,
                "cache-control: no-cache",
                "content-type: application/json; charset=UTF-8",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $datasReturn['result'] = 'E';
            $datasReturn['message'] = $err;
        } else {
            if($response == "{}"){
                $datasReturn['result'] = 'S';
                $datasReturn['message'] = 'Success';
            }else{
                $datasReturn['result'] = 'E';
                $datasReturn['message'] = $response;
            }
        }
        return $datasReturn;
    }

    public static function pushMessage($arrayPostData)
    {
        $strUrl = "https://api.line.me/v2/bot/message/push";
        
        $arrayHeader = array();
        $arrayHeader[] = "Content-Type: application/json";
        $arrayHeader[] = "Authorization: Bearer {". self::$token ."}";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$strUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayPostData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close ($ch);
    }
    
    public static function getProfile($userId)
    {
        $strUrl = "https://api.line.me/v2/bot/profile/".$userId;
        
        $arrayHeader = array();
        $arrayHeader[] = "Content-Type: application/json";
        $arrayHeader[] = "Authorization: Bearer {". self::$token ."}";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$strUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close ($ch);
        
        $profile = json_decode($result);
        
        return $profile;
    }
    
    public static function getContent($messageId,$type="image")
    {
        $strUrl = "https://api.line.me/v2/bot/message/" . $messageId . "/content";
        
        $arrayHeader = array();
        $arrayHeader[] = "Content-Type: application/json";
        $arrayHeader[] = "Authorization: Bearer {". self::$token ."}";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$strUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close ($ch);

        if($type == "image"){
            Storage::disk('local')->put('public/chat_upload/' . $messageId . '.jpg', $result);
            $return = url('storage/chat_upload/'.$messageId.'.jpg');
        }else if($type == "audio"){
            Storage::disk('local')->put('public/chat_upload/' . $messageId . '.mp3', $result);
            $return = url('storage/chat_upload/'.$messageId.'.mp3');
        }else if($type == "video"){
            Storage::disk('local')->put('public/chat_upload/' . $messageId . '.mov', $result);
            $return = url('storage/chat_upload/'.$messageId.'.mov');
        }
        
        
        return $return;
        
    }
    
    public static function jsonPayment($args)
    {
        
        extract($args);
        
        $liffURL = "line://app/1653749064-2ax4kWx9"; //production
        //$liffURL = "line://app/1600551303-bwDq9N9e"; //sandbox
        
        $json = '{
            "type": "bubble",
            "hero": {
              "type": "image",
              "url": "https://app.fastship.co/images/line/line_header.png?t=1244",
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
                          "text": "Payment Notification",
                          "flex": 10,
                          "size": "lg",
                          "align": "center",
                          "weight": "bold",
                          "color": "#F15A22"
                        },
                        {
                          "type": "text",
                          "text": "PICKUP# ' . $pickupId . '",
                          "flex": 10,
                          "size": "xs",
                          "align": "center",
                          "weight": "regular",
                          "color": "#AAAAAA"
                        }
                      ]
                    },
                    {
                      "type": "separator",
                      "margin": "md",
                      "color": "#DDDDDD"
                    },
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "margin": "lg",
                      "contents": [
                        {
                          "type": "text",
                          "text": "Shipping",
                          "flex": 8
                        },
                        {
                          "type": "text",
                          "text": "' . $shipping . ' ฿",
                          "flex": 2,
                          "align": "end"
                        }
                      ]
                    },
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "margin": "sm",
                      "contents": [
                        {
                          "type": "text",
                          "text": "Pickup",
                          "flex": 8
                        },
                        {
                          "type": "text",
                          "text": "' . $pickcost . ' ฿",
                          "flex": 2,
                          "align": "end"
                        }
                      ]
                    },';
        if($discount > 0){
            $json .= '{
                      "type": "box",
                      "layout": "horizontal",
                      "margin": "sm",
                      "contents": [
                        {
                          "type": "text",
                          "text": "Discount",
                          "flex": 8,
                          "color": "#FF0000"
                        },
                        {
                          "type": "text",
                          "text": "' . $discount. ' ฿",
                          "color": "#FF0000",
                          "flex": 2,
                          "align": "end"
                        }
                      ]
                    },';
        }
        if($paid > 0){
            $json .= '{
                      "type": "separator",
                      "margin": "md",
                      "color": "#DDDDDD"
                    },
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "margin": "sm",
                      "contents": [
                        {
                          "type": "text",
                          "text": "Paid",
                          "flex": 8,
                          "weight": "bold",
                          "color": "#4668BF"
                        },
                        {
                          "type": "text",
                          "text": "' . $paid . ' ฿",
                          "flex": 2,
                          "align": "end",
                          "weight": "bold",
                          "color": "#4668BF"
                        }
                      ]
                    },';
        }
        if($packcost > 0){
            $json .= '{
                      "type": "box",
                      "layout": "horizontal",
                      "margin": "sm",
                      "contents": [
                        {
                          "type": "text",
                          "text": "Re-pack",
                          "flex": 8
                        },
                        {
                          "type": "text",
                          "text": "' . $packcost . ' ฿",
                          "flex": 2,
                          "align": "end"
                        }
                      ]
                    },';
        }
        if($insurance > 0){
            $json .= '{
                      "type": "box",
                      "layout": "horizontal",
                      "margin": "sm",
                      "contents": [
                        {
                          "type": "text",
                          "text": "Insurance",
                          "flex": 8
                        },
                        {
                          "type": "text",
                          "text": "' . $insurance . ' ฿",
                          "flex": 2,
                          "align": "end"
                        }
                      ]
                    },';
        }
        if($additioncost > 0){
            $json .= '{
                      "type": "box",
                      "layout": "horizontal",
                      "margin": "sm",
                      "contents": [
                        {
                          "type": "text",
                          "text": "Additional",
                          "flex": 8
                        },
                        {
                          "type": "text",
                          "text": "' . $additioncost . ' ฿",
                          "flex": 2,
                          "align": "end"
                        }
                      ]
                    },';
        }
        $json .= '{
                      "type": "separator",
                      "margin": "md",
                      "color": "#DDDDDD"
                    },{
                      "type": "box",
                      "layout": "horizontal",
                      "margin": "md",
                      "contents": [
                        {
                          "type": "text",
                          "text": "Total",
                          "flex": 7,
                          "size": "xl",
                          "weight": "bold",
                          "color": "#42BB1E"
                        },
                        {
                          "type": "text",
                          "text": "' . $unpaid . ' ฿",
                          "flex": 3,
                          "size": "xl",
                          "align": "end",
                          "weight": "bold",
                          "color": "#42BB1E"
                        }
                      ]
                    },
                    {
                      "type": "separator",
                      "margin": "md",
                      "color": "#FFFFFF"
                    },
                    {
                      "type": "text",
                      "text": "Disclaimer:",
                      "size": "xxs",
                      "weight": "bold",
                      "color": "#CCCCCC"
                    },
                    {
                      "type": "text",
                      "text": "This document is only generated for the purpose of notify of payment and in no way shall it be interupted as receipt of such shown amount by Fastship for any purposes.",
                      "size": "xxs",
                      "align": "start",
                      "color": "#CCCCCC",
                      "wrap": true
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
                        "type": "uri",
                        "label": "Pay via QR Code",
                        "uri": "' . $paymentNotifyUrl . '"
                      },
                      "flex": 10,
                      "style": "primary"
                    }
                  ]
                }
              ]
            }
          }';
        return $json;
        
    }
    
}