<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;

use yii\helpers\VarDumper;
use yii\rest\ActiveController;

use yii\filters\auth\HttpBasicAuth;

use app\models\User;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';
    
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
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => [$this, 'auth']
        ];
        return $behaviors;
    }    

    public function auth($username, $password)
    {
        $user = User::findByUsername($username);
        if($user != null){
            return $user->validatePassword($password) ? $user : null;
        }else{
            $res = \Yii::$app->dbcascap->createCommand("SELECT * FROM `user` WHERE username = '".$username."';")->queryOne();
            //VarDumper::dump($res, 10, true); exit;
            if($res) {
                if (\Yii::$app->getSecurity()->validatePassword($password, $res['password_hash'])) {
                    $res_profile = \Yii::$app->dbcascap->createCommand("SELECT * FROM `user_profile` WHERE user_id = '" . $res['id'] . "';")->queryOne();
                    $res_role = \Yii::$app->dbcascap->createCommand("SELECT * FROM `rbac_auth_assignment` WHERE user_id = '" . $res['id'] . "';")->queryAll();

                    $user = new User();
                    $user->username = $username;
                    $user->email = $res['email'];
                    $user->setPassword($password);
                    $user->save();
                    //save profile
                    $res_profile['user_id'] = $user->id;
                    \Yii::$app->db->createCommand()->insert('user_profile', $res_profile)->execute();
                    //VarDumper::dump($res_role, 10, true); exit;
                    foreach ($res_role as $key => $val) {
                        $res_role[$key]['user_id'] = $user->id;
                        $res_role[$key]['created_at'] = time();
                    }
                    \Yii::$app->db->createCommand()->batchInsert('rbac_auth_assignment', ['item_name', 'user_id', 'created_at'], $res_role)->execute();
                    //User::afterSignup($res_profile, $res_role);
                    return $tdcuser;
                }
            }

            return null;

        }
//         \Yii::$app->user->enableSession = false;
//         $model = new \app\models\LoginForm();
//            $model->username = $username;
//            $model->password = $password;
//            $model->rememberMe = FALSE;
//
//            if($model->login()){
//                return \Yii::$app->user->identity;
//            }
//            return NULL;
        
    }
    
    public function actions()
    {
        $actions = parent::actions();

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }
    
    public function actionView($id)
    {
        return User::findOne($id);
    }
    public function getIdentity()
    {

        return \Yii::$app->user->identity;
    }
    public function prepareDataProvider()
    {   
        $identity = \Yii::$app->user->identity;
        $profile = \app\models\UserProfile::findOne(['user_id'=>$identity->id]);

        
        $user->id = $profile->user_id;
        $user->username = $identity->username;
        $user->access_token = $identity->auth_key;
        $user->name = $profile->firstname . " " . $profile->lastname;
        $user->sitecode = $profile->sitecode;

        $zone = \Yii::$app->db->createCommand("SELECT name,zone_code from all_hospital_thai where hcode='{$profile->sitecode}' limit 1")->queryOne();
        if ($zone['zone_code']=="") $zone['zone_code']="xx";
        $user->service_url = "https://webservice".$zone['zone_code'].".thaicarecloud.org";        
        $user->hospital = $zone['name'];

        //return \Yii::$app->user->identity;
        return $user;
    }    
    
    public function checkAccess($action, $model = null, $params = [])
    {
        // check if the user can access $action and $model
        // throw ForbiddenHttpException if access should be denied

    }
}