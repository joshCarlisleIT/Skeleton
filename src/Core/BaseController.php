<?php
namespace IngeniousWeb\Skeleton\Core;

use IngeniousWeb\Skeleton\Services\System\User;

class BaseController
{
	/**
	 * @var $user
	 */
	//protected $user;

	/**
	 * @param User $user
	 */
	public function __construct(/*User $user*/)
	{
		//$this->user = $user;
	}

	public function title($class)
	{
		return get_class($class);
	}

	public function view($view, $title, $user, $data = [], $token)
	{
		require_once '../web/templates/template.php';
	}
}