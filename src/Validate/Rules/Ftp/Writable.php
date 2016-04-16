<?php
/**
 * Jaeger
 *
 * @copyright	Copyright (c) 2015-2016, mithra62
 * @link		http://jaeger-app.com
 * @version		1.0
 * @filesource 	./Validate/Rules/Ftp/Connect.php
 */
namespace JaegerApp\Validate\Rules\Ftp;

use JaegerApp\Validate\AbstractRule;
use JaegerApp\Remote;
use JaegerApp\Remote\Ftp;
use JaegerApp\Remote\Local;

/**
 * Jaeger - FTP Writable Validation Rule
 *
 * Validates that a given path is writable by the supplied credentiasl directory
 *
 * @package Validate\Rules\Ftp
 * @author Eric Lamb <eric@mithra62.com>
 */
class Writable extends AbstractRule
{

    /**
     * The Rule shortname
     * 
     * @var string
     */
    protected $name = 'ftp_writable';

    /**
     * The error template
     * 
     * @var string
     */
    protected $error_message = 'Can\'t connect to {field}';

    /**
     * (non-PHPdoc)
     * 
     * @see \mithra62\Validate\RuleInterface::validate()
     * @ignore
     *
     */
    public function validate($field, $input, array $params = array())
    {
        try {
            if ($input == '' || empty($params['0'])) {
                return false;
            }
            
            $params = $params['0'];
            if (empty($params['ftp_hostname']) || empty($params['ftp_password']) || empty($params['ftp_username']) || empty($params['ftp_port']) || empty($params['ftp_store_location'])) {
                return false;
            }
            
            $local = new Remote(new Local(dirname($this->getTestFilePath())));
            $filesystem = new Remote(Ftp::getRemoteClient($params));
            
            if ($local->has($this->test_file)) {
                $contents = $local->read($this->test_file);
                
                $filesystem->getAdapter()->setRoot($params['ftp_store_location']);
                
                if ($filesystem->has($this->test_file)) {
                    $filesystem->delete($this->test_file);
                } else {
                    if ($filesystem->write($this->test_file, $contents)) {
                        $filesystem->delete($this->test_file);
                    }
                }
            }
            
            $filesystem->getAdapter()->disconnect();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

$rule = new Writable;
\JaegerApp\Validate::addrule($rule->getName(), array($rule, 'validate'), $rule->getErrorMessage());
