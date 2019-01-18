<?php

namespace Trax\UI\Options;

use Trax\Foundation\Options\OptionsModel;

class Backgrounds extends OptionsModel
{

    /**
     * Get data.
     */
    protected function data($plural = false)
    {
        $res = [
            'default.jpg' => [
                'name' => 'Default',
            ]
        ];
        return $res;
    }

}

