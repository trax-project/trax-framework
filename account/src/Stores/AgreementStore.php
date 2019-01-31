<?php

namespace Trax\Account\Stores;

traxCreateDataStoreSwitchClass('Trax\Account\Stores', 'trax-account', 'Agreement');

class AgreementStore extends AgreementStoreSwitch
{

    /**
     * Get the last published agrrement.
     */
    public function lastPublished()
    {
        return $this->get([
            'search' => [(object)array('key' => 'published', 'operator' => 'BOOL', 'value' => true)],
            'limit' => 1,
            'order-dir' => 'desc',
            'order-by' => 'id',
        ])->first();
    }

    /**
     * Get the draft agreement.
     */
    public function draft()
    {
        return $this->get([
            'search' => [(object)array('key' => 'published', 'operator' => 'BOOL', 'value' => false)],
            'limit' => 1,
            'order-dir' => 'desc',
            'order-by' => 'id',
        ])->first();
    }


}
