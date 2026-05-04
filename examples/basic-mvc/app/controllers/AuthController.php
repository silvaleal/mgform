<?php 


namespace App\Controllers;

use Flight;
use MGForm\Validator;

class AuthController {
    public function index()
    {
        unset($_SESSION['errors']);
        Flight::render('home');
    }

    public function success()
    {
        Flight::render('success');
    }

    public function store()
    {
        $validator = (new Validator())->validate([
            'nome' => 'required|min:3|max:5',
            'clan' => 'required|min:3',
            'email' => 'required|email'
        ]);

        if ($validator['type'] == 'error') {
            $_SESSION['errors'] = $validator['data'];
            return Flight::render('home');
            
            }
        
        Flight::redirect('/success');
    }

}