<?php

namespace SunlightExtend\Ckeditor;

use Fosc\Feature\Plugin\Config\FieldGenerator;
use Sunlight\Core;
use Sunlight\Plugin\Action\ConfigAction as BaseConfigAction;
use Sunlight\User;

class ConfigAction extends BaseConfigAction
{
    protected function getFields(): array
    {
        $modes = [
            'limited' => _lang('ckeditor.config.limited'),
            'basic' => _lang('ckeditor.config.basic'),
            'advanced' => _lang('ckeditor.config.advanced')
        ];

        // filemanager plugin exists?
        $fmAttr = [];
        if (!Core::$pluginManager->getPlugins()->has('extend/wysiwyg-fm')) {
            $fmAttr[] = 'disabled';
        }

        $privNames = [];
        foreach (['limited', 'basic', 'advanced'] as $k => $v) {
            foreach (['min', 'max'] as $v2) {
                $privNames[] = 'priv_' . $v2 . '_' . $v;
            }
        }

        $langPrefix = "%p:ckeditor.config";

        $gen = new FieldGenerator($this->plugin);
        $gen->generateField('editor_mode', $langPrefix, '%select', [
            'class' => 'inputsmall',
            'select_options' => $modes
        ], 'text')
            ->generateField('filemanager', $langPrefix, '%checkbox', $fmAttr)
            ->generateFields([
                'editor_in_perex',
                'editor_in_boxes',
                'mode_by_priv'
            ], $langPrefix, '%checkbox')
            ->generateFields($privNames, $langPrefix, '%number', ['min' => -1, 'max' => User::MAX_LEVEL]);

        return $gen->getFields();
    }
}
