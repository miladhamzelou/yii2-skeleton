<?php
/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) 2017 Power Kernel
 */

namespace common\actions;


use common\models\Setting;
use Yii;
use yii\base\Action;

/**
 * Class BrowserConfigAction
 * @package common\actions
 */
class BrowserConfigAction extends Action
{

    /**
     * run action
     */
    public function run()
    {
        $gitHubPage = Yii::$app->params['gitHubPage'];
        $msTileColor = Setting::getValue('msTileColor');

        Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
        $xml = <<<EOB
<?xml version="1.0" encoding="utf-8"?>
<browserconfig>
    <msapplication>
        <tile>
            <square150x150logo src="{$gitHubPage}/favicon/mstile-150x150.png"/>
            <TileColor>{$msTileColor}</TileColor>
        </tile>
    </msapplication>
</browserconfig>
EOB;
        echo $xml;

    }


}