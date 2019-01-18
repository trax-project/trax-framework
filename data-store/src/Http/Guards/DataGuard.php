<?php

namespace Trax\DataStore\Http\Guards;

use Illuminate\Http\Request;

trait DataGuard
{

    /**
     * Guard get request.
     */
    protected function guardGetRequest(Request $request)
    {
    }

    /**
     * Guard store request.
     */
    protected function guardStoreRequest(Request $request)
    {
    }

    /**
     * Guard update request.
     */
    protected function guardUpdateRequest(Request $request, $id = null)
    {
    }

    /**
     * Guard find request.
     */
    protected function guardFindRequest(Request $request, $id = null)
    {
    }

    /**
     * Guard findBy request.
     */
    protected function guardFindByRequest(Request $request)
    {
    }

    /**
     * Guard delete request.
     */
    protected function guardDeleteRequest(Request $request, $id = null)
    {
    }

    /**
     * Guard count request.
     */
    protected function guardCountRequest(Request $request)
    {
    }

    /**
     * Guard clear request.
     */
    protected function guardClearRequest(Request $request)
    {
    }

    /**
     * Guard register request.
     */
    protected function guardRegisterRequest(Request $request, $leftModel, $rightModel)
    {
    }

    /**
     * Guard unregister request.
     */
    protected function guardUnregisterRequest(Request $request, $leftModel, $rightModel)
    {
    }


}
