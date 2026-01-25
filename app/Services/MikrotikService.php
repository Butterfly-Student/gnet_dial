<?php
namespace Services;

// MikrotikService.php - Refactored from MikrotikAPI.php
require_once BASE_PATH . '/package/routeros_api.php';

class MikrotikService
{
  private $api;
  private $host;
  private $port;
  private $username;
  private $password;
  private $connected = false;

  public function __construct($host, $port, $username, $password)
  {
    $this->host = $host;
    $this->port = $port;
    $this->username = $username;
    $this->password = $password;

    try {
      $this->api = new \RouterosAPI();
      $this->api->port = $port;

      // Attempt to connect
      if (!$this->api->connect($host, $username, $password)) {
        throw new \Exception("Koneksi ke MikroTik gagal");
      }
      $this->connected = true;
    } catch (\Exception $e) {
      throw new \Exception("Koneksi ke MikroTik gagal: " . $e->getMessage());
    }
  }

  public function testConnection()
  {
    try {
      if (!$this->connected) {
        return false;
      }

      // Test connection by getting system identity
      $response = $this->api->comm('/system/identity/print');
      return !empty($response);
    } catch (\Exception $e) {
      return false;
    }
  }

  public function getPPPSecrets()
  {
    try {
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      $secrets = $this->api->comm('/ppp/secret/print');

      // Ensure we return an array even if empty
      if (!is_array($secrets)) {
        return [];
      }

      return $secrets;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil data PPP secrets: " . $e->getMessage());
    }
  }

  public function getPPPActive()
  {
    try {
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      $active = $this->api->comm('/ppp/active/print');
      $secrets = $this->api->comm('/ppp/secret/print');

      // Ensure we return an array even if empty
      if (!is_array($active)) {
        return [];
      }

      // Create a map of secrets by name for quick lookup
      $secretsMap = [];
      if (is_array($secrets)) {
        foreach ($secrets as $secret) {
          $name = $secret['name'] ?? '';
          if ($name) {
            $secretsMap[$name] = $secret;
          }
        }
      }

      // Enhance active sessions with profile information from secrets
      $enhancedActive = [];
      foreach ($active as $session) {
        $username = $session['name'] ?? '';
        
        // Add profile from secrets if available
        if (isset($secretsMap[$username])) {
          $session['profile'] = $secretsMap[$username]['profile'] ?? 'default';
        } else {
          $session['profile'] = 'default';
        }
        
        $enhancedActive[] = $session;
      }

      return $enhancedActive;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil data PPP active: " . $e->getMessage());
    }
  }

  public function getNonActiveSecrets()
  {
    try {
      $secrets = $this->getPPPSecrets();
      $active = $this->getPPPActive();

      // Extract active usernames
      $activeUsers = [];
      foreach ($active as $act) {
        if (isset($act['name'])) {
          $activeUsers[] = $act['name'];
        }
      }

      // Filter inactive secrets
      $inactive = [];
      foreach ($secrets as $secret) {
        if (isset($secret['name']) && !in_array($secret['name'], $activeUsers)) {
          $inactive[] = $secret;
        }
      }

      return $inactive;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil data PPP inactive: " . $e->getMessage());
    }
  }

  /**
   * Fixed ping method for MikrotikAPI class
   * Replace your existing pingAddress method with this one
   */
  public function pingAddress($address, $count = 1)
  {
    try {
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Validate address
      if (empty($address)) {
        throw new \Exception("Address tidak boleh kosong");
      }

      // Set timeout untuk mencegah hanging
      set_time_limit(30);

      // Persiapkan parameter ping
      $pingParams = [
        'address' => $address,
        'count' => (string)$count,
        'interval' => '1'
      ];

      // Jalankan ping command
      $response = $this->api->comm('/ping', $pingParams);

      // Log untuk debugging (akan dihapus di production)
      error_log("MikroTik ping response for {$address}: " . json_encode($response));

      // Process response
      return $this->processPingResponse($response, $address);
    } catch (\Exception $e) {
      error_log("MikroTik ping error: " . $e->getMessage());

      // Return error format yang konsisten
      return [
        'status' => 'error',
        'time' => 'error',
        'size' => 0,
        'ttl' => 0,
        'host' => $address,
        'error' => $e->getMessage()
      ];
    }
  }

  /**
   * Process ping response from MikroTik
   */
  private function processPingResponse($response, $address)
  {
    try {
      // Handle different response formats
      if (empty($response)) {
        return [
          'status' => 'timeout',
          'time' => 'timeout',
          'size' => 0,
          'ttl' => 0,
          'host' => $address
        ];
      }

      // If response is an array with multiple results
      if (is_array($response)) {
        // Check for !trap (errors like "not enough permissions")
        if (isset($response['!trap'])) {
          $trapMsg = $response['!trap'][0]['message'] ?? 'Unknown MikroTik error';
          return [
            'status' => 'error',
            'time' => 'error',
            'size' => 0,
            'ttl' => 0,
            'host' => $address,
            'error' => $trapMsg,
            'raw_response' => $response
          ];
        }

        // Look for successful ping result
        foreach ($response as $result) {
          if (is_array($result)) {
            // Check for successful ping indicators
            if (isset($result['time']) || isset($result['sent']) || isset($result['received'])) {
              return [
                'status' => 'success',
                'time' => isset($result['time']) ? $result['time'] : (isset($result['avg-rtt']) ? $result['avg-rtt'] : 'N/A'),
                'size' => isset($result['size']) ? $result['size'] : 56,
                'ttl' => isset($result['ttl']) ? $result['ttl'] : 'N/A',
                'host' => $address,
                'sent' => isset($result['sent']) ? $result['sent'] : 1,
                'received' => isset($result['received']) ? $result['received'] : 1,
                'packet-loss' => isset($result['packet-loss']) ? $result['packet-loss'] : '0%',
                'raw_response' => $result
              ];
            }

            // Check for timeout or error
            if (isset($result['timeout']) || isset($result['status']) && $result['status'] == 'timeout') {
              return [
                'status' => 'timeout',
                'time' => 'timeout',
                'size' => 0,
                'ttl' => 0,
                'host' => $address,
                'raw_response' => $result
              ];
            }
          }
        }

        // If we get here, try to extract any meaningful data
        $firstResult = $response[0] ?? [];
        if (!empty($firstResult)) {
          return [
            'status' => 'success',
            'time' => 'N/A',
            'size' => 56,
            'ttl' => 'N/A',
            'host' => $address,
            'raw_response' => $firstResult
          ];
        }
      }

      // Fallback for unexpected response format
      return [
        'status' => 'unknown',
        'time' => 'unknown',
        'size' => 56,
        'ttl' => 'N/A',
        'host' => $address,
        'raw_response' => $response
      ];
    } catch (\Exception $e) {
      error_log("Error processing ping response: " . $e->getMessage());

      return [
        'status' => 'error',
        'time' => 'error',
        'size' => 0,
        'ttl' => 0,
        'host' => $address,
        'error' => $e->getMessage()
      ];
    }
  }


  public function interfaceTrafic($interfaceName)
  {
    try {
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Get interface traffic statistics
      $response = $this->api->comm('/interface/print', [
        '?name' => $interfaceName,
      ]);

      return $response;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil data monitoring interface: " . $e->getMessage());
    }
  }

  public function getAllInterfaceType($interfaceType)
  {
    try {
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Get interface traffic statistics
      $response = $this->api->comm('/interface/print', [
        '?type' => $interfaceType,
      ]);

      return $response;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil data monitoring interface: " . $e->getMessage());
    }
  }

  /**
   * Get all interfaces with traffic data (Ethernet only)
   */
  public function getInterfacesWithTraffic()
  {
      try {
          if (!$this->connected) {
              throw new \Exception("Tidak terhubung ke MikroTik");
          }

          // Get all ether interfaces first
          $interfaces = $this->api->comm('/interface/print', [
              '?type' => 'ether'
          ]);
          
          if (!is_array($interfaces)) return [];
          
          // Extract names for monitor-traffic
          $names = [];
          foreach ($interfaces as $iface) {
              if (isset($iface['name'])) {
                  $names[] = $iface['name'];
              }
          }
          
          if (empty($names)) return $interfaces;
          
          // Monitor traffic for all interfaces
          // Note: monitor-traffic with multiple interfaces returns an array of stats
          $traffic = $this->api->comm('/interface/monitor-traffic', [
              'interface' => implode(',', $names),
              'once' => ''
          ]);
          
          // Map traffic data back to interfaces
          $trafficMap = [];
          if (is_array($traffic)) {
              foreach ($traffic as $t) {
                  if (isset($t['name'])) {
                      $trafficMap[$t['name']] = $t;
                  }
              }
          }
          
          // Merge
          foreach ($interfaces as &$iface) {
              if (isset($iface['name']) && isset($trafficMap[$iface['name']])) {
                  // Merge traffic stats (rx-bits-per-second, etc)
                  $iface = array_merge($iface, $trafficMap[$iface['name']]);
              }
          }
          
          return $interfaces;
      } catch (\Exception $e) {
          throw new \Exception("Gagal mengambil data interface: " . $e->getMessage());
      }
  }

  public function getSimpleQueues()
  {
      try {
          if (!$this->connected) {
              throw new \Exception("Tidak terhubung ke MikroTik");
          }
          
          // Fetch all queues with stats
          $response = $this->api->comm('/queue/simple/print', [
              'stats' => '' 
          ]);
          return $response;
      } catch (\Exception $e) {
          throw new \Exception("Gagal mengambil data simple queues: " . $e->getMessage());
      }
  }

  public function searchActivePPP($searchTerm)
  {
    try {
      $activeSecrets = $this->getPPPActive();
      $allSecrets = $this->getPPPSecrets();

      $filtered = [];

      // Search in active sessions only
      foreach ($activeSecrets as $active) {
        // Check standard fields plus caller-id (MAC) and address (IP)
        $match = false;
        
        if (isset($active['name']) && stripos($active['name'], $searchTerm) !== false) $match = true;
        else if (isset($active['caller-id']) && stripos($active['caller-id'], $searchTerm) !== false) $match = true;
        else if (isset($active['address']) && stripos($active['address'], $searchTerm) !== false) $match = true;
        // Verify against secret's last-caller-id if available
        else {
           foreach ($allSecrets as $secret) {
             if (isset($secret['name']) && $secret['name'] === $active['name']) {
                if (isset($secret['last-caller-id']) && stripos($secret['last-caller-id'], $searchTerm) !== false) {
                    $match = true;
                }
                break;
             }
           }
        }

        if ($match) {
          // Find the corresponding secret info
          $secretInfo = null;
          foreach ($allSecrets as $secret) {
            if (isset($secret['name']) && $secret['name'] === $active['name']) {
              $secretInfo = $secret;
              break;
            }
          }

          // Return only required fields
          $result = [
            'name' => $active['name'],
            'profile' => isset($secretInfo['profile']) ? $secretInfo['profile'] : (isset($active['profile']) ? $active['profile'] : 'none'),
            'service' => isset($secretInfo['service']) ? $secretInfo['service'] : (isset($active['service']) ? $active['service'] : ''),
            'status' => 'active',
            'address' => isset($active['address']) ? $active['address'] : '',
            'uptime' => isset($active['uptime']) ? $active['uptime'] : '',
            'address' => isset($active['address']) ? $active['address'] : '',
            'uptime' => isset($active['uptime']) ? $active['uptime'] : '',
            'caller-id' => isset($active['caller-id']) ? $active['caller-id'] : '',
            'last-caller-id' => isset($secretInfo['last-caller-id']) ? $secretInfo['last-caller-id'] : '',
            'last-caller-id' => isset($secretInfo['last-caller-id']) ? $secretInfo['last-caller-id'] : ''
          ];

          $filtered[] = $result;
        }
      }

      return $filtered;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mencari data PPP active: " . $e->getMessage());
    }
  }

  public function searchNonActivePPP($searchTerm)
  {
    try {
      $inactiveSecrets = $this->getNonActiveSecrets();
      $filtered = [];

      // Search in inactive secrets only
      foreach ($inactiveSecrets as $secret) {
        $match = false;
        if (isset($secret['name']) && stripos($secret['name'], $searchTerm) !== false) $match = true;
        else if (isset($secret['comment']) && stripos($secret['comment'], $searchTerm) !== false) $match = true;
        else if (isset($secret['caller-id']) && stripos($secret['caller-id'], $searchTerm) !== false) $match = true;

        if ($match) {
          // Return only required fields
          $result = [
            'name' => $secret['name'],
            'profile' => isset($secret['profile']) ? $secret['profile'] : 'none',
            'service' => isset($secret['service']) ? $secret['service'] : '',
            'status' => 'inactive',
            'comment' => isset($secret['comment']) ? $secret['comment'] : '',
            'caller-id' => isset($secret['caller-id']) ? $secret['caller-id'] : '',
            'last-caller-id' => isset($secret['last-caller-id']) ? $secret['last-caller-id'] : '',
            'disabled' => isset($secret['disabled']) ? $secret['disabled'] : 'false'
          ];

          $filtered[] = $result;
        }
      }

      return $filtered;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mencari data PPP non-active: " . $e->getMessage());
    }
  }

  public function searchSecrets($searchTerm)
  {
    try {
      // Get both inactive secrets and active sessions
      $inactiveSecrets = $this->getNonActiveSecrets();
      $activeSecrets = $this->getPPPActive();
      $allSecrets = $this->getPPPSecrets();

      $activeResults = [];
      $nonActiveResults = [];

      // Search in inactive secrets
      foreach ($inactiveSecrets as $secret) {
        $match = false;
        if (isset($secret['name']) && stripos($secret['name'], $searchTerm) !== false) $match = true;
        else if (isset($secret['comment']) && stripos($secret['comment'], $searchTerm) !== false) $match = true;
        if (isset($secret['name']) && stripos($secret['name'], $searchTerm) !== false) $match = true;
        else if (isset($secret['comment']) && stripos($secret['comment'], $searchTerm) !== false) $match = true;
        else if (isset($secret['caller-id']) && stripos($secret['caller-id'], $searchTerm) !== false) $match = true;
        else if (isset($secret['last-caller-id']) && stripos($secret['last-caller-id'], $searchTerm) !== false) $match = true;

        if ($match) {
          // Return all fields from secret and add status
          $result = $secret;
          $result['status'] = 'inactive';
          $nonActiveResults[] = $result;
        }
      }

      // Search in active sessions
      foreach ($activeSecrets as $active) {
        $match = false;
        if (isset($active['name']) && stripos($active['name'], $searchTerm) !== false) $match = true;
        else if (isset($active['caller-id']) && stripos($active['caller-id'], $searchTerm) !== false) $match = true;
        if (isset($active['name']) && stripos($active['name'], $searchTerm) !== false) $match = true;
        else if (isset($active['caller-id']) && stripos($active['caller-id'], $searchTerm) !== false) $match = true;
        else if (isset($active['address']) && stripos($active['address'], $searchTerm) !== false) $match = true;

        if ($match) {
          // Find the corresponding secret info
          $secretInfo = null;
          foreach ($allSecrets as $secret) {
            if (isset($secret['name']) && $secret['name'] === $active['name']) {
              $secretInfo = $secret;
              break;
            }
          }
          
          // Re-check detailed search against secret info if basic match failed but we are here? 
          // Actually, if match is false, we should check secret info for last-caller-id
          if (!$match && $secretInfo) {
             if (isset($secretInfo['last-caller-id']) && stripos($secretInfo['last-caller-id'], $searchTerm) !== false) {
                 $match = true;
             }
          }
          
          if ($match) {
             // Merge active session data with secret info
             $result = $active; 

             // Add/override with secret info if available
             if ($secretInfo) {
               $result = array_merge($secretInfo, $result);
             }

             // Ensure status is set to active
             $result['status'] = 'active';
             $activeResults[] = $result;
          }
        }
      }

      // Return results in the requested format
      return [
        'active' => $activeResults,
        'non_active' => $nonActiveResults
      ];
    } catch (\Exception $e) {
      throw new \Exception("Gagal mencari data PPP secrets: " . $e->getMessage());
    }
  }

  // Add new method for searching all secrets (if needed)
  public function searchAllSecrets($searchTerm)
  {
    try {
      $secrets = $this->getPPPSecrets();
      $filtered = [];

      foreach ($secrets as $secret) {
        $match = false;
        if (isset($secret['name']) && stripos($secret['name'], $searchTerm) !== false) $match = true;
        else if (isset($secret['comment']) && stripos($secret['comment'], $searchTerm) !== false) $match = true;
        else if (isset($secret['caller-id']) && stripos($secret['caller-id'], $searchTerm) !== false) $match = true;

        if ($match) {
          $filtered[] = $secret;
        }
      }

      return $filtered;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mencari data PPP secrets: " . $e->getMessage());
    }
  }

  /**
   * Disconnect PPP active session by username
   * @param string $username The username to disconnect
   * @return bool True if successful, false otherwise
   */
  public function disconnectPppActive($username)
  {
    try {
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      if (empty($username)) {
        throw new \Exception("Username tidak boleh kosong");
      }

      // Get active PPP sessions
      $activeSessions = $this->getPPPActive();

      // Find the session ID for the username
      $sessionId = null;
      foreach ($activeSessions as $session) {
        if (isset($session['name']) && $session['name'] === $username) {
          $sessionId = isset($session['.id']) ? $session['.id'] : null;
          break;
        }
      }

      if ($sessionId === null) {
        throw new \Exception("User '$username' tidak ditemukan dalam sesi aktif");
      }

      // Disconnect the user
      $response = $this->api->comm('/ppp/active/remove', [
        '.id' => $sessionId
      ]);

      // Check if the command was successful
      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal memutuskan koneksi user '$username': " . $e->getMessage());
    }
  }

  /**
   * Disconnect multiple PPP active sessions by usernames
   * @param array $usernames Array of usernames to disconnect
   * @return array Results with success/failure for each username
   */
  public function disconnectMultiplePppActive($usernames)
  {
    $results = [];

    if (!is_array($usernames)) {
      throw new \Exception("Parameter usernames harus berupa array");
    }

    foreach ($usernames as $username) {
      try {
        $this->disconnectPppActive($username);
        $results[$username] = [
          'success' => true,
          'message' => 'Berhasil memutuskan koneksi'
        ];
      } catch (\Exception $e) {
        $results[$username] = [
          'success' => false,
          'message' => $e->getMessage()
        ];
      }
    }

    return $results;
  }

  /**
   * Get system logs from MikroTik
   * @param int $limit Number of logs to fetch
   * @param string $searchTerm Optional search term to filter logs
   * @return array Array of logs
   */
  public function getLogs($limit = 100, $searchTerm = '')
  {
    try {
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      $params = [];
      
      // If searching, we fetch more logs internally to find matches
      $fetchLimit = !empty($searchTerm) ? 1000 : $limit;

      $logs = $this->api->comm('/log/print');

      if (!is_array($logs)) {
        return [];
      }

      // Filter in PHP if search term is provided
      if (!empty($searchTerm)) {
        $logs = array_filter($logs, function($log) use ($searchTerm) {
          $message = $log['message'] ?? '';
          $topics = $log['topics'] ?? '';
          return stripos($message, $searchTerm) !== false || stripos($topics, $searchTerm) !== false;
        });
      }

      // Reverse logs to show newest first and limit
      $logs = array_reverse($logs);
      
      if ($limit > 0) {
        $logs = array_slice($logs, 0, $limit);
      }

      return array_values($logs); // Reset array keys after filtering
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil data log MikroTik: " . $e->getMessage());
    }
  }

  /**
   * Update PPP Secret Profile
   * @param string $username Username to update
   * @param string $profile New profile name
   * @return bool True if successful
   */
  public function updatePppSecretProfile($username, $profile)
  {
    try {
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Find the secret first
      $secrets = $this->api->comm('/ppp/secret/print', [
        '?name' => $username
      ]);

      if (empty($secrets)) {
        throw new \Exception("User PPP '$username' tidak ditemukan");
      }

      $secretId = $secrets[0]['.id'];

      // Update the profile
      $this->api->comm('/ppp/secret/set', [
        '.id' => $secretId,
        'profile' => $profile
      ]);

      // Checks if user is active, if so, disconnect to apply changes immediately
      $activeSessions = $this->api->comm('/ppp/active/print', [
        '?name' => $username
      ]);

      if (!empty($activeSessions)) {
        foreach ($activeSessions as $session) {
          $this->api->comm('/ppp/active/remove', [
            '.id' => $session['.id']
          ]);
        }
      }

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengubah profile user '$username': " . $e->getMessage());
    }
  }

  public function __destruct()
  {
    if ($this->api && $this->connected) {
      $this->api->disconnect();
    }
  }
}
