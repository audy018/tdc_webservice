<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tdc_command".
 *
 * @property string $id
 * @property string $sitecode
 * @property integer $template_id
 * @property integer $priority
 * @property string $ctype
 * @property string $cname
 * @property string $dadd
 * @property string $presql
 * @property string $sql
 * @property integer $status
 * @property string $clienterr
 * @property string $serverr
 * @property string $result
 * @property string $controller
 * @property string $table
 * @property string $dupdate
 */
class BuffeCommand extends \yii\db\ActiveRecord
{
   /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'buffe_command';
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
            [['sitecode'], 'required'],
            [['id', 'priority', 'status'], 'integer'],
            [['dadd', 'dupdate'], 'safe'],
            [['presql', 'sql', 'clienterr', 'serverr', 'result'], 'string'],
            [['ctype', 'sitecode'], 'string', 'max' => 10],
            [['cname'], 'string', 'max' => 30],
            [['controller'], 'string', 'max' => 20],
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
            'sitecode' => 'Siteid',
            'template_id' => 'Template ID',
            'priority' => 'Priority',
            'ctype' => 'Ctype',
            'cname' => 'Cname',
            'dadd' => 'Dadd',
            'presql' => 'Presql',
            'sql' => 'Sql',
            'status' => 'Status',
            'clienterr' => 'Clienterr',
            'serverr' => 'Serverr',
            'result' => 'Result',
            'controller' => 'Controller',
            'table' => 'Table',
            'dupdate' => 'Dupdate',
        ];
    }
}