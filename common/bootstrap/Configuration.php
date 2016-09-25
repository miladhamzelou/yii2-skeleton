<?php
/**
 * @author Harry Tang <harry@modernkernel.com>
 * @link https://modernkernel.com
 * @copyright Copyright (c) 2016 Modern Kernel
 */

namespace common\bootstrap;


use common\models\Message;
use common\models\Setting;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\web\View;

/**
 * Class Configuration
 * @package common\components
 */
class Configuration extends Component
{
    public function init()
    {

        parent::init(); // TODO: Change the autogenerated stub

        /* do not run if in migrate cmd */
        if (is_a(Yii::$app, '\yii\console\Application') && !empty(Yii::$app->request->params[0]) && preg_match('/migrate/', Yii::$app->request->params[0])) {
            return;
        }

        Yii::$app->name = Setting::getValue('title');

        $this->configAuthClient();

        $this->configMailer();

        $this->configDebugMode();

        $this->configUrlManager();

        $this->configReCaptcha();

        $this->configUserSettings();

        $this->configHsts();

        $this->configZopim();

    }

    /**
     * Zopim
     */
    protected function configZopim()
    {
        if (Yii::$app->id == 'app-frontend') {
            if ($id = Setting::getValue('zopim')) {
                $js = <<<EOB
window.\$zopim||(function(d,s){var z=\$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="//v2.zopim.com/?{$id}";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
EOB;
                Yii::$app->view->registerJs($js, View::POS_END);
            }
        }
    }




    /**
     * Enable HSTS
     */
    protected function configHsts()
    {
        if (Yii::$app->request->isSecureConnection) {
            $response = Yii::$app->response;
            $response->headers->set('Strict-Transport-Security', 'max-age=15552000');
            $response->headers->set('x-content-type-options', 'nosniff');
        }
    }

    /**
     * user settings
     */
    protected function configUserSettings()
    {
        /* language / timezone */
        if (is_a(Yii::$app, '\yii\web\Application')) {
            if (!Yii::$app->user->isGuest) {
                try {
                    $user = Yii::$app->user->identity;
                    /* if user local not exist, set default */
                    $locales = Message::getLocaleList();
                    if (!in_array($user->language, array_keys($locales))) {
                        $user->language = Setting::getValue('language');
                        $user->save();
                    }
                    Yii::$app->language = $user->language;
                    Yii::$app->setTimeZone($user->timezone);


                } catch (Exception $e) {
                    Yii::$app->cache->flush();
                    Yii::$app->db->schema->refresh();
                    Yii::$app->user->logout();
                }
            } else {
                $timezone = Setting::getValue('timezone');
                $language = Setting::getValue('language');
                Yii::$app->language = $language;
                Yii::$app->setTimeZone($timezone);
            }
        }
    }


    /**
     * ReCaptcha
     */
    protected function configReCaptcha()
    {
        // recaptcha
        $rcKey = Setting::getValue('reCaptchaKey');
        $rcSecret = Setting::getValue('reCaptchaSecret');
        if (!empty($rcKey) && !empty($rcSecret)) {
            Yii::$container->set('himiklab\yii2\recaptcha\ReCaptcha', [
                'siteKey' => $rcKey,
                'secret' => $rcSecret,
            ]);
        }
    }


    /**
     * Auth clients
     */
    protected function configAuthClient()
    {

        $clients = [];

        // client facebook
        $fbAppId = Setting::getValue('facebookAppId');
        $fbAppSecret = Setting::getValue('facebookAppSecret');
        if (!empty($fbAppId) && !empty($fbAppSecret)) {
            $clients['facebook'] = [
                'class' => 'yii\authclient\clients\Facebook',
                'clientId' => $fbAppId,
                'clientSecret' => $fbAppSecret,
            ];
        }

        // client google
        $gClientId = Setting::getValue('googleClientId');
        $gClientSecret = Setting::getValue('googleClientSecret');
        if (!empty($gClientId) && !empty($gClientSecret)) {
            $clients['google'] = [
                'class' => 'yii\authclient\clients\Google',
                'clientId' => $gClientId,
                'clientSecret' => $gClientSecret,
            ];
        }

        // flickr-photo
        $flickrClientKey = Setting::getValue('flickrClientKey');
        $flickrClientSecret = Setting::getValue('flickrClientSecret');
        if (!empty($flickrClientKey) && !empty($flickrClientSecret)) {
            $clients['flickr-photo'] = [
                'class' => 'common\components\FlickrPhoto',
                'perms' => 'write',
                'consumerKey' => $flickrClientKey,
                'consumerSecret' => $flickrClientSecret,
            ];
        }

        // clients OK
        if (!empty($clients)) {
            Yii::$container->set('yii\authclient\Collection', [
                'class' => 'yii\authclient\Collection',
                'clients' => $clients,
            ]);
        }
    }


    /**
     * mailer
     */
    protected function configMailer()
    {
        $mailProtocol = Setting::getValue('mailProtocol');
        if ($mailProtocol == 'smtp') {
            $host = Setting::getValue('smtpHost');
            $user = Setting::getValue('smtpUsername');
            $pass = Setting::getValue('smtpPassword');
            $port = Setting::getValue('smtpPort');
            $encryption = Setting::getValue('smtpEncryption');
            Yii::$container->set('yii\swiftmailer\Mailer', [
                'viewPath' => '@common/mail',
                'useFileTransport' => false,
                'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    'host' => $host,
                    'username' => $user,
                    'password' => $pass,
                    'port' => $port,
                    'encryption' => $encryption,
                ],
            ]);
        }
    }


    /**
     * UrlManager
     */
    protected function configUrlManager()
    {

        $modules = scandir(__DIR__ . '/../../vendor/modernkernel');
        $urlManager = [
            'ignoreLanguageUrlPatterns' => [
                '#^account/auth#' => '#^account/auth#',
                '#^site/logout#' => '#^site/logout#',
                '#^site/robots#' => '#^robots.txt#',
                '#^site/sitemap#' => '#^sitemap\.xml#',
                '#^blog/sitemap#' => '#^blog/sitemap\d+\.xml#',
            ],
            'rules' => [
                '' => 'site/index',
                'sitemap.xml' => 'site/sitemap',
                'robots.txt' => 'site/robots',
                'manifest.json' => 'site/manifest',
                'browserconfig.xml' => 'site/browserconfig',
                '<id:.+?>.html' => 'site/page',

                /* blog */
                'blog/<action:(manage|create|update|delete)>' => 'blog/<action>',
                'blog/sitemap<page:\d+>.xml' => 'blog/sitemap',
                'blog' => 'blog/index',
                'blog/<name:.+?>' => 'blog/view',

                /* page */
                'page/sitemap<page:\d+>.xml' => 'page/sitemap',
            ],
        ];
        foreach ($modules as $module) {
            if (!preg_match('/[\.]+/', $module)) {
                $urlManagerFile = __DIR__ . '/../../vendor/modernkernel/' . $module . '/urlManager.php';
                if (is_file($urlManagerFile)) {
                    $urlManagerConfig = require($urlManagerFile);
                    $urlManager['ignoreLanguageUrlPatterns'] = array_merge(
                        $urlManager['ignoreLanguageUrlPatterns'],
                        $urlManagerConfig['ignoreLanguageUrlPatterns']
                    );
                    $urlManager['rules'] = array_merge(
                        $urlManager['rules'],
                        $urlManagerConfig['rules']
                    );
                }
            }
        }

        Yii::$container->set('common\components\LocaleUrl', [
            /* config */
            'languages' => array_keys(Message::getLocaleList()),
            'languageParam' => 'lang',
            'enableLanguagePersistence' => false, // default true
            'enableDefaultLanguageUrlCode' => (boolean)Setting::getValue('languageUrlCode'),
            'enableLanguageDetection' => false, // default true
            'ignoreLanguageUrlPatterns' => $urlManager['ignoreLanguageUrlPatterns'],
            'rules' => $urlManager['rules']
        ]);
    }

    /**
     * debug mode
     */
    protected function configDebugMode()
    {
        if (Setting::getValue('debug') && !is_a(Yii::$app, 'yii\console\Application') && Yii::$app->user->can('admin')) {
            $module = Yii::$app->getModule('debug');
            $module->allowedIPs = [Yii::$app->request->userIP];
        }
    }
}