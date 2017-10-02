<?php
namespace IngeniousWeb\Skeleton\Services\System;

use IngeniousWeb\Site\Models\UserModel;
use IngeniousWeb\Site\Services\Auth\Session;
use IngeniousWeb\Site\Services\Auth\Cookie;
use IngeniousWeb\Site\Services\Utils\Hash;
use IngeniousWeb\Site\Services\Utils\Config;

class User 
{
	/**
	 * @var $userModel
	 * @var $hash
	 */
	private $userModel;
	private $hash;
	private $loggedIn;

	/**
	 * @param UserModel $userModel
	 * @param Hash $hash
	 */
	public function __construct(UserModel $userModel, Hash $hash, $user = null)
	{
		$this->userModel = $userModel;
		$this->hash = $hash;
		$this->session = Config::get('session/session_name');
		$this->cookieName = Config::get('remember/cookie_name');

		if (!$user) {
			if (Session::exists($this->session)) {
				$user = Session::get($this->session);
				if ($this->find($user)) {
					$this->loggedIn = true;
				} else {
					$this->logout();
				}
			}
		} else {
			$this->find($user);
		}
	}

	public function find($code = null)
	{
		if ($code) {
			$field = (is_numeric($code)) ? 'userID' : 'userCode' ;
			return $this->data = $this->userModel->get('users', [$field, '=', $code]);
		}
		return false;
	}

	public function data()
	{
		return $this->data;
	}

	public function login($code = null, $password = null, $remember = false)
	{
		if (!$code && !$password && $this->exists()) {
			Session::put($this->session, $user->user->userID);
		}

		$user = $this->find($code);
		if (!isset($user->user->userCode)) {
			$this->error('email');
		}

		$hash = $user->user->password;
		if (Hash::check($password, $hash) == true) {
			Session::put($this->session, $user->user->userID);
			if ($remember) {
				$hash = $this->hash->unique();
				$id = $this->data()->user->userID;
				$hashCheck = $this->userModel->getSession('userSession', ['userID', '=', $id], $id, $hash);
				
				Cookie::put($this->cookieName, $hash, Config::get('remember/cookie_expiry'));
			}
			return true;
		} else {
			$this->error('password');
		}
		return false;
	}

	public function isLoggedIn()
	{
		return $this->loggedIn;
	}

	public function password($table = '', $where = [], $data = [])
	{
		if ($this->userModel->updatePass($table, $where, $data)) {
        } else {
            throw new \Exception('Password wasn\'t updated try again');
        }
	}

	public function logout()
	{
		$this->userModel->delete('userSession', ['userID', '=', $this->data()->user->userID]);
		Session::delete($this->session);
		Cookie::delete($this->cookieName);
	}
}