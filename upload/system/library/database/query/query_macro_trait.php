<?php

namespace Opencart\System\Library\Database\Query;

/**
 * Adds registered extension macros to an Aura query object.
 */
trait QueryMacroTrait {
    protected QueryMacroRegistry $macroRegistry;
    protected string $macroQueryType;

    protected function initializeMacros(QueryMacroRegistry $registry, string $queryType): void {
        $this->macroRegistry = $registry;
        $this->macroQueryType = $queryType;
    }

    public function __call(string $name, array $arguments): mixed {
        return $this->macroRegistry->call($this, $this->macroQueryType, $name, $arguments);
    }
}
