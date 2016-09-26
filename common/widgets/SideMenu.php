<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\widgets;

use common\Core;
use Yii;
use \yii\bootstrap\Widget;

/**
 * Class SideMenu
 * @package common\widgets
 */
class SideMenu extends Widget
{

    public $items = [];
    public $homeTitle='';
    public $homeUrl='';


    /**
     * @inheritdoc
     * @return string|void
     */
    public function run()
    {

        if(Yii::$app->id == 'app-backend'){
            $this->items=$this->adminItems();
        }
        if(Yii::$app->id == 'app-frontend'){
            $this->items=$this->accountItems();
        }
        return $this->render('sideMenu', ['items' => $this->items, 'homeTitle'=>$this->homeTitle, 'homeUrl'=>$this->homeUrl]);
    }

    /**
     * admin default items
     * @return array
     */
    protected function adminItems(){
        return [
            ['icon' => 'users', 'label' => Yii::t('app','Users'), 'url' => ['/account/index'], 'active' => Core::checkMCA(null, 'account', '*')],
            ['icon' => 'key', 'label' => Yii::t('app','RBAC'), 'url' => ['/rbac/index'], 'active' => Core::checkMCA(null, 'rbac', '*')],
            ['icon' => 'edit', 'label' => Yii::t('app','Blog'), 'url' => ['/blog/index'], 'active' => Core::checkMCA(null, 'blog', '*')],
            ['icon' => 'files-o', 'label' => Yii::t('app','Pages'), 'url' => ['/page/index'], 'active' => Core::checkMCA(null, 'page', '*')],
            ['icon' => 'list', 'label' => Yii::t('app','Menu'), 'url' => ['/menu/index'], 'active' => Core::checkMCA(null, 'menu', '*')],
            ['icon' => 'cog', 'label' => Yii::t('app','Settings'), 'url' => ['/setting/index'], 'active' => Core::checkMCA(null, 'setting', '*')],
            ['icon' => 'language', 'label' => Yii::t('app','Languages'), 'url' => ['/i18n/index'], 'active' => Core::checkMCA(null, 'i18n', '*')],
            ['icon' => 'gears', 'label' => Yii::t('app','Services'), 'url' => ['/service/index'], 'active' => Core::checkMCA(null, 'service', '*')],
        ];
    }

    /**
     * account default items
     * @return array
     */
    protected function accountItems(){
        return [
            ['label'=>Yii::t('app', 'Personal settings')],
            ['icon' => 'circle-o', 'label' => Yii::t('app', 'Profile'), 'url' => ['/account/index'], 'active' => Core::checkMCA(null, 'account', 'index')],
            ['icon' => 'circle-o', 'label' => Yii::t('app', 'Email'), 'url' => ['/account/email'], 'active' => Core::checkMCA(null, 'account', 'email')],
            ['icon' => 'circle-o', 'label' => Yii::t('app', 'Password'), 'url' => ['/account/password'], 'active' => Core::checkMCA(null, 'account', 'password')],
            ['icon' => 'circle-o', 'label' => Yii::t('app', 'Linked Accounts'), 'url' => ['/account/linked'], 'active' => Core::checkMCA(null, 'account', 'linked')],

            ['label'=>Yii::t('app', 'Blog')],
            ['icon' => 'circle-o', 'label' => Yii::t('app', 'My Blog'), 'url' => ['/blog/manage'], 'active' => Core::checkMCA(null, 'blog', 'manage')],
            ['icon' => 'circle-o', 'label' => Yii::t('app', 'Write'), 'url' => ['/blog/create'], 'active' => Core::checkMCA(null, 'blog', 'create')],
        ];


    }


} 