<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2017 Modern Kernel
 */


namespace frontend\widgets;


use Yii;
use yii\jui\Widget;

/**
 * Class Menu
 * @package frontend\widgets
 */
class Menu extends Widget
{
    public $position = 'header';

    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::run(); // TODO: Change the autogenerated stub
        $items=$this->getMenuItems();
        return $this->render('menu', ['items'=>$items]);
    }

    /**
     * get menu items
     * @return array
     */
    protected function getMenuItems()
    {
        $items = [];
        $menus = \common\models\Menu::find()
            ->where([
                'status' => \common\models\Menu::STATUS_ACTIVE,
                'id_parent' => null,
                'position' => $this->position])
            ->orderBy('order')->all();
        foreach ($menus as $menu) {
            $items[] = [
                'active' => $menu->getActiveStatus(),
                'label' => Yii::t('main', $menu->label),
                'url' => preg_match('/\/\//', $menu->url) ? $menu->url : $menu->route,
                'linkOptions' => ['class' => $menu->class],
                'items' => $menu->generateSubNavItem()
            ];
        }
        return $items;
    }
}