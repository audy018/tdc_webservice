<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ezform".
 *
 * @property string $ezf_id
 * @property string $ezf_name
 * @property string $ezf_detail
 * @property string $ezf_table
 * @property string $user_create
 * @property string $create_date
 * @property string $user_update
 * @property string $update_date
 * @property integer $status
 * @property integer $shared
 * @property integer $public_listview
 * @property integer $public_edit
 * @property integer $public_delete
 * @property string $comp_id_target
 * @property string $co_dev
 * @property string $assign
 * @property integer $category_id
 * @property string $field_detail
 * @property string $sql
 * @property string $js
 * @property string $error
 * @property integer $query_tools
 *
 * @property EzformAssign[] $ezformAssigns
 * @property User[] $users
 * @property EzformCoDev[] $ezformCoDevs
 * @property EzformComponent[] $ezformComponents
 * @property EzformFields[] $ezformFields
 * @property EzformTarget[] $ezformTargets
 */
class Ezform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ezform';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ezf_id', 'ezf_detail', 'ezf_table', 'user_create', 'create_date', 'user_update', 'update_date', 'status', 'co_dev', 'assign', 'field_detail'], 'required'],
            [['ezf_id', 'user_create', 'user_update', 'status', 'shared', 'public_listview', 'public_edit', 'public_delete', 'comp_id_target', 'category_id', 'query_tools'], 'integer'],
            [['ezf_detail', 'co_dev', 'assign', 'sql', 'js', 'error'], 'string'],
            [['create_date', 'update_date'], 'safe'],
            [['ezf_name', 'ezf_table'], 'string', 'max' => 100],
            [['field_detail'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ezf_id' => 'Ezf ID',
            'ezf_name' => 'Ezf Name',
            'ezf_detail' => 'Ezf Detail',
            'ezf_table' => 'Ezf Table',
            'user_create' => 'User Create',
            'create_date' => 'Create Date',
            'user_update' => 'User Update',
            'update_date' => 'Update Date',
            'status' => 'Status',
            'shared' => 'Shared',
            'public_listview' => 'Public Listview',
            'public_edit' => 'Public Edit',
            'public_delete' => 'Public Delete',
            'comp_id_target' => 'Comp Id Target',
            'co_dev' => 'Co Dev',
            'assign' => 'Assign',
            'category_id' => 'Category ID',
            'field_detail' => 'Field Detail',
            'sql' => 'Sql',
            'js' => 'Js',
            'error' => 'Error',
            'query_tools' => 'Query Tools',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEzformAssigns()
    {
        return $this->hasMany(EzformAssign::className(), ['ezf_id' => 'ezf_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('ezform_assign', ['ezf_id' => 'ezf_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEzformCoDevs()
    {
        return $this->hasMany(EzformCoDev::className(), ['ezf_id' => 'ezf_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEzformComponents()
    {
        return $this->hasMany(EzformComponent::className(), ['ezf_id' => 'ezf_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEzformFields()
    {
        return $this->hasMany(EzformFields::className(), ['ezf_id' => 'ezf_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEzformTargets()
    {
        return $this->hasMany(EzformTarget::className(), ['ezf_id' => 'ezf_id']);
    }
}
