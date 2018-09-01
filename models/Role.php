<?php

namespace smart\rbac\models;

use Yii;

/**
 * This is the model class for table "smart_role".
 *
 * @property int $role_id 角色id
 * @property string $role_name 角色名称
 * @property string $role_desc 描述
 * @property string $auth_ids 多个auth_id用逗号分隔
 * @property int $create_at 创建时间
 * @property int $update_at 更新时间
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smart_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_name', 'create_at', 'update_at'], 'required'],
            [['auth_ids'], 'string'],
            [['create_at', 'update_at'], 'integer'],
            [['role_name'], 'string', 'max' => 50],
            [['role_desc'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'role_id' => Yii::t('app', '角色id'),
            'role_name' => Yii::t('app', '角色名称'),
            'role_desc' => Yii::t('app', '描述'),
            'auth_ids' => Yii::t('app', '权限'),//多个auth_id用逗号分隔
            'create_at' => Yii::t('app', '创建时间'),
            'update_at' => Yii::t('app', '更新时间'),
        ];
    }


    public function getAuthNames()
    {
        if(!empty($this->auth_ids)){
            return Auth::find()->select('auth_name')->where('auth_id in('.$this->auth_ids.')')->column();
        }
        return [];
    }

    /**
     * 获取以role_id为key，role_name为值的数组
     * @param array $condition
     * @return array
     */
    public static function getRoleNameArray($condition = [])
    {
        return static::find()
            ->select('role_name')
            ->where($condition)
            ->indexBy('role_id')
            ->column();
    }
}
