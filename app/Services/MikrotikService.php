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
  }

  private function connect()
  {
    if ($this->connected) {
      return;
    }

    try {
      $this->api = new \RouterosAPI();
      $this->api->port = $this->port;

      // Set timeout for connection attempt to avoid long hangs
      // Note: RouterosAPI might not support timeout configuration directly in connect,
      // but we wrap this in try-catch.
      if (!$this->api->connect($this->host, $this->username, $this->password)) {
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
      $this->connect();

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
      $this->connect();

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
      $this->connect();

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
      // getPPPSecrets and getPPPActive will handle connection
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
      $this->connect();
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
        'count' => (string) $count,
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
      $this->connect();
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
      $this->connect();
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
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Get all ether interfaces first
      $interfaces = $this->api->comm('/interface/print', [
        '?type' => 'ether'
      ]);

      if (!is_array($interfaces))
        return [];

      // Extract names for monitor-traffic
      $names = [];
      foreach ($interfaces as $iface) {
        if (isset($iface['name'])) {
          $names[] = $iface['name'];
        }
      }

      if (empty($names))
        return $interfaces;

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
      $this->connect();
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

  /**
   * Get queue traffic data by username
   * @param string $username Username to get traffic for
   * @return array|null Queue traffic data or null if not found
   */
  public function getQueueTrafficByUsername($username)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Queue names usually prefixed with '<pppoe-' or just '<'
      // Try with pppoe- prefix first
      $queueName = '<pppoe-' . $username . '>';

      $response = $this->api->comm('/queue/simple/print', [
        '?name' => $queueName,
        'stats' => ''
      ]);

      // If not found with pppoe- prefix, try without prefix
      if (empty($response)) {
        $queueName = '<' . $username . '>';
        $response = $this->api->comm('/queue/simple/print', [
          '?name' => $queueName,
          'stats' => ''
        ]);
      }

      // If still not found, try exact username
      if (empty($response)) {
        $response = $this->api->comm('/queue/simple/print', [
          '?name' => $username,
          'stats' => ''
        ]);
      }

      // Return first result or null
      return !empty($response) && is_array($response) ? $response[0] : null;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil data queue traffic untuk user '$username': " . $e->getMessage());
    }
  }

  public function searchActivePPP($searchTerm)
  {
    try {
      $activeSecrets = $this->getPPPActive();
      $allSecrets = $this->getPPPSecrets();

      // Get queue data for traffic information
      $queues = $this->getSimpleQueues();

      $filtered = [];

      // Search in active sessions only
      foreach ($activeSecrets as $active) {
        // Check standard fields plus caller-id (MAC) and address (IP)
        $match = false;

        if (isset($active['name']) && stripos($active['name'], $searchTerm) !== false)
          $match = true;
        else if (isset($active['caller-id']) && stripos($active['caller-id'], $searchTerm) !== false)
          $match = true;
        else if (isset($active['address']) && stripos($active['address'], $searchTerm) !== false)
          $match = true;
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

          // Find queue data for this user
          $queueData = null;
          $username = $active['name'];
          foreach ($queues as $queue) {
            $queueName = isset($queue['name']) ? $queue['name'] : '';
            // Remove < > characters from queue name
            $queueName = str_replace(['<', '>'], '', $queueName);

            if ($queueName === $username) {
              $queueData = $queue;
              break;
            }
          }

          // Parse queue traffic data
          $trafficData = [
            'rx-rate' => '0',
            'tx-rate' => '0',
            'bytes-up' => '0',
            'bytes-down' => '0',
            'max-limit' => 'Unlimited'
          ];

          if ($queueData) {
            // Parse rate (format: "uploadbps/downloadbps")
            if (isset($queueData['rate'])) {
              $rateParts = explode('/', str_replace('bps', '', $queueData['rate']));
              $trafficData['tx-rate'] = isset($rateParts[0]) ? trim($rateParts[0]) : '0';
              $trafficData['rx-rate'] = isset($rateParts[1]) ? trim($rateParts[1]) : '0';
            }

            // Parse bytes (format: "upload/download")
            if (isset($queueData['bytes'])) {
              $bytesParts = explode('/', $queueData['bytes']);
              $trafficData['bytes-up'] = isset($bytesParts[0]) ? trim($bytesParts[0]) : '0';
              $trafficData['bytes-down'] = isset($bytesParts[1]) ? trim($bytesParts[1]) : '0';
            }

            // Get max limit
            if (isset($queueData['max-limit'])) {
              $trafficData['max-limit'] = $queueData['max-limit'];
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
            'caller-id' => isset($active['caller-id']) ? $active['caller-id'] : '',
            'last-caller-id' => isset($secretInfo['last-caller-id']) ? $secretInfo['last-caller-id'] : '',
            // Add traffic data
            'rx-rate' => $trafficData['rx-rate'],
            'tx-rate' => $trafficData['tx-rate'],
            'bytes-up' => $trafficData['bytes-up'],
            'bytes-down' => $trafficData['bytes-down'],
            'max-limit' => $trafficData['max-limit']
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
        if (isset($secret['name']) && stripos($secret['name'], $searchTerm) !== false)
          $match = true;
        else if (isset($secret['comment']) && stripos($secret['comment'], $searchTerm) !== false)
          $match = true;
        else if (isset($secret['caller-id']) && stripos($secret['caller-id'], $searchTerm) !== false)
          $match = true;

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
        if (isset($secret['name']) && stripos($secret['name'], $searchTerm) !== false)
          $match = true;
        else if (isset($secret['comment']) && stripos($secret['comment'], $searchTerm) !== false)
          $match = true;
        if (isset($secret['name']) && stripos($secret['name'], $searchTerm) !== false)
          $match = true;
        else if (isset($secret['comment']) && stripos($secret['comment'], $searchTerm) !== false)
          $match = true;
        else if (isset($secret['caller-id']) && stripos($secret['caller-id'], $searchTerm) !== false)
          $match = true;
        else if (isset($secret['last-caller-id']) && stripos($secret['last-caller-id'], $searchTerm) !== false)
          $match = true;

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
        if (isset($active['name']) && stripos($active['name'], $searchTerm) !== false)
          $match = true;
        else if (isset($active['caller-id']) && stripos($active['caller-id'], $searchTerm) !== false)
          $match = true;
        if (isset($active['name']) && stripos($active['name'], $searchTerm) !== false)
          $match = true;
        else if (isset($active['caller-id']) && stripos($active['caller-id'], $searchTerm) !== false)
          $match = true;
        else if (isset($active['address']) && stripos($active['address'], $searchTerm) !== false)
          $match = true;

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
        if (isset($secret['name']) && stripos($secret['name'], $searchTerm) !== false)
          $match = true;
        else if (isset($secret['comment']) && stripos($secret['comment'], $searchTerm) !== false)
          $match = true;
        else if (isset($secret['caller-id']) && stripos($secret['caller-id'], $searchTerm) !== false)
          $match = true;

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
      $this->connect();
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
      $this->connect();
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
        $logs = array_filter($logs, function ($log) use ($searchTerm) {
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
      $this->connect();
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

  /**
   * Get system resource information
   * @return array|null Resource info or null on failure
   */
  public function getSystemResource()
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      $response = $this->api->comm('/system/resource/print');

      // MikroTik returns array of results, we need the first one
      if (is_array($response) && !empty($response)) {
        return $response[0];
      }

      return null;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil system resource: " . $e->getMessage());
    }
  }

  /**
   * Get PPP Secret by name
   * @param string $name Username to find
   * @return array|null Secret data or null if not found
   */
  public function getPppSecretByName($name)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      $secrets = $this->api->comm('/ppp/secret/print', [
        '?name' => $name
      ]);

      if (empty($secrets) || !is_array($secrets)) {
        return null;
      }

      return $secrets[0];
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil PPP secret '$name': " . $e->getMessage());
    }
  }

  /**
   * Add new PPP Secret
   * @param string $name Username
   * @param string $password Password
   * @param string $profile Profile name
   * @param bool $disabled Disabled status
   * @return bool True on success
   */
  public function addPppSecret($name, $password, $profile, $disabled = false)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      $params = [
        'name' => $name,
        'password' => $password,
        'profile' => $profile
      ];

      if ($disabled) {
        $params['disabled'] = 'yes';
      }

      $this->api->comm('/ppp/secret/add', $params);

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal menambah PPP secret '$name': " . $e->getMessage());
    }
  }

  /**
   * Update PPP Secret
   * @param string $name Username (current name)
   * @param string $password New password (optional, null to keep current)
   * @param string $profile New profile
   * @param bool $disabled Disabled status
   * @return bool True on success
   */
  public function updatePppSecret($name, $password, $profile, $disabled)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Get the secret ID first
      $secrets = $this->api->comm('/ppp/secret/print', [
        '?name' => $name
      ]);

      if (empty($secrets)) {
        throw new \Exception("PPP secret '$name' tidak ditemukan");
      }

      $secretId = $secrets[0]['.id'];

      // Prepare update parameters
      $params = [
        '.id' => $secretId,
        'profile' => $profile
      ];

      // Update password only if provided
      if (!empty($password)) {
        $params['password'] = $password;
      }

      if ($disabled) {
        $params['disabled'] = 'yes';
      } else {
        $params['disabled'] = 'no';
      }

      $this->api->comm('/ppp/secret/set', $params);

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengupdate PPP secret '$name': " . $e->getMessage());
    }
  }

  /**
   * Delete PPP Secret
   * @param string $name Username to delete
   * @return bool True on success
   */
  public function deletePppSecret($name)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Check if user is active
      $activeSessions = $this->api->comm('/ppp/active/print', [
        '?name' => $name
      ]);

      if (!empty($activeSessions)) {
        throw new \Exception("User '$name' sedang aktif. Disconnect terlebih dahulu.");
      }

      // Get secret ID
      $secrets = $this->api->comm('/ppp/secret/print', [
        '?name' => $name
      ]);

      if (empty($secrets)) {
        throw new \Exception("PPP secret '$name' tidak ditemukan");
      }

      $secretId = $secrets[0]['.id'];

      $this->api->comm('/ppp/secret/remove', [
        '.id' => $secretId
      ]);

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal menghapus PPP secret '$name': " . $e->getMessage());
    }
  }

  /**
   * Set PPP Secret Disabled status
   * @param string $name Username
   * @param bool $disabled Disabled status
   * @return bool True on success
   */
  public function setPppSecretDisabled($name, $disabled)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Get secret ID
      $secrets = $this->api->comm('/ppp/secret/print', [
        '?name' => $name
      ]);

      if (empty($secrets)) {
        throw new \Exception("PPP secret '$name' tidak ditemukan");
      }

      $secretId = $secrets[0]['.id'];

      $this->api->comm('/ppp/secret/set', [
        '.id' => $secretId,
        'disabled' => $disabled ? 'yes' : 'no'
      ]);

      // If disabling and user is active, disconnect them
      if ($disabled) {
        $activeSessions = $this->api->comm('/ppp/active/print', [
          '?name' => $name
        ]);

        if (!empty($activeSessions)) {
          foreach ($activeSessions as $session) {
            $this->api->comm('/ppp/active/remove', [
              '.id' => $session['.id']
            ]);
          }
        }
      }

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengubah status PPP secret '$name': " . $e->getMessage());
    }
  }

  /**
   * Get all PPP Profiles
   * @return array List of profile names
   */
  public function getPppProfiles()
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      $profiles = $this->api->comm('/ppp/profile/print');

      if (!is_array($profiles)) {
        return [];
      }

      // Extract profile names
      $profileNames = [];
      foreach ($profiles as $profile) {
        if (isset($profile['name'])) {
          $profileNames[] = $profile['name'];
        }
      }

      return $profileNames;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil PPP profiles: " . $e->getMessage());
    }
  }

  /**
   * Get all IP pools
   * @return array List of IP pools with ranges
   */
  public function getIpPools()
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      $pools = $this->api->comm('/ip/pool/print');

      if (!is_array($pools)) {
        return [];
      }

      return $pools;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil IP pools: " . $e->getMessage());
    }
  }

  /**
   * Get IP pool by name
   * @param string $name Pool name
   * @return array|null Pool data or null if not found
   */
  public function getIpPoolByName($name)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      $pools = $this->api->comm('/ip/pool/print', [
        '?name' => $name
      ]);

      if (empty($pools) || !is_array($pools)) {
        return null;
      }

      return $pools[0];
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil IP pool '$name': " . $e->getMessage());
    }
  }

  /**
   * Add new IP pool
   * @param string $name Pool name
   * @param string $ranges IP ranges (e.g., "192.168.1.2-192.168.1.254")
   * @return bool True on success
   */
  public function addIpPool($name, $ranges)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Check if pool already exists
      $existing = $this->getIpPoolByName($name);
      if ($existing) {
        throw new \Exception("IP pool '$name' sudah ada");
      }

      $this->api->comm('/ip/pool/add', [
        'name' => $name,
        'ranges' => $ranges
      ]);

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal menambah IP pool '$name': " . $e->getMessage());
    }
  }

  /**
   * Update IP pool
   * @param string $name Pool name (current)
   * @param string $ranges New IP ranges
   * @return bool True on success
   */
  public function updateIpPool($name, $ranges)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Get pool ID
      $pool = $this->getIpPoolByName($name);
      if (!$pool) {
        throw new \Exception("IP pool '$name' tidak ditemukan");
      }

      $poolId = $pool['.id'];

      $this->api->comm('/ip/pool/set', [
        '.id' => $poolId,
        'ranges' => $ranges
      ]);

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengupdate IP pool '$name': " . $e->getMessage());
    }
  }

  /**
   * Delete IP pool
   * @param string $name Pool name
   * @return bool True on success
   */
  public function deleteIpPool($name)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Get pool ID
      $pool = $this->getIpPoolByName($name);
      if (!$pool) {
        throw new \Exception("IP pool '$name' tidak ditemukan");
      }

      $poolId = $pool['.id'];

      $this->api->comm('/ip/pool/remove', [
        '.id' => $poolId
      ]);

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal menghapus IP pool '$name': " . $e->getMessage());
    }
  }

  /**
   * Get all parent queues
   * @return array List of all queues
   */
  public function getParentQueues()
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Get all queues from MikroTik
      $queues = $this->api->comm("/queue/simple/print", array(
                "?dynamic" => "false",
            ));

      if (!is_array($queues)) {
        return [];
      }

      // Return ALL queues without filtering
      $queues = array_values($queues);

      // Debug: Log semua queues
      error_log("Total queues from MikroTik: " . count($queues));

      return $queues;
    } catch (\Exception $e) {
      error_log("getParentQueues Exception: " . $e->getMessage());
      throw new \Exception("Gagal mengambil parent queues: " . $e->getMessage());
    }
  }

   /**
    * Get parent queue by name
    * @param string $name Queue name
    * @return array|null Queue data or null if not found
    */

  /**
   * Get parent queue by name
   * @param string $name Queue name
   * @return array|null Queue data or null if not found
   */
  public function getParentQueueByName($name)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      error_log("Looking for parent queue: " . $name);

      $queues = $this->api->comm('/queue/simple/print', [
        '?name' => $name
      ]);

      error_log("Queue search result for '$name': " . json_encode($queues));

      if (empty($queues) || !is_array($queues)) {
        error_log("Queue '$name' not found or empty response");
        return null;
      }

      error_log("Queue '$name' found. Target: " . 
                 (isset($queues[0]['target']) ? $queues[0]['target'] : 'none') . 
                 ", Packet-mark: " . 
                 (isset($queues[0]['packet-mark']) ? $queues[0]['packet-mark'] : 'none'));

      // Return the queue regardless (parent queue caller knows what they're doing)
      // Only return null if queue genuinely doesn't exist
      return $queues[0];
    } catch (\Exception $e) {
      error_log("getParentQueueByName Error: " . $e->getMessage());
      throw new \Exception("Gagal mengambil parent queue '$name': " . $e->getMessage());
    }
  }

  /**
   * Add new parent queue
   * @param string $name Queue name
   * @param string $maxLimit Max rate limit (e.g., "10M/10M")
   * @param string $packetMark Packet mark (optional)
   * @return bool True on success
   */
  public function addParentQueue($name, $maxLimit, $packetMark = '')
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Check if queue already exists
      $existing = $this->getParentQueueByName($name);
      if ($existing) {
        throw new \Exception("Parent queue '$name' sudah ada");
      }

      $params = [
        'name' => $name,
        'max-limit' => $maxLimit
      ];

      if (!empty($packetMark)) {
        $params['packet-mark'] = $packetMark;
      }

      $this->api->comm('/queue/simple/add', $params);

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal menambah parent queue '$name': " . $e->getMessage());
    }
  }

  /**
   * Update parent queue
   * @param string $name Queue name (current)
   * @param string $maxLimit New max rate limit
   * @param string $packetMark New packet mark (optional)
   * @return bool True on success
   */
  public function updateParentQueue($name, $maxLimit, $packetMark = '')
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Get queue ID
      $queue = $this->getParentQueueByName($name);
      if (!$queue) {
        throw new \Exception("Parent queue '$name' tidak ditemukan");
      }

      $queueId = $queue['.id'];

      $params = [
        '.id' => $queueId,
        'max-limit' => $maxLimit
      ];

      if (!empty($packetMark)) {
        $params['packet-mark'] = $packetMark;
      }

      $this->api->comm('/queue/simple/set', $params);

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengupdate parent queue '$name': " . $e->getMessage());
    }
  }

  /**
   * Delete parent queue
   * @param string $name Queue name
   * @return bool True on success
   */
  public function deleteParentQueue($name)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Get queue ID
      $queue = $this->getParentQueueByName($name);
      if (!$queue) {
        throw new \Exception("Parent queue '$name' tidak ditemukan");
      }

      $queueId = $queue['.id'];

      $this->api->comm('/queue/simple/remove', [
        '.id' => $queueId
      ]);

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal menghapus parent queue '$name': " . $e->getMessage());
    }
  }

  /**
   * Get all PPP profiles with full details
   * @return array List of profiles with all attributes
   */
  public function getPppProfilesFull()
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      $profiles = $this->api->comm('/ppp/profile/print');

      if (!is_array($profiles)) {
        return [];
      }

      return $profiles;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil PPP profiles: " . $e->getMessage());
    }
  }

  /**
   * Get PPP profile by name
   * @param string $name Profile name
   * @return array|null Profile data or null if not found
   */
  public function getPppProfileByName($name)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      $profiles = $this->api->comm('/ppp/profile/print', [
        '?name' => $name
      ]);

      if (empty($profiles) || !is_array($profiles)) {
        return null;
      }

      return $profiles[0];
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengambil PPP profile '$name': " . $e->getMessage());
    }
  }

  /**
   * Add new PPP profile
   * @param string $name Profile name
   * @param string $localAddress Local address (IP pool name)
   * @param string $remoteAddress Remote address (IP pool name)
   * @param string $rateLimit Rate limit (e.g., "512k/1M")
   * @param string $parentQueue Parent queue name
   * @param bool $disabled Disabled status
   * @return bool True on success
   */
  public function addPppProfile($name, $localAddress, $remoteAddress, $rateLimit, $parentQueue)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Check if profile already exists
      $existing = $this->getPppProfileByName($name);
      if ($existing) {
        throw new \Exception("PPP profile '$name' sudah ada");
      }

      $params = [
        'name' => $name,
        'local-address' => $localAddress,
        'remote-address' => $remoteAddress,
        'rate-limit' => $rateLimit,
        'parent-queue' => $parentQueue
      ];


      $this->api->comm('/ppp/profile/add', $params);

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal menambah PPP profile '$name': " . $e->getMessage());
    }
  }

  /**
   * Update PPP profile
   * @param string $name Profile name (current)
   * @param string $localAddress New local address (IP pool name)
   * @param string $remoteAddress New remote address (IP pool name)
   * @param string $rateLimit New rate limit
   * @param string $parentQueue New parent queue name
   * @param bool $disabled Disabled status
   * @return bool True on success
   */
  public function updatePppProfile($name, $localAddress, $remoteAddress, $rateLimit, $parentQueue, $disabled)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Get profile ID
      $profile = $this->getPppProfileByName($name);
      if (!$profile) {
        throw new \Exception("PPP profile '$name' tidak ditemukan");
      }

      $profileId = $profile['.id'];

      $params = [
        '.id' => $profileId,
        'local-address' => $localAddress,
        'remote-address' => $remoteAddress,
        'rate-limit' => $rateLimit,
        'parent-queue' => $parentQueue
      ];

      $this->api->comm('/ppp/profile/set', $params);

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengupdate PPP profile '$name': " . $e->getMessage());
    }
  }

  /**
   * Delete PPP profile
   * @param string $name Profile name
   * @return bool True on success
   */
  public function deletePppProfile($name)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      // Check if profile is used by any PPP secret
      if ($this->isProfileUsedBySecrets($name)) {
        throw new \Exception("Profile '$name' sedang digunakan oleh PPP secret. Hapus/pindahkan secret terlebih dahulu.");
      }

      // Get profile ID
      $profile = $this->getPppProfileByName($name);
      if (!$profile) {
        throw new \Exception("PPP profile '$name' tidak ditemukan");
      }

      $profileId = $profile['.id'];

      $this->api->comm('/ppp/profile/remove', [
        '.id' => $profileId
      ]);

      return true;
    } catch (\Exception $e) {
      throw new \Exception("Gagal menghapus PPP profile '$name': " . $e->getMessage());
    }
  }

  /**
   * Set PPP profile disabled status
   * @param string $name Profile name
   * @param bool $disabled Disabled status
   * @return bool True on success
   */

  /**
   * Check if profile is used by any PPP secret
   * @param string $name Profile name
   * @return bool True if profile is in use
   */
  public function isProfileUsedBySecrets($name)
  {
    try {
      $this->connect();
      if (!$this->connected) {
        throw new \Exception("Tidak terhubung ke MikroTik");
      }

      $secrets = $this->api->comm('/ppp/secret/print');

      if (!is_array($secrets)) {
        return false;
      }

      foreach ($secrets as $secret) {
        if (isset($secret['profile']) && $secret['profile'] === $name) {
          return true;
        }
      }

      return false;
    } catch (\Exception $e) {
      throw new \Exception("Gagal mengecek penggunaan profile: " . $e->getMessage());
    }
  }

  public function __destruct()
  {
    if ($this->api && $this->connected) {
      $this->api->disconnect();
    }
  }
}

