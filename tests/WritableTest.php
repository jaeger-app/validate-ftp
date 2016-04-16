<?php
/**
 * mithra62 - Unit Test
 *
 * @copyright	Copyright (c) 2015, mithra62, Eric Lamb.
 * @link		http://mithra62.com/
 * @version		1.0
 * @filesource 	./mithra62/tests/Validate/Rules/Ftp/ExistsTest.php
 */
namespace mithra62\tests\Validate\Rules\Ftp;

use mithra62\tests\TestFixture;
use mithra62\Validate;
use mithra62\Validate\Rules\Ftp\Writable;

/**
 * mithra62 - Valiate object Unit Tests
 *
 * Contains all the unit tests for the \mithra62\Valiate object
 *
 * @package mithra62\Tests
 * @author Eric Lamb <eric@mithra62.com>
 */
class WritableTest extends TestFixture
{

    /**
     * Tests the name of the rule
     */
    public function testName()
    {
        $dir = new Writable();
        $this->assertEquals($dir->getName(), 'ftp_writable');
    }

    /**
     * Tests that a directory can be determined true
     */
    public function testRuleFail()
    {
        $val = new Validate();
        $creds = $this->getFtpCreds();
        $creds['ftp_store_location'] = '/fdsafdsa';
        $val->rule('ftp_writable', 'connection_field', $creds)->val(array(
            'connection_field' => 'Foo'
        ));
        $this->assertTrue($val->hasErrors());
    }

    /**
     * Tests that a directory can be determined true
     */
    public function testRuleSuccess()
    {
        $val = new Validate();
        $val->rule('ftp_writable', 'connection_field', $this->getFtpCreds())
            ->val(array(
            'connection_field' => 'Foo'
        ));
        $this->assertFALSE($val->hasErrors());
    }
}