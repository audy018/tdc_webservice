<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;
/**
 * This is the model class for table "buffe_config".
 *
 * @property string $sitecode
 * @property string $client_version
 * @property integer $config_delay
 * @property integer $command_delay
 * @property integer $sync_delay
 */
class BuffeConfig extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'buffe_config';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbtdc');
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['config_delay', 'command_delay', 'sync_delay','sync_nrec'], 'integer'],
            [['id'], 'string', 'max' => 10],
            [['buffe_version','his_type','his_version'], 'string', 'max' => 20]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Sitecode',
            'his_type' => 'Client Type',
            'his_version' => 'Client Version',
            'config_delay' => 'Config Delay',
            'command_delay' => 'Template Delay',
            'sync_delay' => 'Sync Delay',
        ];
    }  
}
