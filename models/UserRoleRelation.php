<?php

namespace smart\rbac\models;

use Yii;

/**
 * This is the model class for table "smart_user_role_relation".
 *
 * @property int $relation_id 关系id
 * @property int $user_id 用户id
 * @property int $role_id 角色id
 * @property int $create_at 创建时间
 * @property int $update_at 更新时间
 */
class UserRoleRelation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smart_user_role_relation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'role_id', 'create_at', 'update_at'], 'required'],
            [['user_id', 'role_id', 'create_at', 'update_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'relation_id' => Yii::t('app', '关系id'),
            'user_id' => Yii::t('app', '用户id'),
            'role_id' => Yii::t('app', '角色id'),
            'create_at' => Yii::t('app', '创建时间'),
            'update_at' => Yii::t('app', '更新时间'),
        ];
    }
}
