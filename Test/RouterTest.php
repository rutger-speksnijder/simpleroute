<?php
namespace Test;

use \SimpleRoute\Router;

if (version_compare(PHP_VERSION, '7.0', '>=')) {
    class RouterTest extends \PHPUnit\Framework\TestCase
    {
        public function testCanNotExecuteWhenNoEmptyRouteIsSet()
        {
            $this->expectException('Exception');
            $r = new Router;
            $r->execute();
        }

        public function testCanExecute()
        {
            $r = new Router;
            $r->add('/', function() {});
            $r->add('/test', function() {
                return 'test';
            });

            $r->setUrl('/test');

            $expectedResult = 'test';
            $result = $r->execute();

            $this->assertEquals($expectedResult, $result);
        }
    }
} else {
    class RouterTest extends \PHPUnit_Framework_TestCase
    {
        public function testCanNotExecuteWhenNoEmptyRouteIsSet()
        {
            $this->setExpectedException('Exception');
            $r = new Router;
            $r->execute();
        }

        public function testCanExecute()
        {
            $r = new Router;
            $r->add('/', function() {});
            $r->add('/test', function() {
                return 'test';
            });

            $r->setUrl('/test');

            $expectedResult = 'test';
            $result = $r->execute();

            $this->assertEquals($expectedResult, $result);
        }
    }
}
