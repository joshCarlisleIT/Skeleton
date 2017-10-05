<?php
namespace IngeniousWeb\Skeleton\Controllers;

use IngeniousWeb\Skeleton\Core\BaseController;

class ExampleController extends BaseController
{
	/**
	 * @var $base
	 */
	public $base;

	/**
	 * @param Base $base
	 */
	public function __construct(Base $base)
	{
		$this->base = $base;
	}

	public function index()
	{
		$this->base->view('ExampleView/ExampleView.php', $title = null, $user = null, $data = null, $token = null);
	}
}