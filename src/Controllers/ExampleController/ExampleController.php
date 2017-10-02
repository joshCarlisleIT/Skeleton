<?php
namespace IngeniousWeb\Skeleton\Controllers;

use IngeniousWeb\Skeleton\Core\BaseController;

class ExampleController extends BaseController
{
	/**
	 * @var $base
	 */
	//public $base;

	/**
	 * @param Base $base
	 */
	public function __construct(/*Base $base*/)
	{
		//$this->base = $base;
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