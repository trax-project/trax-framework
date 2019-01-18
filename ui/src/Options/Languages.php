<?php

namespace Trax\UI\Options;

use Trax\Foundation\Options\OptionsModel;

class Languages extends OptionsModel
{

    /**
     * Get data.
     */
    protected function data($plural = false)
    {
        return [
            'fr' => [
                'name' => __('trax-ui::lang.french'),
            ],
            'en' => [
                'name' => __('trax-ui::lang.english'),
            ],
        ];
    }

}

