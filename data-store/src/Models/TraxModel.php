<?php

namespace Trax\DataStore\Models;

trait TraxModel
{
    /**
     * Get the model definition.
     */
    public function definition()
    {
        if (!isset($this->trax)) return false;
        $definition = (object)$this->trax;
        if (!isset($definition->protected)) $definition->protected = [];
        if (!isset($definition->columns)) $definition->columns = [];
        if (!isset($definition->relations)) $definition->relations = [];
        if (!isset($definition->options)) $definition->options = [];
        if (!isset($definition->virtualColumns)) $definition->virtualColumns = [];
        if (!isset($definition->ignore)) $definition->ignore = [];
        if (!isset($definition->defaultValues)) $definition->defaultValues = [];
        $except = array_merge(array_keys($definition->relations), array_keys($definition->options), $definition->ignore);
        $definition->visible = array_diff($this->visible, $except);
        return $definition;
    }

}
