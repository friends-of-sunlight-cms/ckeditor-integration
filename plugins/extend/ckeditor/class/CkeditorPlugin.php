<?php

namespace SunlightExtend\Ckeditor;

use Sunlight\Core;
use Sunlight\Plugin\ExtendPlugin;
use Sunlight\Plugin\Plugin;
use Sunlight\User;

class CkeditorPlugin extends ExtendPlugin
{
    private const SUPPORTED_FORMATS = [
        'xml' => false,
        'css' => false,
        'js' => false,
        'json' => false,
        'php' => false,
        'php-raw' => false,
        'html' => true,
    ];

    /** @var bool */
    private $wysiwygDetected = false;

    public function onAdminInit(array $args): void
    {
        global $_admin;
        $_admin->wysiwygAvailable = true;
    }

    public function onAdminHead(array $args): void
    {
        if (User::isLoggedIn() && !$this->hasStatus(Plugin::STATUS_DISABLED) && !$this->wysiwygDetected && (bool)User::$data['wysiwyg'] === true) {
            $args['js'][] = $this->getAssetPath('public/ckeditor/ckeditor.js');

            $active_mode = $this->getConfig()->offsetGet('editor_mode');

            // mode by priv
            if ($this->getConfig()->offsetGet('mode_by_priv') === true) {

                foreach (['limited', 'basic', 'advanced'] as $mode) {
                    if (User::getLevel() >= $this->getConfig()->offsetGet('priv_min_' . $mode)
                        && (User::getLevel() <= $this->getConfig()->offsetGet('priv_max_' . $mode))) {
                        $active_mode = $mode;
                    }
                }
            }

            $args['js'][] = $this->getAssetPath('public/wysiwyg_' . $active_mode . '.js');
        }
    }

    function onAdminEditor(array $args): void
    {
        global $_admin;

        $config = $this->getConfig();

        if (
            ($args['context'] === 'box-content' && $config['editor_in_boxes'] === false)
            || (
                ($args['context'] === 'page-perex' || $args['context'] === 'article-perex')
                && $config['editor_in_perex'] === false
            )
        ) {
            $args['options']['mode'] = 'code';
        }

        if (
            isset(self::SUPPORTED_FORMATS[$args['options']['format']])
            && $args['options']['mode'] === 'default'
            && $_admin->wysiwygAvailable
            && User::isLoggedIn()
            && User::$data['wysiwyg']
        ) {
            $this->enableEventGroup('ckeditor');
        }
    }

    public function onCoreJavascript(array $args): void
    {
        $args['variables']['pluginWysiwyg'] = [
            'systemLang' => Core::$lang,
        ];
    }


}
