<?php

namespace Trax\Account\Options;

use Trax\Foundation\Options\OptionsModel;

class RightsLevels extends OptionsModel
{

    /**
     * Get data.
     */
    protected function data($plural = false)
    {
        return [
            'global' => [
                'name' => __('trax-account::options.global_rights_level'),
            ],
            'organization' => [
                'name' => __('trax-account::options.organization_rights_level'),
            ],
            'entity' => [
                'name' => __('trax-account::options.entity_rights_level'),
            ],
        ];
    }

}

