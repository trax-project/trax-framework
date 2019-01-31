<?php

namespace Trax\Foundation\Options;

use Trax\Foundation\Options\OptionsModel;

class StandardStatus extends OptionsModel
{

    /**
     * Get data.
     */
    protected function data($plural = false)
    {
        return [
            'draft' => [
                'name' => __('trax-ui::options.draft'),
            ],
            'active' => [
                'name' => __('trax-ui::options.active'),
            ],
            'maintenance' => [
                'name' => __('trax-ui::options.maintenance'),
            ],
            'archived' => [
                'name' => __('trax-ui::options.archived'),
            ],
        ];
    }

}

