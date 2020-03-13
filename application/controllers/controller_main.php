<?php

class Controller_Main extends Controller
{

	function __construct()
	{
		$this->model = new Model_Main();
		$this->view = new View();
	}

	function action_index($get_data)
	{
		session_start();
		session_regenerate_id();

		if (!empty($_SESSION['admin']) && $_SESSION['admin'] == "true") {
			header('Location:/admin');
		} else {
			$data = $this->model->get_data(null);
			$this->view->generate('main_view.php', 'template_view.php', $data);
		}
	}

	function action_getdata()
	{
		if (isset($_POST)) {
			$data = $this->model->get_data($_POST);
		} else {
			$data = $this->model->get_data(null);
		}

		echo json_encode($data);
	}

	function action_add()
	{
		if (isset($_POST)) {
			$this->model->add_post($_POST);
		}
	}
}
