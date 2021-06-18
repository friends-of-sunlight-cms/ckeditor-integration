<?php

namespace SunlightExtend\Ckeditor;

use Sunlight\Core;
use Sunlight\Plugin\Action\ConfigAction;
use Sunlight\Plugin\ExtendPlugin;
use Sunlight\Util\Form;
use Sunlight\Plugin\Action\PluginAction;

/**
 * CKEditor plugin
 *
 * @author Jirka Daněk <jdanek.eu>
 */
class CkeditorPlugin extends ExtendPlugin
{
    private $wysiwygDetected = false;

    protected function getConfigDefaults(): array
    {
        return array(
            'editor_mode' => 'basic',
            'mode_by_priv' => false,
            // privileges
            'priv_min_limited' => 1,
            'priv_max_limited' => 500,
            'priv_min_basic' => 600,
            'priv_max_basic' => 1000,
            'priv_min_advanced' => 10000,
            'priv_max_advanced' => 10001,
        );
    }

    /**
     * @param array $args
     */
    public function onHead(array $args)
    {
        if (_logged_in && !$this->isDisabled() && !$this->wysiwygDetected && (bool)Core::$userData['wysiwyg'] === true) {
            $args['js'][] = $this->getWebPath() . '/Resources/ckeditor/ckeditor.js';

            $active_mode = $this->getConfig()->offsetGet('editor_mode');

            // mode by priv
            if ($this->getConfig()->offsetGet('mode_by_priv') === true) {

                foreach (array('limited', 'basic', 'advanced') as $mode) {
                    if (_priv_level >= $this->getConfig()->offsetGet('priv_min_' . $mode)
                        && (_priv_level <= $this->getConfig()->offsetGet('priv_max_' . $mode))) {
                        $active_mode = $mode;
                    }
                }
            }

            $args['js'][] = $this->getWebPath() . '/Resources/wysiwyg_' . $active_mode . '.js';
        }
    }

    /**
     * @param $args
     */
    public function onWysiwyg($args)
    {
        if ($args['available']) {
            $this->wysiwygDetected = true;
        } elseif (_logged_in && !$this->isDisabled() && (bool)Core::$userData['wysiwyg']) {
            $args['available'] = true;
        }
    }

    /**
     * @param array $args
     */
    public function onCoreJavascript(array $args)
    {
        $args['variables']['pluginWysiwyg'] = array(
            'systemLang' => _language,
        );
    }

    public function getAction($name): ?PluginAction
    {
        if ($name == 'config') {
            return new CustomConfig($this);
        }
        return parent::getAction($name);
    }
}

class CustomConfig extends ConfigAction
{
    protected function getFields(): array
    {
        $modes = array(
            _lang('ckeditor.limited') => 'limited',
            _lang('ckeditor.basic') => 'basic',
            _lang('ckeditor.advanced') => 'advanced'
        );

        $fields = array(
            'editor_mode' => array(
                'label' => _lang('ckeditor.mode'),
                'input' => $this->createSelect('editor_mode', $modes, $this->plugin->getConfig()->offsetGet('editor_mode')),
                'type' => 'text'
            ),
            'mode_by_priv' => array(
                'label' => _lang('ckeditor.mode_by_priv'),
                'input' => $this->createInput('checkbox', 'mode_by_priv'),
                'type' => 'checkbox'
            ),
        );

        foreach (array('limited', 'basic', 'advanced') as $v) {
            foreach (array('min', 'max') as $v2) {
                $name = 'priv_' . $v2 . '_' . $v;
                $fields[$name] = array(
                    'label' => _lang('ckeditor.' . $name),
                    'input' => $this->createInput('number', $name, array('min' => 0, 'max' => _priv_max_level)),
                    'type' => 'text'
                );
            }
        }

        return $fields;
    }

    private function createSelect($name, $options, $default)
    {
        $result = "<select name='config[" . $name . "]'>";
        foreach ($options as $k => $v) {
            $result .= "<option value='" . $v . "'" . ($default == $v ? " selected" : "") . ">" . $k . "</option>";
        }
        $result .= "</select>";
        return $result;
    }

    private function createInput($type, $name, $attributes = null)
    {
        $result = "";
        $attr = array();

        if (is_array($attributes)) {
            foreach ($attributes as $k => $v) {
                if (is_integer($k)) {
                    $attr[] = $v . '=' . $v;
                } else {
                    $attr[] = $k . '=' . $v;
                }
            }
        }

        if ($type === 'checkbox') {
            $result = '<input type="checkbox" name="config[' . $name . ']" value="1"' . implode(' ', $attr) . Form::activateCheckbox($this->plugin->getConfig()->offsetGet($name)) . '>';
        } else {
            $result = '<input type="' . $type . '" name="config[' . $name . ']" value="' . $this->plugin->getConfig()->offsetGet($name) . '"' . implode(' ', $attr) . '>';
        }

        return $result;
    }
}
