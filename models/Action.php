<?php

namespace smart\rbac\models;

use Yii;

/**
 * This is the model class for table "smart_action".
 *
 * @property int $action_id 动作id
 * @property string $action_title 动作标题
 * @property string $ctrl_title 控制器标题
 * @property string $module_title 模块标题
 * @property string $route 路由
 * @property int $create_at 创建时间
 * @property int $update_at 更新时间
 */
class Action extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'smart_action';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action_title', 'ctrl_title', 'module_title', 'route', 'create_at', 'update_at'], 'required'],
            [['create_at', 'update_at'], 'integer'],
            [['action_title', 'ctrl_title', 'module_title', 'route'], 'string', 'max' => 50],
            [['route'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'action_id' => Yii::t('app', '动作id'),
            'action_title' => Yii::t('app', '动作标题'),
            'ctrl_title' => Yii::t('app', '控制器标题'),
            'module_title' => Yii::t('app', '模块标题'),
            'route' => Yii::t('app', '路由'),
            'create_at' => Yii::t('app', '创建时间'),
            'update_at' => Yii::t('app', '更新时间'),
        ];
    }

    /**
     * 将controller 名称转化成路由形式
     * @param $controllerName
     * @return string
     */
    public static function convertToControllerId($controllerName)
    {

        $str = preg_replace("/(?=[A-Z])/", '-', $controllerName);
        $str = trim($str, '-');
        $str = strtolower($str);
        $arr = explode('-', $str);
        $last = count($arr) - 1;
        if ($arr[$last] === 'controller') {
            unset($arr[$last]);
        }

        return implode('-', $arr);
    }

    /**
     * 将action的名称转化成路由形式
     * @param $actionId
     * @return string
     */
    public static function convertToActionId($actionId)
    {
        /*
        preg_match_all("/([a-zA-Z]{1}[a-z]*)?[^A-Z]/",$str,$array);
        */
        $str = preg_replace("/(?=[A-Z])/", '-', $actionId);
        $str = strtolower($str);
        $arr = explode('-', $str);
        if ($arr[0] === 'action') {
            array_shift($arr);
        }

        return implode('-', $arr);
    }

    /**
     * 生成路由格式
     * @param null $moduleId
     * @param null $controllerId
     * @param null $actionId
     * @return string
     */
    public static function generateRoute($moduleId = null, $controllerId = null, $actionId = null)
    {
        $params = [
            $moduleId,
            $controllerId,
            $actionId
        ];

        return implode('/', $params);
    }


    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['action_id' => 'action_id']);
    }
}
