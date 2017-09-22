<?php

namespace app\controllers;
use yii\rest\ActiveController;

class BuffeConstantsController extends ActiveController
{
    public $modelClass = 'app\models\BuffeConstants';
    
    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => [],
            'create' => [],
            'update' => [],
            'delete' => [],
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
        $user=\app\models\User::findIdentityByAccessToken($token);
        //\yii\helpers\VarDumper::dump($user);
        $profile=\app\models\UserProfile::findOne($user->id);
        $sitecode=  $profile->sitecode;
        $constants = \app\models\BuffeConstants::find()->all();
        
        $khet = \Yii::$app->db->createCommand("SELECT zone_code,name from all_hospital_thai where hcode='{$sitecode}'")->queryOne();

        $hospital=\Yii::$app->db->createCommand("select * from all_hospital_thai where hcode='{$sitecode}'")->queryOne();
        $provincecode=$hospital['provincecode'];
        if ($provincecode != "") $config = \Yii::$app->db->createCommand("select * from db_config_province where province='{$provincecode}'")->queryOne();

        
        if (count($constants)>0) foreach ($constants as $key => $constant) {
            if ($constant->id == "_SITECODE_") $constant->value=$sitecode;
            if ($constant->id == "_KHET_") {
                $constant->value=$khet['zone_code'];
            }
            if ($constant->id == "_REMOTE_DB_") $constant->value=$config[db];
            if ($constant->id == "_HOSPITAL_") $constant->value=$khet['name'];
            if ($constant->id == "_USERFULLNAME_") $constant->value=$profile->firstname . " " . $profile->lastname;
            if ($constant->id == "_SERVICE_URL_") $constant->value="https://webservice{$khet['zone_code']}.thaicarecloud.org";
        }
        return $constants;
    }        
    public function selectdb() 
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
        
        \Yii::$app->dbtdc->dsn = "mysql:host={$host};dbname={$db}";
        \Yii::$app->dbtdc->username=$mysqluser;
        \Yii::$app->dbtdc->password=$mysqlpasswd;
        return true;
    }    
    public function actionQuery()
    {
        $this->selectdb();
        $sql = \Yii::$app->request->get('sql');
        $model=\Yii::$app->dbtdc->createCommand($sql)->queryAll();
        return $model;
    }
    public function actionHisType() 
    {
        $sql = "SELECT * from buffe_his where status=1";
        $model=\Yii::$app->dbtdc->createCommand($sql)->queryAll();
        return $model;        
    }
    public function actionNewHisType() 
    {
        $sql = "SELECT * from buffe_his where status=3";
        $model=\Yii::$app->dbtdc->createCommand($sql)->queryAll();
        return $model;        
    }        
    public function actionSearchPatient() {
        $token = \Yii::$app->request->get('token');
        $user=\app\models\User::findIdentityByAccessToken($token);
        //\yii\helpers\VarDumper::dump($user);
        $profile=\app\models\UserProfile::findOne($user->id);
        $sitecode=  $profile->sitecode;
        $text = \Yii::$app->request->get('text');
        $secret = \Yii::$app->request->get('secret');
        
        $this->selectdb();
        $sql = "SELECT * from f_person where sitecode='{$sitecode}' and (decode(unhex(cid),sha2('{$secret}',256)) like '%{$text}%' or decode(unhex(name),sha2('{$secret}',256)) like '%{$text}%' or decode(unhex(lname),sha2('{$secret}',256)) like '%{$text}%' or convert(decode(unhex(name),sha2('{$secret}',256)) using tis620) like '%{$text}%' or convert(decode(unhex(lname),sha2('{$secret}',256)) using tis620) like '%{$text}%') limit 1000";
        $model=\Yii::$app->dbtdc->createCommand($sql)->queryAll();
        return $model;         
        
    }
    public function actionGetData ()
    {
        header("Access-Control-Allow-Origin: *",false);
        $token = \Yii::$app->request->get('token');
        $user=\app\models\User::findIdentityByAccessToken($token);
        //\yii\helpers\VarDumper::dump($user);
        $profile=\app\models\UserProfile::findOne($user->id);
        $sitecode=  $profile->sitecode;
        $ptlink = \Yii::$app->request->get('ptlink');
        $tbname = \Yii::$app->request->get('tbname');
        //check there own patient before get data
        
        $this->selectdb();
        $sql = "SELECT * from f_person where sitecode='{$sitecode}' and ptlink='{$ptlink}'";
        $patient=\Yii::$app->dbtdc->createCommand($sql)->queryAll();
        if (count($patient)>0) {
            $sql = "SELECT * from {$tbname} where ptlink='{$ptlink}'";
            $model=\Yii::$app->dbtdc->createCommand($sql)->queryAll();
        }
        
        return $model;         
        
    }
    public function actionTableExample($tbname) 
    {
        $token = \Yii::$app->request->get('token');
        $user=\app\models\User::findIdentityByAccessToken($token);
        //\yii\helpers\VarDumper::dump($user);
        $profile=\app\models\UserProfile::findOne($user->id);
        $sitecode=  $profile->sitecode;
        
        $this->selectdb();
        $sql = "SELECT * from `{$tbname}` where sitecode='{$sitecode}' limit 50";
        $model=\Yii::$app->dbtdc->createCommand($sql)->queryAll();
        return $model;         
    }
    public function actionTableList() 
    {
        $this->selectdb();
        $sql = "SHOW TABLES";
        $model=\Yii::$app->dbtdc->createCommand($sql)->queryAll();
        return $model;         
    }  
    public function actionTableStructure($tbname) 
    {
        $this->selectdb();
        $sql = "SHOW CREATE TABLE `{$tbname}`";
        $model=\Yii::$app->dbtdc->createCommand($sql)->queryAll();
        return $model;                 
    }
    public function actionEzformList() 
    {
        $token = \Yii::$app->request->get('token');
        $user=\app\models\User::findIdentityByAccessToken($token);
        $userid = $user->id;
        
        $sql = "select ezf_id,ezf_name from ezform where user_create={$userid} or ezf_id in (select distinct ezf_id from ezform_target where user_create={$userid})";
        $model=\Yii::$app->db->createCommand($sql)->queryAll();
        return $model;                         
    }
    public function actionEzformTable()
    {
        $token = \Yii::$app->request->get('token');
        $user=\app\models\User::findIdentityByAccessToken($token);
        $userid = $user->id;
        
        $ezf_id = \Yii::$app->request->get('ezf_id');
        $ezform = \app\models\Ezform::findOne(['ezf_id'=>$ezf_id]);

        $table = $ezform->ezf_table;
        if ($table == "") return "";
        $sql = "SHOW CREATE TABLE {$table}";
        $model=\Yii::$app->db->createCommand($sql)->queryAll();
        return $model;                         
        
    }
    public function actionEzformListData()
    {
        $token = \Yii::$app->request->get('token');
        $user=\app\models\User::findIdentityByAccessToken($token);
        $userid = $user->id;
        
        $profile=\app\models\UserProfile::findOne($userid);
        $sitecode=  $profile->sitecode;
        
        $ezf_id = \Yii::$app->request->get('ezf_id');
        $ezform = \app\models\Ezform::findOne(['ezf_id'=>$ezf_id]);

        $table = $ezform->ezf_table;
        if ($table == "") return "";
        $sql = "SELECT id,xsourcex,create_date,update_date from {$table} WHERE rstat NOT IN ('0','3') AND xsourcex='{$sitecode}' or user_create={$userid} or user_update={$userid}";
        $model=\Yii::$app->db->createCommand($sql)->queryAll();
        return $model;                         
        
    }   
    public function actionEzformGetDataById()
    {
        header("Access-Control-Allow-Origin: *",false);
        $token = \Yii::$app->request->get('token');
        $user=\app\models\User::findIdentityByAccessToken($token);
        $userid = $user->id;
        
        $profile=\app\models\UserProfile::findOne($userid);
        $sitecode=  $profile->sitecode;
        
        $ezf_id = \Yii::$app->request->get('ezf_id');
        $ezform = \app\models\Ezform::findOne(['ezf_id'=>$ezf_id]);

        $table = $ezform->ezf_table;
        
        $dataid = \Yii::$app->request->get('id');
        
        if ($table == "") return "";
        if ($dataid=="") return "";
        $sql = "SELECT * from {$table} WHERE id='$dataid'";
        $model=\Yii::$app->db->createCommand($sql)->queryAll();
        if (!$model)            return "";
        
        return $model;                         
        
    }       
    public function actionEzformGetAllData()
    {
        header("Access-Control-Allow-Origin: *",false);
        $token = \Yii::$app->request->get('token');
        $user=\app\models\User::findIdentityByAccessToken($token);
        $userid = $user->id;
        
        $profile=\app\models\UserProfile::findOne($userid);
        $sitecode=  $profile->sitecode;
        
        $ezf_id = \Yii::$app->request->get('ezf_id');
        $ezform = \app\models\Ezform::findOne(['ezf_id'=>$ezf_id]);

        $table = $ezform->ezf_table;
        
        
        if ($table == "") return "";

        $sql = "SELECT * from {$table} WHERE rstat NOT IN ('0','3') AND xsourcex='{$sitecode}' or user_create={$userid} or user_update={$userid}";
        $model=\Yii::$app->db->createCommand($sql)->queryAll();
        if (!$model)            return "";
        
        return $model;                         
        
    }      
    public function actionEzformTdcList() 
    {
        $token = \Yii::$app->request->get('token');
        $user=\app\models\User::findIdentityByAccessToken($token);
        $userid = $user->id;
        
        $sql = "select ef.ezf_id,ef.ezf_name,f43.f43 from ezform ef inner join ezform_map_f43 f43 on ef.ezf_id=f43.ezf_id";
        $model=\Yii::$app->db->createCommand($sql)->queryAll();
        return $model;                         
    }    
    
    public function  actionGetPtid(){

        $token = \Yii::$app->request->get('token');
        $user=\app\models\User::findIdentityByAccessToken($token);
        $userid = $user->id;
        
         $sql = "select tb_data_coc.ptid as ptid from tb_data_coc where tb_data_coc.cid=(select inv_user.id from inv_user where inv_user.user_id='{$userid}')";
        $model=\Yii::$app->db->createCommand($sql)->queryOne();

        return      $model  ;
    }
    
        public function  actionPersonData($ptable,$ptid){

        $token = \Yii::$app->request->get('token');
        $user=\app\models\User::findIdentityByAccessToken($token);
         if($user == "") return;
          $sql = "select * from {$ptable} where ptid = {$ptid}" ;
          $model=\Yii::$app->db->createCommand($sql)->queryOne();
        return      $model  ;
    }
    
    
          public function  actionCkdData(){

            $token = \Yii::$app->request->get('token');
        $user=\app\models\User::findIdentityByAccessToken($token);
        $userid = $user->id;
        
         $sql = "select tb_data_coc.ptid as ptid from tb_data_coc where tb_data_coc.cid=(select inv_user.id from inv_user where inv_user.user_id='{$userid}')";
        $model=\Yii::$app->db->createCommand($sql)->queryOne();
        $ptid = $model['ptid'];

          $sql2 = "select * from tbdata_1484589286074665600 where ptid = {$ptid}" ;
           $ckddata=\Yii::$app->db->createCommand($sql2)->queryOne();
          
        return      $ckddata  ;
    }
    public function actionSecretkey()
    {
        $key = \Yii::$app->request->get('key');
        $sql = "select sha2('$key',256) as secretkey";
        $model=\Yii::$app->dbtdc->createCommand($sql)->queryAll();
        return $model;
    }     

}
