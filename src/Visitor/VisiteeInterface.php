<?php
namespace Dschoenbauer\SqlControl\Visitor;

/**
 *
 * @author David Schoenbauer <dschoenbauer@gmail.com>
 */
interface VisiteeInterface
{

    public function accept(VisitorInterface $visitor);
}
