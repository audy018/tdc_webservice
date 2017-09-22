<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "buffe_tables".
 *
 * @property string $sitecode
 * @property string $table
 * @property string $data
 * @property string $his_rows
 * @property string $server_rows
 * @property string $dadd
 * @property string $dupdate
 */
class BuffeTables extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'buffe_tables';
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
            [['sitecode', 'table'], 'required'],
            [['data'], 'string'],
            [['his_rows', 'server_rows'], 'integer'],
            [['dadd', 'dupdate'], 'safe'],
            [['sitecode'], 'string', 'max' => 10],
            [['table'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sitecode' => 'Sitecode',
            'table' => 'Table',
            'data' => 'Data',
            'his_rows' => 'His Rows',
            'server_rows' => 'Server Rows',
            'dadd' => 'Dadd',
            'dupdate' => 'Dupdate',
        ];
    }
}
