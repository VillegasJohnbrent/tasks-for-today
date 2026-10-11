<?php
namespace App\Controllers;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $helpers = ['url', 'form'];
    public function login()
    {
        if (session()->get('logged_in') === true) {
            return redirect()->to(site_url('tasks'));
        }
        return $this->loginView();
    }

    public function authenticate()
    {
        $data = $this->request->getPost(['username', 'password']);
        if (is_string($data['username'] ?? null)) {
            $data['username'] = trim($data['username']);
        }
        $rules = [
            'username' => 'required|max_length[50]',
            'password' => 'required|max_length[4096]',
        ];
        if (! $this->validateData($data, $rules)) {
            $this->response->setStatusCode(422);
            return $this->loginView($this->validator->getErrors(), is_string($data['username'] ?? null) ? $data['username'] : '');
        }
        $user = (new UserModel())->forLogin($data['username']);
        if (! $user || ! password_verify($data['password'], $user['password'])) {
            $this->response->setStatusCode(422);
            return $this->loginView(['login' => 'Incorrect username or password.'], $data['username']);
        }
        session()->regenerate(true);
        session()->set([
            'logged_in' => true,
            'user_id' => (int) $user['id'],
            'username' => $user['username'],
        ]);
        return redirect()->to(site_url('tasks'))->with('success', 'You are now logged in.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }

    private function loginView(array $errors = [], string $username = ''): string
    {
        return view('login', ['title' => 'Log in', 'active' => 'login', 'errors' => $errors, 'username' => $username]);
    }
}
