<?php
/**
 * Jaeger
 *
 * @copyright	Copyright (c) 2015-2016, mithra62
 * @link		http://jaeger-app.com
 * @version		1.0
 * @filesource 	./tests/ConnectTest.php
 */
namespace JaegerApp\tests;

use JaegerApp\Validate;
use JaegerApp\Validate\Rules\Ftp\Connect;

/**
 * Jaeger - Valiate object Unit Tests
 *
 * Contains all the unit tests for the \mithra62\Valiate object
 *
 * @package Jaeger\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class ConnectTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests the name of the rule
     */
    public function testName()
    {
        $dir = new Connect();
        $this->assertEquals($dir->getName(), 'ftp_connect');
    }

    /**
     * Tests that a file can be determined false
     */
    public function testRuleFail()
    {
        $val = new Validate();
        $creds = $this->getFtpCreds();
        $creds['ftp_username'] = '/cache';
        $val->rule('ftp_connect', 'connection_field', $creds)->val(array(
            'connection_field' => __FILE__
        ));
        $this->assertTrue($val->hasErrors());
    }

    /**
     * Tests that a directory can be determined true
     */
    public function testRuleSuccess()
    {
        $val = new Validate();
        $val->rule('ftp_connect', 'connection_field', $this->getFtpCreds())
            ->val(array(
            'connection_field' => 'Foo'
        ));
        $this->assertFALSE($val->hasErrors());
    }

    /**
     * The FTP Test Credentials
     *
     * @return array
     */
    protected function getFtpCreds()
    {
        return include 'data/ftpcreds.config.php';
    }    
}