<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "buffe_transfer".
 *
 * @property string $id
 * @property string $sitecode
 * @property string $dadd
 * @property string $presql
 * @property string $sql
 * @property integer $status
 * @property string $clienterr
 * @property string $serverr
 * @property string $result
 * @property string $table
 * @property string $dupdate
 */
class BuffeTransfer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'buffe_transfer';
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
            [['id', 'sitecode', 'status'], 'required'],
            [['id'], 'integer'],
            [['dadd', 'dupdate'], 'safe'],
            [['presql', 'sql', 'clienterr', 'serverr', 'result'], 'string'],
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
            'id' => 'ID',
            'sitecode' => 'Sitecode',
            'dadd' => 'Dadd',
            'presql' => 'Presql',
            'sql' => 'Sql',
            'status' => 'Status',
            'clienterr' => 'Clienterr',
            'serverr' => 'Serverr',
            'result' => 'Result',
            'table' => 'Table',
            'qleft' => 'Queu left',
            'dupdate' => 'Dupdate',
        ];
    }
}
