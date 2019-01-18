<?php

namespace Trax\Account\Options;

use Trax\Foundation\Options\OptionsModel;

class UserSources extends OptionsModel
{

    /**
     * Get data.
     */
    protected function data($plural = false)
    {
        return [
            'internal' => [
                'name' => __('trax-account::options.internal'),
            ],
            'external' => [
                'name' => __('trax-account::options.external'),
            ],
        ];
    }

}

