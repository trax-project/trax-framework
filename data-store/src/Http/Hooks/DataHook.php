<?php

namespace Trax\DataStore\Http\Hooks;

use Illuminate\Http\Request;

trait DataHook
{

    /**
     * Data stored hook.
     */
    protected function hookDataStored(Request $request, $data, $res)
    {
    }

    /**
     * Data updated hook.
     */
    protected function hookDataUpdated(Request $request, $model, $data, $res)
    {
    }

    /**
     * Data updated hook.
     */
    protected function hookDataDuplicated(Request $request, $model, $data, $res)
    {
    }

}
