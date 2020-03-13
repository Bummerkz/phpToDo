<?php

class Controller_Login extends Controller
{
	function action_index($getdata)
	{
		session_start();
		session_regenerate_id();

		$_SESSION['login_status'] = "access_denied";
		
		if(isset($_POST['login']) && isset($_POST['password']))
		{
			$login = $_POST['login'];
			$password =$_POST['password'];

			if($login=="admin" && $password=="123")
			{
				$_SESSION['admin'] = "true";
				$_SESSION['login_status'] = "";
				header('Location:/admin');
			}
			else
			{
				$_SESSION['admin'] = "";
				$_SESSION['login_status'] = "access_denied";
				header('Location:/');
			}
		}
	}
}
