<?php

class Controller_Admin extends Controller
{
	function __construct()
	{
		$this->model = new Model_Admin();
		$this->view = new View();
	}

	function action_logout()
	{
		session_start();
		
		session_unset();
		session_destroy();

		header('Location:/');
	}

	function action_index($get_data)
	{
		session_start();
		session_regenerate_id();
		
		if (!empty($_SESSION['admin']) && $_SESSION['admin'] == "true") 
		{
			$data = $this->model->get_data(null);
			$this->view->generate('admin_view.php', 'template_view.php', $data);
		}
		else
		{
		
			session_unset();
			session_destroy();

			header('Location:/');
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


	function action_delete()
	{
		if (isset($_POST)) {
			$data = $this->model->delete_row($_POST);
		}

		echo json_encode($data);
	}

	function action_add()
	{
		if (isset($_POST)) {
			$this->model->add_post($_POST);
		}
	}

	function action_update()
	{
		if (isset($_POST)) {
			$this->model->update_post($_POST);
		}
	}

	function action_updatestatus()
	{
		if (isset($_POST)) {
			$this->model->update_status($_POST);
		}
	}

}
