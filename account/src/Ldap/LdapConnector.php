<?php

namespace Trax\Account\Ldap;

use Illuminate\Support\Facades\Log;

class LdapConnector
{
    /**
     * LDAP config.
     */
    protected $config;

    /**
     * LDAP connection.
     */
    protected $ldapconnection;


    /**
     * Try to login with LDAP.
     */
    public function validateCredentials($user, $credentials)
    {
        // Username & password
        $username = $user->username;
        $password = $credentials['password'];

        try {
            // Check a few thinks
            $this->setConfig();
            
            // LDAP connection
            $this->ldapconnection = $this->connect();

            // Find user to get its DN
            $user_dn = $this->userdn($this->ldapconnection, $username);
            if (!$user_dn) $this->error('LDAP user not found!');

            // Try to bind with current username and password
            $login = @ldap_bind($this->ldapconnection, $user_dn, $password);
            if (!$login) $this->error('LDAP authentication failed!');

            // Passed
            $this->close();
            return true;

        } catch(\Exception $e) {

            // Failed
            $this->close();
            return false;
        }
    }

    /**
     * Set and check LDAP config.
     */
    protected function setConfig()
    {
        // Check plugin existance
        if (!function_exists('ldap_bind')) $this->error('PHP LDAP extension not installed!');

        // Get config
        $this->config = (object)config('trax-account.ldap.ad');

        // Check host
        if (empty($this->config->host)) $this->error('LDAP host not defined!');

        // Check username
        if (empty($this->config->username)) $this->error('LDAP username not defined!');

        // Check password
        if (empty($this->config->password)) $this->error('LDAP password not defined!');

        // Check version
        if (empty($this->config->version)) $this->error('LDAP version not defined!');
        $this->config->version = intval($this->config->version);
        if (!$this->config->version || $this->config->version > 3) $this->error('LDAP version not valid!');

        // Check opt_referrals
        $this->config->opt_referrals = intval($this->config->opt_referrals);

        // Check opt_deref
        $this->config->opt_deref = intval($this->config->opt_deref);
        if ($this->config->opt_deref > 3) $this->error('LDAP opt_deref not valid!');

        // Check contexts
        if (empty($this->config->contexts)) $this->error('LDAP contexts not defined!');

        // Check object class
        if (empty($this->config->object_class)) $this->error('LDAP objectClass not defined!');
        $this->config->object_class = '(objectClass=' . $this->config->object_class . ')';

        // Check user attribute
        if (empty($this->config->user_attribute)) $this->error('LDAP user attribute not defined!');
    }

    /**
     * Try to connect to the LDAP.
     */
    protected function connect()
    {
        // Connect: result is always true
        $connresult = ldap_connect($this->config->host);
        
        // Set options
        ldap_set_option($connresult, LDAP_OPT_PROTOCOL_VERSION, $this->config->version);
        ldap_set_option($connresult, LDAP_OPT_REFERRALS, $this->config->opt_referrals);
        if (!empty($this->config->opt_deref)) {
            ldap_set_option($connresult, LDAP_OPT_DEREF, $this->config->opt_deref);
        }

        // Start TLS
        if ($this->config->tls && (!ldap_start_tls($connresult))) {
            $this->error('LDAP start TLS failed!');
        }

        // Auth
        $bindresult = @ldap_bind($connresult, $this->config->username, $this->config->password);
        if ($bindresult) return $connresult;

        // Error
        $this->error('LDAP connection failed!');
    }

    /**
     * Try to get the user DN.
     */
    protected function userdn($ldapconnection, $username)
    {
        // Default return value
        $ldap_user_dn = false;

        // Get all contexts and look for first matching user
        $contexts = explode(';', $this->config->contexts);
        foreach ($contexts as $context) {

            // Empty context
            $context = trim($context);
            if (empty($context)) continue;

            // Try to bind
            $ldap_result = @ldap_search(
                $ldapconnection,
                $context,
                '(&' . $this->config->object_class . '(' . $this->config->user_attribute . '=' . $this->escape($username) . '))',
                array($this->config->user_attribute)
            );

            // Not found in this context
            if (!$ldap_result) continue;

            // Found
            $entry = ldap_first_entry($ldapconnection, $ldap_result);
            if ($entry) {
                $ldap_user_dn = ldap_get_dn($ldapconnection, $entry);
                break;
            }
        }
        return $ldap_user_dn;
    }

    /**
     * Quote control characters in texts used in LDAP filters - see RFC 4515/2254
     */
    protected function escape($text)
    {
        $text = str_replace('\\', '\\5c', $text);
        $text = str_replace(
            array('*', '(', ')', "\0"),
            array('\\2a', '\\28', '\\29', '\\00'),
            $text
        );
        return $text;
    }

    /**
     * Error.
     */
    protected function error($message)
    {
        $this->close();
        Log::debug($message);
        throw new \Exception($message);
    }

    /**
     * Close LDAP connection if it exists.
     */
    protected function close()
    {
        if (isset($this->ldapconnection)) {
            @ldap_close($this->ldapconnection);
            unset($this->ldapconnection);
        }
    }

}
