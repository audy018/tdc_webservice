<?php

namespace app\controllers;
use yii\rest\ActiveController;
use app\models\BuffeConfig;
use app\models\UserProfile;
use app\models\User;

class BuffeConfigController extends ActiveController
{
    public $modelClass = 'app\models\BuffeConfig';
    
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => [],
            'create' => [],
            'update' => [],
            'delete' => [],
            'test'  => ['GET'],
        ];
    }    
    public function actionTest() {
        $json='[
  {
    "id": 1397723432409,
    "usmobile": null,
    "sitecode": "05279",
    "ptcode": "9",
    "ptcodefull": "0527900009",
    "ptid_link": null,
    "ptid_key": 1397723432409,
    "reccheck": null,
    "cid": "3450100816089",
    "cid_check": null,
    "title": "???",
    "name": "???????",
    "surname": "????????",
    "uidadd": 1487,
    "dadd": "2014-04-17T15:33:04",
    "uidedit": 2526,
    "dedit": "2015-10-07T16:49:50",
    "regdate": null,
    "regdatedb": null,
    "v2": "09/09/1969",
    "bdatedb": "1969-09-09T00:00:00",
    "age": "46",
    "v3": "1",
    "mobile": "0874306497",
    "homephone": "",
    "telcontact1": "",
    "telcontact2": "",
    "telcontact3": "",
    "add1n1": "65",
    "add1n2": "",
    "add1n3": "",
    "add1n4": "",
    "add1n5": "2",
    "add1n6": "¼Ñ¡áÇè¹   ",
    "add1n7": "¨Ñ§ËÒÃ   ",
    "add1n8": "ÃéÍÂàÍç´",
    "add1n9": "45000",
    "hospitalcurrent": "11076: ¹Ñ°Ç´Õ ÍØè¹¨Ñ§ËÒÃ",
    "hn": null,
    "selfenroll": null,
    "selfenrolldate": null,
    "vconsent": "1",
    "vconsentdate": "17/04/2014",
    "vconsentdatedb": "2014-04-17T15:39:13",
    "sys_assigncheck": null,
    "dlastcheck": "2014-04-19T17:41:30",
    "confirmdate": "2014-04-19T17:41:30",
    "confirm": 1159,
    "venroll": "1",
    "venrolldate": "17/04/2014",
    "venrolldatedb": "2014-04-17T15:39:13",
    "pay": 1015,
    "paytime": "2014-08-25T00:00:00",
    "recieve": null,
    "recievedate": null,
    "bankdate": null,
    "sys_dateoficf": "16/04/2557",
    "sys_dateoficfdb": "2014-04-16T00:00:00",
    "sys_ecoficf": "20/12/2556",
    "sys_ecoficfdb": "2013-12-20T00:00:00",
    "rstat": "2",
    "addr": "65 ËÁÙè 2 µ.¼Ñ¡áÇè¹ Í.¨Ñ§ËÒÃ ¨.ÃéÍÂàÍç´",
    "lat": "16.1718379",
    "lng": "103.5384721",
    "geocode": "{\n   \"results\" : [\n      {\n         \"address_components\" : [\n            {\n               \"long_name\" : \"Phak Waen Sub District Community Police Station\",\n               \"short_name\" : \"Phak Waen Sub District Community Police Station\",\n               \"types\" : [ \"point_of_interest\", \"establishment\" ]\n            },\n            {\n               \"long_name\" : \"Phak Waen\",\n               \"short_name\" : \"Phak Waen\",\n               \"types\" : [ \"locality\", \"political\" ]\n            },\n            {\n               \"long_name\" : \"Changhan District\",\n               \"short_name\" : \"Changhan District\",\n               \"types\" : [ \"administrative_area_level_2\", \"political\" ]\n            },\n            {\n               \"long_name\" : \"Roi Et\",\n               \"short_name\" : \"à¸ˆ.à¸£à¹‰à¸­à¸¢à¹€à¸­à¹‡à¸”\",\n               \"types\" : [ \"administrative_area_level_1\", \"political\" ]\n            },\n            {\n               \"long_name\" : \"Thailand\",\n               \"short_name\" : \"TH\",\n               \"types\" : [ \"country\", \"political\" ]\n            },\n            {\n               \"long_name\" : \"45000\",\n               \"short_name\" : \"45000\",\n               \"types\" : [ \"postal_code\" ]\n            }\n         ],\n         \"formatted_address\" : \"Phak Waen Sub District Community Police Station, Phak Waen, Changhan District, Roi Et 45000, Thailand\",\n         \"geometry\" : {\n            \"location\" : {\n               \"lat\" : 16.1718379,\n               \"lng\" : 103.5384721\n            },\n            \"location_type\" : \"APPROXIMATE\",\n            \"viewport\" : {\n               \"northeast\" : {\n                  \"lat\" : 16.1731868802915,\n                  \"lng\" : 103.5398210802915\n               },\n               \"southwest\" : {\n                  \"lat\" : 16.1704889197085,\n                  \"lng\" : 103.5371231197085\n               }\n            }\n         },\n         \"partial_match\" : true,\n         \"types\" : [ \"point_of_interest\", \"establishment\" ]\n      },\n      {\n         \"address_components\" : [\n            {\n               \"long_name\" : \"Tathataram Dharma Practice Center\",\n               \"short_name\" : \"Tathataram Dharma Practice Center\",\n               \"types\" : [ \"point_of_interest\", \"establishment\" ]\n            },\n            {\n               \"long_name\" : \"Phak Waen\",\n               \"short_name\" : \"Phak Waen\",\n               \"types\" : [ \"locality\", \"political\" ]\n            },\n            {\n               \"long_name\" : \"Changhan District\",\n               \"short_name\" : \"Changhan District\",\n               \"types\" : [ \"administrative_area_level_2\", \"political\" ]\n            },\n            {\n               \"long_name\" : \"Roi Et\",\n               \"short_name\" : \"à¸ˆ.à¸£à¹‰à¸­à¸¢à¹€à¸­à¹‡à¸”\",\n               \"types\" : [ \"administrative_area_level_1\", \"political\" ]\n            },\n            {\n               \"long_name\" : \"Thailand\",\n               \"short_name\" : \"TH\",\n               \"types\" : [ \"country\", \"political\" ]\n            },\n            {\n               \"long_name\" : \"45000\",\n               \"short_name\" : \"45000\",\n               \"types\" : [ \"postal_code\" ]\n            }\n         ],\n         \"formatted_address\" : \"Tathataram Dharma Practice Center, Phak Waen, Changhan District, Roi Et 45000, Thailand\",\n         \"geometry\" : {\n            \"location\" : {\n               \"lat\" : 16.1820905,\n               \"lng\" : 103.5410191\n            },\n            \"location_type\" : \"APPROXIMATE\",\n            \"viewport\" : {\n               \"northeast\" : {\n                  \"lat\" : 16.1834394802915,\n                  \"lng\" : 103.5423680802915\n               },\n               \"southwest\" : {\n                  \"lat\" : 16.1807415197085,\n                  \"lng\" : 103.5396701197085\n               }\n            }\n         },\n         \"partial_match\" : true,\n         \"types\" : [ \"place_of_worship\", \"point_of_interest\", \"establishment\" ]\n      }\n   ],\n   \"status\" : \"OK\"\n}\n",
    "pidcheck": 1,
    "agecheck": null,
    "sitezone": "07",
    "siteprov": "45",
    "siteamp": "17",
    "sitetmb": "07",
    "target": "1397723432409",
    "xsourcex": "05279",
    "user_create": null,
    "create_date": null,
    "user_update": null,
    "update_date": null,
    "add1n8code": null,
    "add1n7code": null,
    "add1n6code": null,
    "value1": "",
    "value2": "",
    "value3": "",
    "edattype": "",
    "error": null
  }
]';
        //print_r(json_decode($json));
        $data=  json_decode($json,true);
        //print_r($data);exit;
        //echo $data[0]['geocode'];exit;
        $json='{
   "results" : [
      {
         "address_components" : [
            {
               "long_name" : "Phak Waen Sub District Community Police Station",
               "short_name" : "Phak Waen Sub District Community Police Station",
               "types" : [ "point_of_interest", "establishment" ]
            },
            {
               "long_name" : "Phak Waen",
               "short_name" : "Phak Waen",
               "types" : [ "locality", "political" ]
            },
            {
               "long_name" : "Changhan District",
               "short_name" : "Changhan District",
               "types" : [ "administrative_area_level_2", "political" ]
            },
            {
               "long_name" : "Roi Et",
               "short_name" : "à¸ˆ.à¸£à¹‰à¸­à¸¢à¹€à¸­à¹‡à¸”",
               "types" : [ "administrative_area_level_1", "political" ]
            },
            {
               "long_name" : "Thailand",
               "short_name" : "TH",
               "types" : [ "country", "political" ]
            },
            {
               "long_name" : "45000",
               "short_name" : "45000",
               "types" : [ "postal_code" ]
            }
         ],
         "formatted_address" : "Phak Waen Sub District Community Police Station, Phak Waen, Changhan District, Roi Et 45000, Thailand",
         "geometry" : {
            "location" : {
               "lat" : 16.1718379,
               "lng" : 103.5384721
            },
            "location_type" : "APPROXIMATE",
            "viewport" : {
               "northeast" : {
                  "lat" : 16.1731868802915,
                  "lng" : 103.5398210802915
               },
               "southwest" : {
                  "lat" : 16.1704889197085,
                  "lng" : 103.5371231197085
               }
            }
         },
         "partial_match" : true,
         "types" : [ "point_of_interest", "establishment" ]
      },
      {
         "address_components" : [
            {
               "long_name" : "Tathataram Dharma Practice Center",
               "short_name" : "Tathataram Dharma Practice Center",
               "types" : [ "point_of_interest", "establishment" ]
            },
            {
               "long_name" : "Phak Waen",
               "short_name" : "Phak Waen",
               "types" : [ "locality", "political" ]
            },
            {
               "long_name" : "Changhan District",
               "short_name" : "Changhan District",
               "types" : [ "administrative_area_level_2", "political" ]
            },
            {
               "long_name" : "Roi Et",
               "short_name" : "à¸ˆ.à¸£à¹‰à¸­à¸¢à¹€à¸­à¹‡à¸”",
               "types" : [ "administrative_area_level_1", "political" ]
            },
            {
               "long_name" : "Thailand",
               "short_name" : "TH",
               "types" : [ "country", "political" ]
            },
            {
               "long_name" : "45000",
               "short_name" : "45000",
               "types" : [ "postal_code" ]
            }
         ],
         "formatted_address" : "Tathataram Dharma Practice Center, Phak Waen, Changhan District, Roi Et 45000, Thailand",
         "geometry" : {
            "location" : {
               "lat" : 16.1820905,
               "lng" : 103.5410191
            },
            "location_type" : "APPROXIMATE",
            "viewport" : {
               "northeast" : {
                  "lat" : 16.1834394802915,
                  "lng" : 103.5423680802915
               },
               "southwest" : {
                  "lat" : 16.1807415197085,
                  "lng" : 103.5396701197085
               }
            }
         },
         "partial_match" : true,
         "types" : [ "place_of_worship", "point_of_interest", "establishment" ]
      }
   ],
   "status" : "OK"
}';
        return json_decode($json);
        return $model=\Yii::$app->dbtdc->createCommand("select * from tb_data_1 where id=1;")->queryAll();
//          $model=\Yii::$app->dbtdc->createCommand("SHOW CREATE TABLE buffe_command")->queryAll();
//          
//          \yii\helpers\VarDumper::dump($model);exit;
//        $template = \app\models\BuffeTemplate::findOne('1');
//        $command = new \app\models\BuffeCommand();
//        $command->load($template->toArray());
//        \yii\helpers\VarDumper::dump($command);
//        $command->save();
//        exit;
//        $model=\Yii::$app->dbtdc->createCommand("SHOW CREATE TABLE buffe_command")->queryAll();
//          print_r($model);exit;
//        return $model;
        
    }
    public function actions()
    {
        $actions = parent::actions();
        
        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
 
        return $actions;
    }    

    public function actionPing() 
    {
        $token = \Yii::$app->request->get('token');

        $user=User::findIdentityByAccessToken($token);
        $sitecode=  UserProfile::findOne($user->id)->sitecode;
        
        $sql="Update buffe_config set last_ping=NOW() where id='{$sitecode}'";
        \Yii::$app->dbtdc->createCommand($sql)->query();

        $cpu = \Yii::$app->request->get('cpu');
        $ram = \Yii::$app->request->get('ram');  
        $ram_usage = \Yii::$app->request->get('ram_usage');  
        $cpu_serv = \Yii::$app->request->get('cpu_serv');
        $ram_serv = \Yii::$app->request->get('ram_serv');  
        $ram_serv_usage = \Yii::$app->request->get('ram_serv_usage');  
        $sql="Update buffe_config set cpu='{$cpu}',ram='{$ram}',ram_usage='{$ram_usage}',cpu_serv='{$cpu_serv}',ram_serv='{$ram_serv}',ram_serv_usage='{$ram_serv_usage}' where id='{$sitecode}'";
        \Yii::$app->dbtdc->createCommand($sql)->query();
        
        $result['result']="OK";
        return $result;
    }
    public function prepareDataProvider()
    {   
        //$token = $_GET['token'];
        $token = \Yii::$app->request->get('token');

        $his_version = \Yii::$app->request->get('his_version');
        $his_type = \Yii::$app->request->get('his_type');
        $buffe_version = \Yii::$app->request->get('buffe_version');
        $user=User::findIdentityByAccessToken($token);
        $sitecode=  UserProfile::findOne($user->id)->sitecode;

        $config = BuffeConfig::findOne($sitecode);
        if (\count($config)>0)
        {
            if (($config->his_version != $his_version)&&($his_version!=""))
            {
                $config->his_version=$his_version;
                $needsave=true;
            }
            if (($config->his_type != $his_type)&&($his_type!=""))
            {
                $config->his_type=$his_type;
                $needsave=true;  
            }
            if (($config->buffe_version != $buffe_version)&&($buffe_version!=""))
            {
                $config->buffe_version=$buffe_version;
                $needsave=true;
            }    
            if ($needsave) 
            {
                $config->save();
            }
        }
        else
        {
            $newconfig = new BuffeConfig();
            $newconfig->id = $sitecode;
            $newconfig->his_version=$his_version;
            $newconfig->his_type=$his_type;
            $zone = \Yii::$app->db->createCommand("SELECT name,zone_code from all_hospital_thai where hcode='{$sitecode}' limit 1")->queryOne();
            $newconfig->service_url = "https://webservice".$zone['zone_code'].".thaicarecloud.org";                
            $newconfig->save();
            
            $config = BuffeConfig::findOne($sitecode);
        }
            
        return $config;
    } 
    public function actionLogConnection(){
        try{
            $token = \Yii::$app->request->get('token')==''?'':\Yii::$app->request->get('token');
            $action = \Yii::$app->request->get('action')==''?'':\Yii::$app->request->get('action');
            $start_tdc = \Yii::$app->request->get('start_tdc')==''?'0000-00-00 00:00:00':\Yii::$app->request->get('start_tdc');
            $stop_tdc = \Yii::$app->request->get('stop_tdc')==''?'0000-00-00 00:00:00':\Yii::$app->request->get('stop_tdc');
            $user = User::findIdentityByAccessToken($token);
            $sitecode =  UserProfile::findOne($user->id)->sitecode;
            
            if($action == 'insert'){
                $dataLog = \Yii::$app->dbtdc->createCommand("SELECT start_tdc FROM log_connection WHERE sitecode = :sitecode AND stop_tdc = '0000-00-00 00:00:00' ",[':sitecode'=>$sitecode])->queryOne();
                if($dataLog > 0){
                    $stop_tdc2 = $start_tdc;
                    $start_tdc2 = $dataLog['start_tdc'];
                    \Yii::$app->dbtdc->createCommand("UPDATE log_connection SET last_connect=:last_connect,".
                    "num_hour=TIMEDIFF(TIME(:stop_tdc),TIME(:start_tdc)),stop_tdc=:stop_tdc,status='stopped' WHERE sitecode=:sitecode AND stop_tdc = '0000-00-00 00:00:00'",[
                        ':last_connect'=>$stop_tdc2,
                        ':stop_tdc'=>$stop_tdc2,
                        ':start_tdc'=>$start_tdc2,
                        ':sitecode'=>$sitecode
                        ])->query();
                }
                
                        
            \Yii::$app->dbtdc->createCommand("INSERT INTO log_connection (sitecode,last_connect,num_hour,start_tdc,stop_tdc,status) ".
            " VALUES (:sitecode,:last_connect,'00:00:00',:start_tdc,:stop_tdc,'started')",[
                    ':sitecode'=>$sitecode,
                    ':last_connect'=>$start_tdc,
                    ':start_tdc'=>$start_tdc,
                    ':stop_tdc'=>$stop_tdc
                ])->query();
            }
            
            if($action == 'update'){
                
                $dataLog = \Yii::$app->dbtdc->createCommand("SELECT start_tdc FROM log_connection WHERE sitecode = :sitecode AND stop_tdc = '0000-00-00 00:00:00' ",[':sitecode'=>$sitecode])->queryOne();
                if($dataLog > 0){
                    $start_tdc = $dataLog['start_tdc'];
                    \Yii::$app->dbtdc->createCommand("UPDATE log_connection SET last_connect=:last_connect,".
                    "num_hour=TIMEDIFF(TIME(:stop_tdc),TIME(:start_tdc)),start_tdc=:start_tdc,stop_tdc=:stop_tdc,status='stopped' WHERE sitecode=:sitecode AND stop_tdc = '0000-00-00 00:00:00'",[
                        ':last_connect'=>$stop_tdc,
                        ':start_tdc'=>$start_tdc,
                        ':stop_tdc'=>$stop_tdc,
                        ':sitecode'=>$sitecode
                        ])->query();
                }
                
            }
                $result['result']="OK";
        }catch(ErrorException $ex){
            $result['result']="Error";
        }
        return \yii\helpers\Json::encode($result);
    }
}
