<?php
namespace Controllers;

use Services\SessionService;

/**
 * Dashboard Controller
 * 
 * Handles dashboard display
 */
class DashboardController extends BaseController {
    
    /**
     * Show dashboard with search functionality
     */
    public function index() {
        $this->requireAuth();
        
        $flash = SessionService::getFlash();
        
        $this->view('dashboard/index', [
            'flash' => $flash
        ]);
    }
}
