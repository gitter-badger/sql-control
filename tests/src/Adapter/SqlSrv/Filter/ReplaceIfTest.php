<?php

namespace Ctimt\SqlControl\Adapter\SqlSrv\Filter;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-19 at 18:02:52.
 */
class ReplaceIfTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ReplaceIf
     */
    protected $object;

    protected function setUp() {
        $this->object = new ReplaceIf();
    }

    public function testFilter() {
        $test = "UPDATE Product SET product_public = if(product_id = 5017,1,0)";
        $result = "UPDATE Product SET product_public = IIF(product_id = 5017,1,0)";
        $this->assertEquals($result, $this->object->filter($test));
    }

}
