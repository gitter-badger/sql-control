<?php
namespace Dschoenbauer\SqlControl\Listener;

use Dschoenbauer\SqlControl\Enum\Events;
use Dschoenbauer\SqlControl\SqlControlManager;
use Dschoenbauer\SqlControl\Visitor\VisitorInterface;

/**
 * Description of RecordResult
 *
 * @author David Schoenbauer <dschoenbauer@gmail.com>
 */
class RecordResult implements VisitorInterface
{
    public function visitSqlControlManager(SqlControlManager $sqlControlManager)
    {
        $sqlControlManager->getEventManager()->attach(Events::RESULT, [$this,'onResult']);
    }
}
