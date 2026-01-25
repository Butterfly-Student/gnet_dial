<?php
namespace Controllers;

use Services\AuthService;
use Services\SessionService;

/**
 * Authentication Controller
 * 
 * Handles user login and logout
 */
class AuthController extends BaseController {
    
    /**
     * Show login form
     */
    public function showLogin() {
        // If already logged in, redirect to dashboard
        if (AuthService::check()) {
            $this->redirect('/');
        }
        
        $flash = SessionService::getFlash();
        $this->view('auth/login', [
            'flash' => $flash
        ]);
    }
    
    /**
     * Process login
     */
    public function login() {
        if (!isPost()) {
            $this->redirect('/login');
        }
        
        $username = $this->post('username');
        $password = $this->post('password');
        $remember = $this->post('remember') === 'on';
        
        // Validate input
        if (empty($username) || empty($password)) {
            SessionService::flash('Please enter username and password', 'error');
            $this->redirect('/login');
        }
        
        // Attempt login
        if (AuthService::login($username, $password, $remember)) {
            SessionService::flash('Login successful!', 'success');
            $this->redirect('/');
        } else {
            SessionService::flash('Invalid username or password', 'error');
            $this->redirect('/login');
        }
    }
    
    /**
     * Process logout
     */
    public function logout() {
        AuthService::logout();
        SessionService::flash('You have been logged out', 'info');
        $this->redirect('/login');
    }
}
