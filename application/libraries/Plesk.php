<?php defined('BASEPATH') or exit('No direct script access allowed');

class Plesk {
    
    private $host;
    private $username;
    private $password;
    private $port = 8443;
    private $protocol = 'https';
    private $CI;
    
    public function __construct($config = array())
    {
        $this->CI = &get_instance();
        
        if (!empty($config))
        {
            $this->initialize($config);
        }
    }
    
    public function initialize($config = array())
    {
        foreach ($config as $key => $val)
        {
            if (isset($this->$key))
            {
                $this->$key = $val;
            }
        }
        
        return $this;
    }
    
    /**
     * Make an API request to Plesk
     *
     * @param string $request XML request
     * @return array Response data
     */
    private function makeRequest($request)
    {
        $url = $this->protocol . '://' . $this->host . ':' . $this->port . '/enterprise/control/agent.php';
        
        $headers = array(
            'HTTP_AUTH_LOGIN: ' . $this->username,
            'HTTP_AUTH_PASSWD: ' . $this->password,
            'Content-Type: text/xml',
            'Content-Length: ' . strlen($request)
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return array('error' => $error);
        }
        
        // Parse XML response to array
        $xml = simplexml_load_string($response);
        $result = json_decode(json_encode($xml), true);
        
        return $result;
    }
    
    /**
     * Get list of all hosting accounts
     *
     * @return array Account data
     */
    public function accounts()
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
        <packet>
            <webspace>
                <get>
                    <filter/>
                    <dataset>
                        <gen_info/>
                        <hosting/>
                        <stat/>
                        <limits/>
                    </dataset>
                </get>
            </webspace>
        </packet>';
        
        $response = $this->makeRequest($request);
        
        if (isset($response['webspace']['get']['result'])) {
            $accounts = array();
            $results = $response['webspace']['get']['result'];
            
            // Handle single or multiple results
            if (isset($results['status'])) {
                // Single result
                $accounts[] = $this->formatAccountData($results);
            } else {
                // Multiple results
                foreach ($results as $result) {
                    $accounts[] = $this->formatAccountData($result);
                }
            }
            
            return $accounts;
        }
        
        return array();
    }
    
    /**
     * Get account summary
     *
     * @param string $domain Domain name
     * @return array Account summary data
     */
    public function accountsummary($domain)
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
        <packet>
            <webspace>
                <get>
                    <filter>
                        <name>' . htmlspecialchars($domain) . '</name>
                    </filter>
                    <dataset>
                        <gen_info/>
                        <hosting/>
                        <stat/>
                        <limits/>
                    </dataset>
                </get>
            </webspace>
        </packet>';
        
        $response = $this->makeRequest($request);
        
        if (isset($response['webspace']['get']['result'])) {
            return $this->formatAccountData($response['webspace']['get']['result']);
        }
        
        return array();
    }
    
    /**
     * List email accounts for a domain
     *
     * @param string $domain Domain name
     * @return array Email accounts
     */
    public function listpops($domain)
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
        <packet>
            <mail>
                <get_mail_account>
                    <filter>
                        <site-name>' . htmlspecialchars($domain) . '</site-name>
                    </filter>
                    <dataset>
                        <gen_info/>
                    </dataset>
                </get_mail_account>
            </mail>
        </packet>';
        
        $response = $this->makeRequest($request);
        
        if (isset($response['mail']['get_mail_account']['result'])) {
            $emails = array();
            $results = $response['mail']['get_mail_account']['result'];
            
            // Handle single or multiple results
            if (isset($results['status'])) {
                // Single result
                $emails[] = $this->formatEmailData($results);
            } else {
                // Multiple results
                foreach ($results as $result) {
                    $emails[] = $this->formatEmailData($result);
                }
            }
            
            return $emails;
        }
        
        return array();
    }
    
    /**
     * List email accounts with disk usage
     *
     * @param string $domain Domain name
     * @return array Email accounts with disk usage
     */
    public function listpopswithdisk($domain)
    {
        $emails = $this->listpops($domain);
        
        if (!empty($emails)) {
            foreach ($emails as &$email) {
                // Add disk usage info by getting mailbox size
                $request = '<?xml version="1.0" encoding="UTF-8"?>
                <packet>
                    <mail>
                        <get_mailbox_size>
                            <filter>
                                <site-name>' . htmlspecialchars($domain) . '</site-name>
                                <mailname>' . htmlspecialchars($email['account']) . '</mailname>
                            </filter>
                        </get_mailbox_size>
                    </mail>
                </packet>';
                
                $response = $this->makeRequest($request);
                
                if (isset($response['mail']['get_mailbox_size']['result']['mailbox'])) {
                    $email['diskused'] = $response['mail']['get_mailbox_size']['result']['mailbox']['size'] / 1024 / 1024; // Convert to MB
                } else {
                    $email['diskused'] = 0;
                }
            }
        }
        
        return $emails;
    }
    
    /**
     * Change account password
     *
     * @param string $domain Domain name
     * @param array $data Password data
     * @return array Response
     */
    public function passwd($domain, $data)
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
        <packet>
            <webspace>
                <set>
                    <filter>
                        <name>' . htmlspecialchars($domain) . '</name>
                    </filter>
                    <values>
                        <gen_setup>
                            <password>' . htmlspecialchars($data['password']) . '</password>
                        </gen_setup>
                    </values>
                </set>
            </webspace>
        </packet>';
        
        return $this->makeRequest($request);
    }
    
    /**
     * Change email account password
     *
     * @param string $domain Domain name
     * @param array $data Email data with email and password
     * @return array Response
     */
    public function passwdpop($domain, $data)
    {
        list($email_user, $email_domain) = explode('@', $data['email']);
        
        $request = '<?xml version="1.0" encoding="UTF-8"?>
        <packet>
            <mail>
                <update>
                    <filter>
                        <site-name>' . htmlspecialchars($domain) . '</site-name>
                        <mailname>' . htmlspecialchars($email_user) . '</mailname>
                    </filter>
                    <values>
                        <password>' . htmlspecialchars($data['password']) . '</password>
                    </values>
                </update>
            </mail>
        </packet>';
        
        return $this->makeRequest($request);
    }
    
    /**
     * Format account data for consistency
     *
     * @param array $data Raw account data
     * @return array Formatted account data
     */
    private function formatAccountData($data)
    {
        $account = array();
        
        if (isset($data['data']['gen_info'])) {
            $info = $data['data']['gen_info'];
            $account['user'] = $info['name'];
            $account['domain'] = $info['name'];
            $account['ip'] = isset($info['dns_ip_address']) ? $info['dns_ip_address'] : '';
            $account['suspended'] = (isset($info['status']) && $info['status'] == 'active') ? 0 : 1;
            $account['suspendreason'] = isset($info['status']) && $info['status'] != 'active' ? $info['status'] : '';
        }
        
        if (isset($data['data']['stat'])) {
            $stat = $data['data']['stat'];
            $account['diskused'] = isset($stat['disk_space']) ? $stat['disk_space'] / 1024 / 1024 : 0; // Convert to MB
            $account['bandwidth'] = isset($stat['max_traffic']) ? $stat['max_traffic'] / 1024 / 1024 : 0; // Convert to MB
        }
        
        if (isset($data['data']['limits'])) {
            $limits = $data['data']['limits'];
            $account['disklimit'] = isset($limits['disk_space']) ? $limits['disk_space'] / 1024 / 1024 : 0; // Convert to MB
            $account['bwlimit'] = isset($limits['max_traffic']) ? $limits['max_traffic'] / 1024 / 1024 : 0; // Convert to MB
        }
        
        return $account;
    }
    
    /**
     * Format email data for consistency
     *
     * @param array $data Raw email data
     * @return array Formatted email data
     */
    private function formatEmailData($data)
    {
        $email = array();
        
        if (isset($data['data']['gen_info'])) {
            $info = $data['data']['gen_info'];
            $email['account'] = $info['name'];
            $email['domain'] = $info['domain'];
            $email['email'] = $info['name'] . '@' . $info['domain'];
            $email['suspended'] = (isset($info['status']) && $info['status'] == 'active') ? 0 : 1;
        }
        
        return $email;
    }
} 