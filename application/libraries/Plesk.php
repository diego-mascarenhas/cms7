<?php defined('BASEPATH') or exit('No direct script access allowed');

class Plesk {
    
    private $host;
    private $username;
    private $password;
    private $port = 8443;
    private $protocol = 'https';
    private $CI;
    public $debug = false;
    public $last_request = '';
    public $last_response = '';
    
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
        $this->last_request = $request;
        
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
        $this->last_response = $response;
        
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return array('error' => $error);
        }
        
        if ($this->debug) {
            echo "<h3>Request</h3>";
            echo "<pre>" . htmlspecialchars($request) . "</pre>";
            echo "<h3>Response</h3>";
            echo "<pre>" . htmlspecialchars($response) . "</pre>";
        }
        
        // Parse XML response to array
        $xml = simplexml_load_string($response);
        $json = json_encode($xml);
        $result = json_decode($json, true);
        
        return $result;
    }
    
    /**
     * Get the raw XML request and response from the last API call
     * 
     * @return array Last request and response
     */
    public function getLastRawData() 
    {
        return array(
            'request' => $this->last_request,
            'response' => $this->last_response
        );
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
        // Corregido según la documentación de Plesk API
        $request = '<?xml version="1.0" encoding="UTF-8"?>
        <packet>
            <mail>
                <get_info>
                    <filter>
                        <site-name>' . htmlspecialchars($domain) . '</site-name>
                    </filter>
                    <mailbox/>
                </get_info>
            </mail>
        </packet>';
        
        $response = $this->makeRequest($request);
        
        if (isset($response['mail']['get_info']['result'])) {
            $emails = array();
            $results = $response['mail']['get_info']['result'];
            
            // Handle single or multiple results
            if (isset($results['status'])) {
                // Single result
                if (isset($results['mailbox']) && !empty($results['mailbox'])) {
                    $emails[] = $this->formatEmailData($results);
                }
            } else {
                // Multiple results
                foreach ($results as $result) {
                    if (isset($result['mailbox']) && !empty($result['mailbox'])) {
                        $emails[] = $this->formatEmailData($result);
                    }
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
        // Plesk API ya proporciona información de uso de disco en la llamada listpops
        return $this->listpops($domain);
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
        
        // Corregido según la documentación de Plesk API
        $request = '<?xml version="1.0" encoding="UTF-8"?>
        <packet>
            <mail>
                <update>
                    <filter>
                        <site-name>' . htmlspecialchars($domain) . '</site-name>
                        <mailname>' . htmlspecialchars($email_user) . '</mailname>
                    </filter>
                    <mailbox>
                        <password>' . htmlspecialchars($data['password']) . '</password>
                    </mailbox>
                </update>
            </mail>
        </packet>';
        
        return $this->makeRequest($request);
    }
    
    /**
     * Get server stats for a domain
     *
     * @param string $domain Domain name
     * @return array Stats data
     */
    public function stats($domain)
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
        <packet>
            <webspace>
                <get>
                    <filter>
                        <name>' . htmlspecialchars($domain) . '</name>
                    </filter>
                    <dataset>
                        <stat/>
                    </dataset>
                </get>
            </webspace>
        </packet>';
        
        $response = $this->makeRequest($request);
        
        if (isset($response['webspace']['get']['result']['data']['stat'])) {
            return $response['webspace']['get']['result']['data']['stat'];
        }
        
        return array();
    }
    
    /**
     * Get disk usage
     *
     * @param string $domain Domain name
     * @return array Disk usage data
     */
    public function diskusage($domain)
    {
        return $this->stats($domain);
    }
    
    /**
     * Get bandwidth usage
     *
     * @param string $domain Domain name
     * @return array Bandwidth usage data
     */
    public function bandwidthusage($domain)
    {
        return $this->stats($domain);
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
            $account['suspended'] = (isset($info['status']) && $info['status'] == '0') ? 0 : 1;
            $account['suspendreason'] = isset($info['status']) && $info['status'] != '0' ? 'Suspended' : '';
        }
        
        if (isset($data['data']['stat'])) {
            $stat = $data['data']['stat'];
            $account['diskused'] = isset($stat['real_size']) ? round($stat['real_size'] / 1024 / 1024) : 0; // Convert to MB
            $account['bandwidth'] = isset($stat['traffic']) ? round($stat['traffic'] / 1024 / 1024) : 0; // Convert to MB
        }
        
        if (isset($data['data']['limits'])) {
            $limits = $data['data']['limits'];
            $account['disklimit'] = isset($limits['disk_space']) ? round($limits['disk_space'] / 1024 / 1024) : 0; // Convert to MB
            $account['bwlimit'] = isset($limits['max_traffic']) ? round($limits['max_traffic'] / 1024 / 1024) : 0; // Convert to MB
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
        
        if (isset($data['mailbox'])) {
            foreach ($data['mailbox'] as $mailbox) {
                $name = $mailbox['name'];
                $domain = $data['name'];
                
                $email[] = array(
                    'account' => $name,
                    'domain' => $domain,
                    'email' => $name . '@' . $domain,
                    'suspended' => isset($mailbox['active']) && $mailbox['active'] == 'true' ? 0 : 1,
                    'diskused' => isset($mailbox['usage']) ? round($mailbox['usage'] / 1024 / 1024) : 0 // Convert to MB
                );
            }
        }
        
        return $email;
    }
} 