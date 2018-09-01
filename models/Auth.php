<?php

namespace smart\rbac\models;

use Yii;

/**
 * This is the model class for table "smart_auth".
 *
 * @property int $auth_id 权限id
 * @property string $auth_name 权限名称
 * @property string $auth_desc 描述
 * @property string $action_ids 多个actionId用逗号分隔
 * @property int $create_at 创建时间
 * @property int $update_at 更新时间
 */
class Auth extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smart_auth';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_name', 'create_at', 'update_at'], 'required'],
            [['action_ids'], 'string'],
            [['create_at', 'update_at'], 'integer'],
            [['auth_name'], 'string', 'max' => 50],
            [['auth_desc'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'auth_id' => Yii::t('app', '权限id'),
            'auth_name' => Yii::t('app', '权限名称'),
            'auth_desc' => Yii::t('app', '描述'),
            'action_ids' => Yii::t('app', '多个actionId用逗号分隔'),
            'create_at' => Yii::t('app', '创建时间'),
            'update_at' => Yii::t('app', '更新时间'),
        ];
    }

    private $_action_id_array;


    /**
     * 获取角色的action id数组
     * @return array
     */
    public function getAction_id_array()
    {
        if ($this->_action_id_array === null) {
            $actionId = ($a = @explode(',', $this->action_ids)) ? $a : [];
            $this->_action_id_array = $actionId;
        }
        return $this->_action_id_array;

    }

    /**
     * 获取以auth_id为key，auth_name为值的数组
     * @param array $condition
     * @return array
     */
    public static function getAuthNameArray($condition = [])
    {
        return static::find()
            ->select('auth_name')
            ->where($condition)
            ->indexBy('auth_id')
            ->column();
    }
}
