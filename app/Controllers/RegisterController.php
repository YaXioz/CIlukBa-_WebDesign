<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;

class RegisterController extends BaseController
{
    protected $user;
    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        $data = [
            'title' => 'title',
            'errors' => session()->getFlashdata('_ci_validation_errors') ?? [],
        ];
        return view('login/index', $data);
    }

    public function create()
    {
        $data = [
            'username' => $this->request->getVar('username'),
            'email' => $this->request->getVar('email'),
            'password' => $this->request->getVar('password'),
            'name' => $this->request->getVar('name'),
        ];

        $validation = service('validation');
        $validation->setRules([
            'username' => 'required|alpha_dash|is_unique[user.username]',
            'email' => 'required|valid_email',
            'password' => 'required|alpha_numeric_punct',
            'name' => 'required|alpha_space'
        ]);
        if (!$validation->run($data))
            return redirect()->back()->withInput();

        $hashedPass = password_hash($data['password'], PASSWORD_ARGON2ID);

        $this->user->save([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $hashedPass,
            'name' => $data['name'],
        ]);

        // Pakai Verifikasi Email?

        return redirect()->to('');
    }
}
