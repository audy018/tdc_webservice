<?php

namespace app\controllers;

use yii\rest\ActiveController;
use app\models\User;
use app\models\UserProfile;
use app\models\BuffeCommand;
use app\models\BuffeConfig;
class BuffeCommandController extends ActiveController
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
            'syncid' => ['POST']
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
        $config =new BuffeConfig;

        $histype=$config->findOne(['id'=>$sitecode])->his_type;
        
        $chkcommand = BuffeCommand::find()->where('sitecode=:sitecode and (status is null)',[':sitecode'=> $sitecode])->addOrderBy(['id'=>'ACS'])->all();

        if (\count($chkcommand)==0) 
        {   
            $query = \Yii::$app->dbtdc->createCommand("REPLACE INTO buffe_command (`id`,`sitecode`,`template_id`,`priority`,`ctype`,`dadd`,`cname`,`presql`,`sql`,`controller`,`table`,`status`) (select '','{$sitecode}',t.`id`,t.`priority`,t.`ctype`,NOW(),t.`cname`,t.`presql`,t.`sql`,t.`controller`,t.`table`,null as `status` from buffe_template as t left join (select * from buffe_command where sitecode='{$sitecode}' and template_id is not null) as c on t.id=c.template_id where client_type='{$histype}' and c.template_id is null and t.status=0 order by t.id limit 1);")->query();       
        }
        return $command = BuffeCommand::find()->where('sitecode=:sitecode and (status is null)',[':sitecode'=> $sitecode])->one();
        
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
                $command = BuffeCommand::findOne($id);
                if ($command) 
                {
                    if ($command->ctype=="SQL") {
                        $command->status=4;
                    }else{
                        $command->status=0;
                    }
                    $command->save();
                    return $result['result']="OK";
                }else{
                    $result['result']="Error";
                    $result['Error']="Enable error: ID is not specify.";

                    return $result;                      
                }
            }
            catch (\yii\db\Exception $e)
            {
                $result['result']="Error";
                $result['Error']=$e->getMessage();

                return $result;                  
            }

        //$result['result']="Error";
        //$result['Error']=$command->getErrors();

        //return $result;        
    }
    
    public function actionSync() {
        $id = \Yii::$app->request->post('id'); 

        $command = BuffeCommand::findOne($id);
        if ($command)
        {
            $command->clienterr=\Yii::$app->request->post('clienterr'); 
            $command->result=\Yii::$app->request->post('result'); 
            $command->status=\Yii::$app->request->post('status'); 
            $command->dupdate=\Yii::$app->request->post('dupdate'); 
            $command->save();
        }

        if ($command->controller != "")
        {
            
            switch ($command->controller)
            {   
                case 'listtables':
                    $this->listtables($command->sitecode,$command->result);
                    break;
                case 'tablestructure':
                    $this->tablestructure($command->sitecode,$command->result);
                    break;
                case 'tablerecordcount':

                    $this->tablerecordcount($command->sitecode,$command->table,$command->result);
                    break;
                case 'listtriggers':
                    $this->listtriggers($command->sitecode,$command->result);
                    break;
                case 'testview':
                    $this->updateviewresult($command->table,$command->result,$command->clienterr);
                    break;
                default:
                    break;
            }
                
        }
        //update status
        $command->dupdate=date('Y-d-m h:i:s');
        $command->status='4';
        
        if ($command->save())
        {
            $result['result']="OK";
        }
        else
        {
            $result['result']="Error";
            $result['Error']=$command->getErrors();
        }
        return $result;
    }
    public function actionSyncId() {
        $id = \Yii::$app->request->post('id'); 

        $command = BuffeCommand::findOne($id);
        
        if ($command->controller != "")
        {
            
            switch ($command->controller)
            {   
                case 'listtables':
                    $this->listtables($command->sitecode,$command->result);
                    break;
                case 'tablestructure':
                    $this->tablestructure($command->sitecode,$command->result);
                    break;
                case 'tablerecordcount':

                    $this->tablerecordcount($command->sitecode,$command->table,$command->result);
                    break;
                case 'listtriggers':
                    $this->listtriggers($command->sitecode,$command->result);
                    break;
                case 'testview':
                    $this->updateviewresult($command->table,$command->result,$command->clienterr);
                    break;                
                default:
                    break;
            }
                
        }
        //update status
        $command->dupdate=date('Y-d-m h:i:s');
        $command->status='4';
        
        if ($command->save())
        {
            $result['result']="OK";
        }
        else
        {
            $result['result']="Error";
            $result['Error']=$command->getErrors();
        }
        return $result;
    }    
    public function updateviewresult ($id,$result,$clienterr) {
        $clienterr=  str_replace('\'', '\\\'', $clienterr);
        $result=  str_replace('\'', '\\\'', $result);
        $update = \Yii::$app->dbtdc->createCommand("UPDATE buffe_data_table_view_run set client_err='{$clienterr}',result='{$result}',update_at=NOW() WHERE id='{$id}' ")->query();
        $resultcode['result']="OK";
        return $resultcode;
    }

    public function listtables ($sitecode,$result) 
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit','256M');
        $results = json_decode($result,true);
        \Yii::$app->dbtdc->createCommand("DELETE FROM buffe_tables where sitecode='{$sitecode}'")->query();
        $count=\count($results);
        if ($count>0) foreach ($results as $key => $table) {
            foreach ($table as $key2 => $tablename) {
                /*
                $ctable=  \app\models\BuffeTables::findOne(['sitecode'=>$sitecode,'table'=>$tablename]);
                if (!$ctable) 
                {
                    $ctable = new \app\models\BuffeTables();
                    $ctable->sitecode=$sitecode;
                    $ctable->table=$tablename;
                    $ctable->dadd=date('Y-d-m h:i:s');
                    $ctable->save(false);
                }
                $command=new BuffeCommand();
                $command->sitecode=$sitecode;
                $command->status=null;
                $command->ctype="COMMAND";
                $command->cname="Table Structure";
                $command->sql="SHOW CREATE TABLE `{$tablename}`;";
                $command->controller="tablestructure";
                $command->dadd=date('Y-d-m h:i:s');
                $command->save(false);
                //\yii\helpers\VarDumper::dump($command);exit;
                */
                \Yii::$app->dbtdc->createCommand("REPLACE DELAYED INTO `buffe_tables` (`sitecode`,`table`,`dadd`) VALUES('$sitecode','{$tablename}',NOW())")->query();
                //\Yii::$app->dbtdc->createCommand("REPLACE DELAYED INTO `buffe_command` (`id`,`sitecode`,`priority`,`ctype`,`cname`,`sql`,`dadd`,`status`,`controller`) VALUES('','$sitecode','100','COMMAND','Table Structure','SHOW CREATE TABLE `{$tablename}`',NOW(),null,'tablestructure')")->query();
            }
        }        
        
    }
    public function listtriggers ($sitecode,$result) 
    {
        $results = json_decode($result,true);
        \Yii::$app->dbtdc->createCommand("DELETE FROM buffe_triggers where sitecode='{$sitecode}'")->query();
        $count=\count($results);
        
        if ($count>0) foreach ($results as $key => $trigger) {
            $ctrigger = new \app\models\BuffeTriggers();
            $ctrigger->sitecode=$sitecode;            
            foreach ($trigger as $attr => $value) {
                    $ctrigger->Trigger=$trigger['Trigger'];
                    $ctrigger->Event=$trigger['Event'];
                    $ctrigger->Table=$trigger['Table'];
                    $ctrigger->Statement=$trigger['Statement'];
                    $ctrigger->Timing=$trigger['Timing'];
                    $ctrigger->Created=$trigger['Created'];
                    $ctrigger->sql_mode=$trigger['sql_mode'];
                    $ctrigger->Definer=$trigger['Definer'];
                    $ctrigger->character_set_client=$trigger['character_set_client'];
                    $ctrigger->collation_connection=$trigger['collation_connection'];
                    $ctrigger->Database_Collation=$trigger['Database Collation'];
            }     
            $ctrigger->save();
        }      
        
    }    
    public function tablestructure($sitecode,$result) {
        $results = json_decode($result,true);

        if (\count($results)>0) foreach ($results as $key => $row) {
            $table=$row['Table'];
            $data=$row['Create Table'];

            $ctable=  \app\models\BuffeTables::findOne(['sitecode'=>$sitecode,'table'=>$table]);
            if ($ctable)
            {
                $ctable->data=$data;
                $ctable->dupdate=date('Y-d-m h:i:s');
                $ctable->save();
            }
            $command=new BuffeCommand();
            $command->sitecode=$sitecode;
            $command->status=null;
            $command->ctype="COMMAND";
            $command->cname="Count records";
            $command->sql="SELECT count(*) as RecordCout FROM `{$table}`;";
            $command->table=$table;
            $command->dadd=date('Y-d-m h:i:s');
            $command->controller="tablerecordcount";
            $command->dadd=date('Y-d-m h:i:s');
            $command->save();    

            $resultcode['result']="OK";
        } 
        else {
            $resultcode['result']="NOT OK";
        }
        return $resultcode;
    }
    public function tablerecordcount($sitecode,$table, $result) {
        $results = json_decode($result,true);

        if (\count($results)>0) foreach ($results as $key => $row) {
            $nrecs=$row['nrecs'];

            $ctable=  \app\models\BuffeTables::findOne(['sitecode'=>$sitecode,'table'=>$table]);
            if ($ctable)
            {
                $ctable->his_rows=$nrecs;
                $ctable->dupdate=date('Y-d-m h:i:s');
                $ctable->save();
            }

            $resultcode['result']="OK";
        } 
        else {
            $resultcode['result']="NOT OK";
        }
        return $resultcode;    
    }
    public function actionGetQuery(){
        try{
         $token = \Yii::$app->request->get('token')==''?'':\Yii::$app->request->get('token');
         $user = User::findIdentityByAccessToken($token);
         $sitecode =  UserProfile::findOne($user->id)->sitecode;
         $dataQuery = \Yii::$app->dbtdc->createCommand("SELECT * FROM buffe_query WHERE `sitecode` = :sitecode AND (`status` is null)",[':sitecode'=>$sitecode])->queryOne();
         return $dataQuery;
        }catch(ErrorException $ex){
            return false;
        }

    }
    public function actionEnableQuery(){
        try{
         $token = \Yii::$app->request->get('token')==''?'':\Yii::$app->request->get('token');
         $id = \Yii::$app->request->get('token')==''?'':\Yii::$app->request->post('id');
         $result = \Yii::$app->request->get('token')==''?'':\Yii::$app->request->post('result');
         $user = User::findIdentityByAccessToken($token);
         $sitecode =  UserProfile::findOne($user->id)->sitecode;
         \Yii::$app->dbtdc->createCommand("UPDATE `buffe_query` SET `result` = :result,`status` = 4 WHERE `id` = :id AND `sitecode` = :sitecode",[':result'=>$result,':id'=>$id,':sitecode'=>$sitecode])->query();
         return "OK";
        }catch(ErrorException $ex){
            return "error";
        }

    }
}
