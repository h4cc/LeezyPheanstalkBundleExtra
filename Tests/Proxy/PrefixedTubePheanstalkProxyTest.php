<?php
/*
 * This file is part of the h4cc/LeezyPheanstalkBundleExtra package.
 *
 * (c) Julius Beckmann <github@h4cc.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leezy\LeezyPheanstalkBundle\Tests\Proxy;

use h4cc\LeezyPheanstalkBundleExtra\Proxy\PrefixedTubePheanstalkProxy;

class PrefixedTubePheanstalkProxyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Leezy\PheanstalkBundle\Proxy\PheanstalkProxy
     */
    protected $pheanstalkProxy;

    /**
     * @var \Pheanstalk_PheanstalkInterface
     */
    protected $pheanstalk;

    public function setUp()
    {
        $this->pheanstalk = $this->getMock('Pheanstalk_PheanstalkInterface');
        $this->pheanstalkProxy = new PrefixedTubePheanstalkProxy();
    }

    public function tearDown()
    {
        unset($this->pheanstalk);
        unset($this->pheanstalkProxy);
    }

    public function testInterfaces()
    {
        $this->assertInstanceOf('Leezy\PheanstalkBundle\Proxy\PheanstalkProxyInterface', $this->pheanstalkProxy);
        $this->assertInstanceOf('Pheanstalk_PheanstalkInterface', $this->pheanstalkProxy);
    }

    public function testProxyValue()
    {
        $this->pheanstalkProxy->setPheanstalk($this->pheanstalk);
        $this->assertEquals($this->pheanstalk, $this->pheanstalkProxy->getPheanstalk());
    }

    public function namedFunctions()
    {
        /* Structure:
         * - Name of function.
         * - Arguments for the proxy method.
         * - Expected arguments for the PheanstalkMock.
         * - Value the PheanstalkMock will return.
         * - Expected value the proxy returns.
         */

        $tubePrefix = 'test_';
        return array (
            array('ignore', array('foo'), array($tubePrefix.'foo')),
            array('listTubes', array(), array(), array($tubePrefix.'bar'), array('bar')),
            array('listTubesWatched', array(true), array(true), array($tubePrefix.'bar'), array('bar')),
            array('listTubeUsed', array(true), array(true), $tubePrefix.'bar', 'bar'),
            array('pauseTube', array('foo', 42),  array($tubePrefix.'foo', 42)),
            array('peekReady', array('foo'), array($tubePrefix.'foo')),
            array('peekDelayed', array('foo'), array($tubePrefix.'foo')),
            array('peekBuried', array('foo'), array($tubePrefix.'foo')),
            array('putInTube', array('foo', 'bar', 42, 42, 42), array($tubePrefix.'foo', 'bar', 42, 42, 42)),
            array('reserveFromTube', array('foo', 42),  array($tubePrefix.'foo', 42)),
            array('statsTube', array('foo'), array($tubePrefix.'foo')),
            array('useTube', array('foo'), array($tubePrefix.'foo')),
            array('watch', array('foo'), array($tubePrefix.'foo')),
            array('watchOnly', array('foo'), array($tubePrefix.'foo')),
        );
    }

    /**
     * Testing without prefix.
     *
     * @dataProvider namedFunctions
     */
    public function testProxyFunctionCallsNoPrefix($name, array $value)
    {
        $pheanstalkProxy = new PrefixedTubePheanstalkProxy();
        $pheanstalkMock = $this->getMock('Pheanstalk_PheanstalkInterface');
        $dispatchMock = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $builder = $pheanstalkMock->expects($this->once())->method($name);
        call_user_func_array(array($builder, 'with'), $value);

        $pheanstalkProxy->setPheanstalk($pheanstalkMock);
        $pheanstalkProxy->setDispatcher($dispatchMock);

        $result = call_user_func_array(array($pheanstalkProxy, $name), $value);
    }

    /**
     * TEsting with prefix.
     *
     * @dataProvider namedFunctions
     */
    public function testProxyFunctionCallsWithPrefix(
        $name,
        array $value,
        $expectedCallValue=null,
        $returnValue = null,
        $expectedReturnValue = null
    )
    {
        if(!$expectedCallValue) {
            $expectedCallValue = $value;
        }

        if(!$returnValue) {
            $returnValue = 1337;
        }

        if(!$expectedReturnValue) {
            $expectedReturnValue = $returnValue;
        }

        $pheanstalkProxy = new PrefixedTubePheanstalkProxy();
        $pheanstalkProxy->setTubePrefix('test_');

        $this->assertEquals('test_', $pheanstalkProxy->getTubePrefix());

        $pheanstalkMock = $this->getMock('Pheanstalk_PheanstalkInterface');
        $dispatchMock = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $builder = $pheanstalkMock->expects($this->once())->method($name);
        call_user_func_array(array($builder, 'with'), $expectedCallValue);

        $pheanstalkProxy->setPheanstalk($pheanstalkMock);
        $pheanstalkProxy->setDispatcher($dispatchMock);

        $result = call_user_func_array(array($pheanstalkProxy, $name), $value);
    }
}
