<?php

namespace Opencart\System\Library\Database\Query;

use Aura\SqlQuery\Common\QuoterInterface;
use Aura\SqlQuery\Mysql\Delete as AuraDelete;

class Delete extends AuraDelete {
    use QueryMacroTrait;
    use QueryStatementTrait;

    public function __construct(
        QuoterInterface $quoter,
        $builder,
        QueryExecutor $executor,
        QueryMacroRegistry $macroRegistry
    ) {
        parent::__construct($quoter, $builder);

        $this->initializeExecutor($executor);
        $this->initializeMacros($macroRegistry, 'delete');
    }
}
