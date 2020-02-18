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
    
    
    
}