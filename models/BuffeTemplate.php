<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "buffe_template".
 *
 * @property string $id
 * @property integer $priority
 * @property string $ctype
 * @property string $client_type
 * @property string $version_only
 * @property string $version_exclude
 * @property string $cname
 * @property string $presql
 * @property string $sql
 * @property string $controller
 * @property string $table
 * @property integer $status
 */
class BuffeTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'buffe_template';
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
            [['priority', 'status'], 'integer'],
            [['ctype', 'version_only', 'version_exclude', 'cname', 'presql', 'sql'], 'required'],
            [['version_only', 'version_exclude', 'presql', 'sql'], 'string'],
            [['ctype'], 'string', 'max' => 10],
            [['client_type', 'controller'], 'string', 'max' => 20],
            [['cname'], 'string', 'max' => 30],
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
            'priority' => 'Priority',
            'ctype' => 'Ctype',
            'client_type' => 'Client Type',
            'version_only' => 'Version Only',
            'version_exclude' => 'Version Exclude',
            'cname' => 'Cname',
            'presql' => 'Presql',
            'sql' => 'Sql',
            'controller' => 'Controller',
            'table' => 'Table',
            'status' => 'Status',
        ];
    }
}
