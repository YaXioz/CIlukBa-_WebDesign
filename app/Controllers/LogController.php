<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LogController extends BaseController
{
    protected $user;
    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        $data = [
            'title' => 'Login',
            'errors' => session()->getFlashdata('_ci_validation_errors') ?? [],
        ];
        // dd($data);
        return view('login', $data);
    }

    public function logging_in()
    {
        $data = [
            'username' => $this->request->getVar('username'),
            'password' => $this->request->getVar('password'),
        ];

        $dataOld = $this->user->getUser($data['username']);
        $rule = password_verify($data['password'], $dataOld['password']);

        $validation = service('validation');
        $validation->setRules(
            [
                'username' => [
                    'required',
                    'is_not_unique[user.username]',

                ],
                'password' => [
                    'required',
                    static fn() => $rule,
                ]
            ],
            [
                'username' => [
                    'required' => 'Username harus diisi',
                    'is_not_unique' => 'Username tidak terdaftar'
                ],
                'password' => [
                    'required' => 'Password harus diisi',
                    1 => 'Password tidak valid'
                ]
            ]
        );

        if (!$validation->run($data))
            return redirect()->to('/login')->withInput();

        session()->set($dataOld);
        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
