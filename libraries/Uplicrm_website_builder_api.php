<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Uplicrm_website_builder_api
{
    private $CI;
    private $directadmin_host;
    private $directadmin_port;
    private $directadmin_username;
    private $directadmin_password;
    private $package_default;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('upli_website_builder_lib');
        // Load DirectAdmin API settings from config or database
        $this->directadmin_host = get_option('uplicrm_wb_directadmin_ip');
        $this->directadmin_port = get_option('uplicrm_wb_directadmin_port');
        $this->directadmin_username = get_option('uplicrm_wb_directadmin_username');
        $this->directadmin_password = get_option('uplicrm_wb_directadmin_password');
        $this->package_default = get_option('uplicrm_wb_default_plan');
        // Add settings in Perfex CRM Setup -> Settings -> Integration for these options

    }

    private function send_command($command, $data = [])
    {
        $url = "http://{$this->directadmin_host}:{$this->directadmin_port}/{$command}";

        $data['username'] = $this->directadmin_username;
        $data['password'] = $this->directadmin_password;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PORT, $this->directadmin_port);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            return false;
        } else {
            parse_str($response, $result);
            if (isset($result['error']) && $result['error'] === '0') {
                return $result;
            }
            return false;
        }
    }

    // Example DirectAdmin API functions

    public function create_user($username, $email, $password)
    {
        $command = 'CMD_API_ACCOUNT_USER';
        $data = [
            'action' => 'create',
            'add' => 'Submit',
            'username' => $username,
            'email' => $email,
            'passwd' => $password,
            'passwd2' => $password,
            'domain' => $username . '.' . get_option('uplicrm_wb_default_domain'),
            'package' => $this->package_default,
            'ip' => get_option('uplicrm_wb_directadmin_ip'),
            'notify' => 'no'
        ];

        return $this->send_command($command, $data);
    }

    public function create_subdomain($username, $subdomain)
    {
        $command = 'CMD_API_SUBDOMAIN';
        $data = [
            'action' => 'create',
            'domain' => get_option('uplicrm_wb_default_domain'),
            'subdomain' => $subdomain,
            'user' => $username
        ];

        return $this->send_command($command, $data);
    }

    public function create_database($username, $db_name)
    {
        $command = 'CMD_API_DB';
        $data = [
            'action' => 'create',
            'domain' => get_option('uplicrm_wb_default_domain'),
            'name' => $db_name,
            'user' => $db_name,
            'passwd' => $this->upli_website_builder_lib->generate_strong_password(),
            'passwd2' => $this->upli_website_builder_lib->generate_strong_password(),
        ];

        return $this->send_command($command, $data);
    }

    public function add_letsencrypt_ssl($username, $domain)
    {
        $command = 'CMD_API_SSL';
        $data = [
            'action' => 'save',
            'type' => 'create',
            'domain' => $domain,
            'name' => $domain,
            'email' => 'admin@' . get_option('uplicrm_wb_default_domain'),
            'encryption' => 'sha256',
            'keysize' => '2048',
            'cacert' => 'yes',
            'private' => 'yes',
            'request' => 'letsencrypt'
        ];

        return $this->send_command($command, $data);
    }

    public function park_domain($username, $domain)
    {
        $command = 'CMD_API_DOMAIN';
        $data = [
            'action' => 'add',
            'domain' => $domain,
            'alias' => $domain,
            'type' => 'alias'
        ];

        return $this->send_command($command, $data);
    }

    public function get_user_document_root($username, $domain) {
        // Example implementation (replace with your actual DirectAdmin API call):
        $command = "CMD_API_SHOW_USER_CONFIG"; // Replace with the correct DirectAdmin command
        $data = [
            'user' => $username,
            'domain' => $domain,
        ];

        $response = $this->send_command($command, $data);

        if ($response && $response['docroot']) { // Adapt to the structure of your API response
            return $response['docroot']; 
        } else {
            return false; // or handle error appropriately
        }
    }

    public function test_connection($host = null, $port = null, $username = null, $password = null)
    {
        $testHost = $host ?? $this->directadmin_host;
        $testPort = $port ?? $this->directadmin_port;
        $testUsername = $username ?? $this->directadmin_username;
        $testPassword = $password ?? $this->directadmin_password;

        $command = 'CMD_API_SHOW_USER_CONFIG'; // Or any other simple DirectAdmin command
        $data = [];

        $url = "http://{$testHost}:{$testPort}/{$command}";
        $auth = base64_encode($testUsername . ':' . $testPassword);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $auth]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $error = curl_error($ch);

        curl_close($ch);
        if ($error) {
            return false;
        } else {
            parse_str($response, $result);
            
            if (isset($result['account']) && $result['account'] === 'ON') {
                return true;
            }
            return false;
        }

    }
}