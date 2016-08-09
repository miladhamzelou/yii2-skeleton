<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\bootstrap;


use yii\base\Component;

/**
 * Class Setting
 * @package common\components
 */
class Setting extends Component
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        \Yii::$app->language='vi';
        /* Header */
        //$headers = Yii::$app->response->headers;

        // HSTS
        //$headers->add('strict-transport-security', 'max-age=600');
        //\Yii::$app->

        $module = \Yii::$app->getModule('debug');
        //$module->allowedIPs=['127.0.0.1', '::1'];
        $module->allowedIPs=[''];

        //\Yii::$app->view->theme->skin='skin-green';
        //$a=Account::find()->one();
//        \Yii::$container->set('modernkernel\themeadminlte\AdminlteTheme', [
//            'skin' => 'skin-green'.$a->id,
//        ]);

//        \Yii::$container->set('yii\web\JqueryAsset', [
//            'sourcePath'=>null,
//            'js' => ['https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js']
//        ]);
    }

}