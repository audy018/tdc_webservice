<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "buffe_his".
 *
 * @property integer $id
 * @property string $his_type
 * @property string $his_name
 * @property integer $status
 */
class BuffeHis extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'buffe_his';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['his_type', 'his_name'], 'required'],
            [['status'], 'integer'],
            [['his_type'], 'string', 'max' => 50],
            [['his_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'his_type' => 'His Type',
            'his_name' => 'His Name',
            'status' => 'Status',
        ];
    }
}
