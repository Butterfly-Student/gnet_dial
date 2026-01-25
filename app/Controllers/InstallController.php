<?php
namespace Controllers;

use Models\BaseModel;

class InstallController extends BaseController {
    
    /**
     * Initialize database
     */
    public function index() {
        // Simple security: You might want to remove this or add a secret token in production
        // For now, we allow it as requested for setup.
        
        $result = \Models\Installer::resetDatabase();
        
        if ($result['success']) {
            echo "<h1>Database Initialized Successfully</h1>";
            echo "<p>All tables dropped and recreated. Default data inserted.</p>";
            echo "<p><a href='/login'>Go to Login</a></p>";
        } else {
            echo "<h1>Installation Failed</h1>";
            echo "<p>Error: " . $result['message'] . "</p>";
        }
    }
}
