<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "buffe_triggers".
 *
 * @property string $sitecode
 * @property string $Trigger
 * @property string $Event
 * @property string $Table
 * @property string $Statement
 * @property string $Timing
 * @property string $Created
 * @property string $sql_mode
 * @property string $Definer
 * @property string $character_set_client
 * @property string $collation_connection
 * @property string $Database Collation
 */
class BuffeTriggers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'buffe_triggers';
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
            [['sitecode', 'Trigger', 'Event', 'Table'], 'required'],
            [['Statement'], 'string'],
            [['sitecode'], 'string', 'max' => 10],
            [['Trigger'], 'string', 'max' => 50],
            [['Event', 'Timing', 'Created', 'sql_mode', 'Definer', 'character_set_client', 'collation_connection', 'Database_Collation'], 'string', 'max' => 80],
            [['Table'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sitecode' => 'Sitecode',
            'Trigger' => 'Trigger',
            'Event' => 'Event',
            'Table' => 'Table',
            'Statement' => 'Statement',
            'Timing' => 'Timing',
            'Created' => 'Created',
            'sql_mode' => 'Sql Mode',
            'Definer' => 'Definer',
            'character_set_client' => 'Character Set Client',
            'collation_connection' => 'Collation Connection',
            'Database Collation' => 'Database  Collation',
        ];
    }
}
