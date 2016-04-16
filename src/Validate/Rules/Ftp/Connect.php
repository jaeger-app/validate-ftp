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

/**
 * Jaeger - FTP Connection Validation Rule
 *
 * Validates that a given credential set is accurate and working for connecting to an FTP site
 *
 * @package Validate\Rules\Ftp
 * @author Eric Lamb <eric@mithra62.com>
 */
class Connect extends AbstractRule
{

    /**
     * The Rule shortname
     * 
     * @var string
     */
    protected $name = 'ftp_connect';

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
            if (empty($params['ftp_hostname']) || empty($params['ftp_password']) || empty($params['ftp_username']) || empty($params['ftp_port'])) {
                return false;
            }
            
            $filesystem = new Remote(FTP::getRemoteClient($params));
            if (! $filesystem->getAdapter()->listContents()) {
                return false;
            }
            
            $filesystem->getAdapter()->disconnect();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

$rule = new Connect;
\JaegerApp\Validate::addrule($rule->getName(), array($rule, 'validate'), $rule->getErrorMessage());
