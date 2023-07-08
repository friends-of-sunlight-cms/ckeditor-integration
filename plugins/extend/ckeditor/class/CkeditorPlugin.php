<?php

namespace SunlightExtend\Ckeditor;

use Sunlight\Core;
use Sunlight\Plugin\Action\ConfigAction;
use Sunlight\Plugin\Action\PluginAction;
use Sunlight\Plugin\ExtendPlugin;
use Sunlight\Plugin\Plugin;
use Sunlight\User;
use Sunlight\Util\Form;

class CkeditorPlugin extends ExtendPlugin
{
    /** @var bool */
    private $wysiwygDetected = false;

    public function onHead(array $args): void
    {
        if (User::isLoggedIn() && !$this->hasStatus(Plugin::STATUS_DISABLED) && !$this->wysiwygDetected && (bool)User::$data['wysiwyg'] === true) {
            $args['js'][] = $this->getWebPath() . '/public/ckeditor/ckeditor.js';

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

            $args['js'][] = $this->getWebPath() . '/public/wysiwyg_' . $active_mode . '.js';
        }
    }

    public function onWysiwyg(array $args): void
    {
        if ($args['available']) {
            $this->wysiwygDetected = true;
        } elseif (User::isLoggedIn() && !$this->hasStatus(Plugin::STATUS_DISABLED) && (bool)User::$data['wysiwyg'] === true) {
            $args['available'] = true;
        }
    }

    public function onCoreJavascript(array $args): void
    {
        $args['variables']['pluginWysiwyg'] = [
            'systemLang' => Core::$lang,
        ];
    }
}
