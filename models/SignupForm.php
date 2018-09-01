<?php

namespace smart\rbac\models;

use Yii;
use smart\rbac\exception\UserException;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $role_ids;
    public $status;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'smart\rbac\models\User','on'=>['create']],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => 'smart\rbac\models\User', 'on'=>['create']],

            //['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['role_ids', 'required'],
            //['role_ids', 'string'],

            ['status', 'required'],
            ['status', 'in', 'range' => [User::STATUS_ACTIVE, User::STATUS_DELETED]],
        ];
    }

    /**
     * @return User|\yii\db\ActiveQuery
     * @throws \Exception
     */
    public function save()
    {
        $tr = Yii::$app->db->beginTransaction();

        try {

            if (!$this->validate()) {
                $Exception = new UserException('数据验证失败');
                $Exception->setErrors($this->getErrors());
                throw $Exception;
            }

            if ($this->id) {
                $user = User::findOne($this->id);
            } else {
                $user = new User();
                $user->created_at = time();
                $user->updated_at = time();
            }

            $user->username = $this->username;
            $user->email = $this->email;
            $user->status = $this->status;

            if(!empty($this->password)){
                $user->password_hash = $this->setPassword($this->password);
                $user->auth_key = $this->generateAuthKey();
            }
            if (!$user->save()) {
                $Exception = new UserException('保存用户信息失败');
                $Exception->setErrors($user->getErrors());
                throw $Exception;
            }

            $newList = [];
            foreach ($this->role_ids as $roleId) {
                $UserRoleRelation = UserRoleRelation::find()->where([
                    'role_id' => $roleId,
                    'user_id' => $this->id])
                    ->one();

                if ($UserRoleRelation) {
                    $newList[$UserRoleRelation->relation_id] = $UserRoleRelation;
                } else {
                    $UserRoleRelation = new UserRoleRelation;
                    $UserRoleRelation->role_id = $roleId;
                    $UserRoleRelation->user_id = $user->id;
                    $UserRoleRelation->update_at = time();
                    $UserRoleRelation->create_at = time();
                    if (!$UserRoleRelation->save()) {
                        $Exception = new UserException('保存用户角色关系失败');
                        $Exception->setErrors($UserRoleRelation->getErrors());
                        throw $Exception;
                    }
                }
            }

            $dbList = UserRoleRelation::find()->indexBy('relation_id')->all();
            $delList = array_diff_key($newList, $dbList);//差集  被删除的action
            foreach ($delList as $model) {
                $model->delete();
            }

            $tr->commit();
            return $user;

        } catch (\Exception $e) {
            $tr->rollBack();
            throw $e;
        }
    }

    public function setPassword($password)
    {
        return \Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        return \Yii::$app->security->generateRandomString();
    }

}
