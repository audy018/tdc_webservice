<?php

namespace app\controllers;

use yii\rest\ActiveController;
use app\models\User;
use app\models\UserProfile;
use app\models\BuffeTransfer;

class BuffeTransferController extends ActiveController
{
    public $modelClass = 'app\models\BuffeConfig';
    
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'enable' => ['GET'],
            'view' => ['GET'],
            'create' => [],
            'update' => [],
            'delete' => [],
            'sync' => ['POST'],
            'nsync'=> ['POST'],
        ];
    }    

    public function actions()
    {
        $actions = parent::actions();
        
        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
 
        return $actions;
    }    

    public function prepareDataProvider()
    {   
        $token = \Yii::$app->request->get('token');

        $user=User::findIdentityByAccessToken($token);
        if ($user->id == "") return null;
        $sitecode=  UserProfile::findOne($user->id)->sitecode;
        
        if ($sitecode == "") return null;

        return $command = BuffeTransfer::find()->where('sitecode=:sitecode and status is null',[':sitecode'=>$sitecode])->all();
        
    }
    public function beforeAction() 
    {
        $token = \Yii::$app->request->get('token');
        $user=\app\models\User::findIdentityByAccessToken($token);
        //\yii\helpers\VarDumper::dump($user);
        $profile=\app\models\UserProfile::findOne($user->id);
        if (!$profile) return false;
        
        $sitecode=  $profile->sitecode;       
        
        $hospital=\Yii::$app->db->createCommand("select * from all_hospital_thai where hcode='{$sitecode}'")->queryOne();
        $provincecode=$hospital['provincecode'];
        if ($provincecode != "") $config = \Yii::$app->db->createCommand("select * from db_config_province where province='{$provincecode}'")->queryOne();
        $host = $config[server];
        $db = $config[db];
        
        $mysqluser = $config[user];
        $mysqlpasswd = $config[passwd];

        \Yii::$app->dbtdcdata->dsn = "mysql:host={$host};dbname={$db}";
        \Yii::$app->dbtdcdata->username=$mysqluser;
        \Yii::$app->dbtdcdata->password=$mysqlpasswd;
        return true;
    }    
    public function actionEnable() {

        if ((\Yii::$app->request->get('id')=="")||(\Yii::$app->request->get('id') == null))
        {
            $id=0;
        }
        else
        {
            $id = \Yii::$app->request->get('id');
        }
            try {
                $command = BuffeTransfer::findOne($id);
                if ($command) 
                {
                    $command->status=0;
                    $command->save();
                    return $command;
                    return $result['status']="OK";
                }else{
                    $result['status']="Error";
                    $result['Error']="Enable error: ID is not specify.";

                    return $result;                      
                }
            }
            catch (\yii\db\Exception $e)
            {
                $result['status']="Error";
                $result['Error']=$e->getMessage();

                return $result;                  
            }

        //$result['status']="Error";
        //$result['Error']=$command->getErrors();

        //return $result;        
    }
    
    public function actionSync() {
        $token = \Yii::$app->request->get('token');

        $user=User::findIdentityByAccessToken($token);
        $hospcode=  UserProfile::findOne($user->id)->sitecode;
        
        $id = \Yii::$app->request->post('id'); 
        $sitecode = \Yii::$app->request->post('sitecode'); 
        $qleft = \Yii::$app->request->post('qleft');
        if ($qleft=="1") $qleft=0;
        $datadb=\Yii::$app->params['datadb'];
        
        $command = BuffeTransfer::find()->where("id=:id and sitecode=:sitecode",[':id'=>$id,':sitecode'=>$sitecode])->one();

        if (!$command) {
            $command = new BuffeTransfer();
            foreach (\Yii::$app->request->post() as $key => $value) {
                $command->$key=$value;
            }
            $command->save();
        }else{
            foreach (\Yii::$app->request->post() as $key => $value) {
                $command->$key=$value;
            }
            $command->save();            
        }
        $command = BuffeTransfer::find()->where("id=:id and sitecode=:sitecode",[':id'=>$id,':sitecode'=>$sitecode])->one();

        if (($command->result != "")&&($command->table != ""))
        {
            $table=$command->table;
            $results = json_decode($command->result);
            if (\count($results)>0) 
            {                
                if (($command->controller == "replace")||($command->controller == "")) {
                    foreach ($results as $recno => $result) {
                        $setsql="";
                        foreach($result as $field => $data) {
                            $data = addslashes($data);
                            $setsql .= ",`{$field}`='$data'";
                        }
//                        if (strpos(strtolower($setsql),'SITECODE')===false) 
//                        {
//                            $setsql .= ",`SITECODE`='$sitecode'";
//                        }
                        $setsql=  substr($setsql, 1);
                        //$sql="REPLACE INTO `{$datadb}`.`{$command->table}` SET  {$setsql}";
                        $sql="REPLACE INTO `{$command->table}` SET  {$setsql}";
                        \Yii::$app->dbtdcdata->createCommand($sql)->query();
                        $sql="SELECT 1 from `buffe_table_server` WHERE sitecode='$sitecode' and `table`='{$table}'";
                        $tbcount = \Yii::$app->dbtdc->createCommand($sql)->query()->count();
                        if ($tbcount == 0) {
                            $sql="INSERT INTO `buffe_table_server` (sitecode,`table`) VALUES('{$sitecode}','{$table}')";
                            \Yii::$app->dbtdc->createCommand($sql)->query();
                        }
                        $sql = "UPDATE buffe_table_server set qleft='{$qleft}' WHERE sitecode='$sitecode' and `table`='{$table}'";
                        \Yii::$app->dbtdc->createCommand($sql)->query();
                    }
                }
                if ($command->controller == "delete") {
                    foreach ($results as $recno => $result) {
                        $key=\Yii::$app->dbtdc->createCommand("SHOW KEYS FROM `{$command->table}` WHERE Key_name = 'PRIMARY'")->query();
                        foreach ($key as $k => $pk) {
                            $pk1=$pk['Column_name'];
                            $pkv=$result->$pk1;
                            $where .= "AND `$pk1`='$pkv'";
                        }
                        $sql="DELETE FROM `{$command->table}` WHERE 1 $where";
                        \Yii::$app->dbtdcdata->createCommand($sql)->query();
                        $sql="SELECT 1 from `buffe_table_server` WHERE sitecode='$sitecode' and `table`='{$table}'";
                        $tbcount = \Yii::$app->dbtdc->createCommand($sql)->query()->count();
                        if ($tbcount == 0) {
                            $sql="INSERT INTO `buffe_table_server` (sitecode,`table`) VALUES('{$sitecode}','{$table}')";
                            \Yii::$app->dbtdc->createCommand($sql)->query();
                        }                        
                        $sql="UPDATE buffe_table_server set qleft='{$qleft}' WHERE sitecode='$sitecode' and `table`='{$table}'";
                        \Yii::$app->dbtdc->createCommand($sql)->query();                        
                    }
                }
                if ($command->controller == "update") {
                    foreach ($results as $recno => $result) {
                        foreach($result as $field => $data) {
                            $data = addslashes($data);
                            $setsql .= ",`{$field}`='$data'";
                        }                       
                        $setsql=  substr($setsql, 1);
                        $key=\Yii::$app->dbtdc->createCommand("SHOW KEYS FROM `{$command->table}` WHERE Key_name = 'PRIMARY'")->query();
                        foreach ($key as $k => $pk) {
                            $pk1=$pk['Column_name'];
                            $pkv=$result->$pk1;
                            $where .= "AND `$pk1`='$pkv'";
                        }
                        $sql="UPDATE `{$command->table}` SET $setsql WHERE 1 $where";
                        \Yii::$app->dbtdcdata->createCommand($sql)->query();
                        $sql="SELECT 1 from `buffe_table_server` WHERE sitecode='$sitecode' and `table`='{$table}'";
                        $tbcount = \Yii::$app->dbtdc->createCommand($sql)->query()->count();
                        if ($tbcount == 0) {
                            $sql="INSERT INTO `buffe_table_server` (sitecode,`table`) VALUES('{$sitecode}','{$table}')";
                            \Yii::$app->dbtdc->createCommand($sql)->query();
                        }
                        $sql="UPDATE buffe_table_server set qleft='{$qleft}' WHERE sitecode='$sitecode' and `table`='{$table}'";
                        \Yii::$app->dbtdc->createCommand($sql)->query();                        
                    }                    
                }
            }

        }

//        $cpu = \Yii::$app->request->get('cpu');
//        $ram = \Yii::$app->request->get('ram');        
//        $sql="Update buffe_config set cpu='{$cpu}',ram='{$ram}' where id='{$sitecode}'";
//        \Yii::$app->dbtdc->createCommand($sql)->query();
        $cpu = \Yii::$app->request->get('cpu');
        $ram = \Yii::$app->request->get('ram');  
        $ram_usage = \Yii::$app->request->get('ram_usage');  
        $cpu_serv = \Yii::$app->request->get('cpu_serv');
        $ram_serv = \Yii::$app->request->get('ram_serv');  
        $ram_serv_usage = \Yii::$app->request->get('ram_serv_usage');  
        $sql="Update buffe_config set cpu='{$cpu}',ram='{$ram}',ram_usage='{$ram_usage}',cpu_serv='{$cpu_serv}',ram_serv='{$ram_serv}',ram_serv_usage='{$ram_serv_usage}' where id='{$sitecode}'";
        \Yii::$app->dbtdc->createCommand($sql)->query();
        
        //update status
        $command->dupdate=date('Y-d-m h:i:s');
        $command->status='4';

        if ($command->save())
        {
            $resultx['result']="OK";
            $deltransfer=  \Yii::$app->dbtdc->createCommand()->delete("buffe_transfer", ['id'=>$id,'sitecode'=>$sitecode])->query();
        }
        else
        {
            $resultx['result']="Error";
            $resultx['Error']=$command->getErrors();
        }
        
        return \yii\helpers\Json::encode($resultx);
    }
    public function actionNsync() {
        $user=User::findIdentityByAccessToken($token);
        $hospcode=  UserProfile::findOne($user->id)->sitecode;
        
        $token = \Yii::$app->request->get('token');
        $jsondata = \Yii::$app->request->post('jsondata');
        $ndata = \yii\helpers\Json::decode($jsondata);

        try {
            if (\count($ndata)>0) foreach ($ndata as $key => $value) {
                $id=$value['id'];
                $sitecode = $value['sitecode'];
                $table = $value['table'];
                $status = $value['status'];
                $controller = $value['controller'];
                $qleft = $value['qleft'];
                $results = json_decode($value['result']);

                if (($controller == "replace")||($controller == "")) {
                     foreach ($results as $recno => $result) {
                         $setsql="";
                         foreach($result as $field => $data) {
                             $data = addslashes($data);
                             $setsql .= ",`{$field}`='$data'";
                         }

                         $setsql=  substr($setsql, 1);
                         //$sql="REPLACE INTO `{$datadb}`.`{$command->table}` SET  {$setsql}";
                         $sql="REPLACE INTO `{$table}` SET  {$setsql}";
                         \Yii::$app->dbtdcdata->createCommand($sql)->query();
                         $sql="SELECT 1 from `buffe_table_server` WHERE sitecode='$sitecode' and `table`='{$table}'";
                         $tbcount = \Yii::$app->dbtdc->createCommand($sql)->query()->count();
                         if ($tbcount == 0) {
                             $sql="INSERT INTO `buffe_table_server` (sitecode,`table`) VALUES('{$sitecode}','{$table}')";

                             \Yii::$app->dbtdc->createCommand($sql)->query();
                         }
                         $sql = "UPDATE buffe_table_server set qleft='{$qleft}' WHERE sitecode='$sitecode' and `table`='{$table}'";
                         \Yii::$app->dbtdc->createCommand($sql)->query();
                     }
                 }
                 if ($$controller == "delete") {
                     foreach ($results as $recno => $result) {
                         $key=\Yii::$app->dbtdc->createCommand("SHOW KEYS FROM `{$table}` WHERE Key_name = 'PRIMARY'")->query();
                         foreach ($key as $k => $pk) {
                             $pk1=$pk['Column_name'];
                             $pkv=$result->$pk1;
                             $where .= "AND `$pk1`='$pkv'";
                         }
                         $sql="DELETE FROM `{$table}` WHERE 1 $where";
                         \Yii::$app->dbtdcdata->createCommand($sql)->query();
                         $sql="SELECT 1 from `buffe_table_server` WHERE sitecode='$sitecode' and `table`='{$table}'";
                         $tbcount = \Yii::$app->dbtdc->createCommand($sql)->query()->count();
                         if ($tbcount == 0) {
                             $sql="INSERT INTO `buffe_table_server` (sitecode,`table`) VALUES('{$sitecode}','{$table}')";
                             \Yii::$app->dbtdc->createCommand($sql)->query();
                         }                        
                         $sql="UPDATE buffe_table_server set qleft='{$qleft}' WHERE sitecode='$sitecode' and `table`='{$table}'";
                         \Yii::$app->dbtdc->createCommand($sql)->query();                        
                     }
                 }
                 if ($controller == "update") {
                     foreach ($results as $recno => $result) {
                         foreach($result as $field => $data) {
                             $data = addslashes($data);
                             $setsql .= ",`{$field}`='$data'";
                         }                       
                         $setsql=  substr($setsql, 1);
                         $key=\Yii::$app->dbtdc->createCommand("SHOW KEYS FROM `{$table}` WHERE Key_name = 'PRIMARY'")->query();
                         foreach ($key as $k => $pk) {
                             $pk1=$pk['Column_name'];
                             $pkv=$result->$pk1;
                             $where .= "AND `$pk1`='$pkv'";
                         }
                         $sql="UPDATE `{$command->table}` SET $setsql WHERE 1 $where";
                         \Yii::$app->dbtdcdata->createCommand($sql)->query();
                         $sql="SELECT 1 from `buffe_table_server` WHERE sitecode='$sitecode' and `table`='{$table}'";
                         $tbcount = \Yii::$app->dbtdc->createCommand($sql)->query()->count();
                         if ($tbcount == 0) {
                             $sql="INSERT INTO `buffe_table_server` (sitecode,`table`) VALUES('{$sitecode}','{$table}')";
                             \Yii::$app->dbtdc->createCommand($sql)->query();
                         }
                         $sql="UPDATE buffe_table_server set qleft='{$qleft}' WHERE sitecode='$sitecode' and `table`='{$table}'";
                         \Yii::$app->dbtdc->createCommand($sql)->query();                        
                     }                    
                 }            
            }
            $cpu = \Yii::$app->request->get('cpu');
            $ram = \Yii::$app->request->get('ram');  
            $ram_usage = \Yii::$app->request->get('ram_usage');  
            $cpu_serv = \Yii::$app->request->get('cpu_serv');
            $ram_serv = \Yii::$app->request->get('ram_serv');  
            $ram_serv_usage = \Yii::$app->request->get('ram_serv_usage');  
            $sql="Update buffe_config set cpu='{$cpu}',ram='{$ram}',ram_usage='{$ram_usage}',cpu_serv='{$cpu_serv}',ram_serv='{$ram_serv}',ram_serv_usage='{$ram_serv_usage}' where id='{$sitecode}'";
            \Yii::$app->dbtdc->createCommand($sql)->query();

            //update status
            $command->dupdate=date('Y-d-m h:i:s');
            $command->status='4';

            $resultx['result']="OK";   
            
        } catch (ErrorException $e) {
            $resultx['result']="Error";
        }        
        
        return \yii\helpers\Json::encode($resultx);
    }    
    
     public function actionGet43File() {

        // $dsn = "mysql:host=61.19.254.8;dbname=buffe_webservice";

        // $db = new \yii\db\Connection([
        //     'dsn' => $dsn,
        //     'username' => 'webservice',
        //     'password' => 'webservice!@#$%',
        //     'charset' => 'utf8',
        //         // 'enableSchemaCache' => true,
        //         // 'schemaCacheDuration' => 3600,
        // ]);

        $data = \Yii::$app->dbtcc->createCommand("SELECT * FROM ezform_map_f43")->queryAll();
        return \yii\helpers\Json::encode($data);
    }

    public function actionSyncQ() {
        $token = \Yii::$app->request->get('token');
        $user = User::findIdentityByAccessToken($token);
        $hospcode = UserProfile::findOne($user->id)->sitecode;
        $jsondata = \Yii::$app->request->post('jsondata');
        $ndata = \yii\helpers\Json::decode($jsondata);
        try {
            if (count($ndata) > 0) {
                foreach ($ndata as $value) {
                    
                    $sitecode = $value['sitecode'];
                    $table = $value['table'];
                    $qleft = $value['qleft'];

                    $sql = "REPLACE INTO `buffe_table_server` (`sitecode`,`table`,`qleft`) VALUES(:sitecode,:table,:qleft)";
                    \Yii::$app->dbtdc->createCommand($sql,[':sitecode'=>$sitecode,':table'=>$table,':qleft'=>$qleft])->query();
                    
                    $resultx['result'] = "OK";
                }
            }else{
                $resultx['result'] = "OK";
            }
        } catch (ErrorException $ex) {
            $resultx['result'] = "Error";
        }
        return \yii\helpers\Json::encode($resultx);
    }
}
