<?php

class Model_Admin extends Model
{
	public function update_status($data)
	{
		session_start();
		session_regenerate_id();

		if (!empty($_SESSION['admin']) && $_SESSION['admin'] == "true") {
			$pdo = connect_db();

			$status = $data['status'];
			$id = $data['id'];

			if ($status == 'true') {
				$status = 1;
			} else {
				$status = 0;
			}

			try {
				$stmt = $pdo->prepare("UPDATE phpToDo SET status = $status WHERE id = $id");
				$stmt->execute();
				echo "true";
				return true;
			} catch (\Throwable $th) {
				return false;
			}
		}
	}

	public function update_post($data)
	{
		session_start();
		session_regenerate_id();

		if (!empty($_SESSION['admin']) && $_SESSION['admin'] == "true") {
			$pdo = connect_db();

			$text = $data['text'];
			$id = $data['id'];

			try {
				$stmt = $pdo->prepare("UPDATE phpToDo SET text = '$text', adminrev = 1 WHERE id = $id");
				$stmt->execute();
				echo "true";
				return true;
			} catch (\Throwable $th) {
				return false;
			}
		}
	}

	public function add_post($data)
	{
		$xss_filter = new xssClean;
		$clean_text = $xss_filter->clean_input($data['text']);

		$pdo = connect_db();

		try {
			$stmt = $pdo->prepare('INSERT INTO phpToDo(username, email, text) VALUES (?,?,?)');
			$stmt->execute([$data['username'], $data['email'], $clean_text]);
			header('Location:/admin');
		} catch (\Throwable $th) {
			header('Location:/admin');
		}
	}

	public function delete_row($post)
	{
		session_start();
		session_regenerate_id();

		if (!empty($_SESSION['admin']) && $_SESSION['admin'] == "true") {
			if (!empty($post)) {
				$id = $post['id'];
			} else {
				return false;
			}

			$pdo = connect_db();

			$stmt = $pdo->query("DELETE FROM phpToDo WHERE id=$id");
			$stmt->execute();

			return true;
		} else {
			session_unset();
			session_destroy();

			return false;
		}
	}

	public function get_data($post)
	{
		if (!empty($post)) {
			$page = $post['page'];
			$columnName = $post['columnName'];
			$sort = $post['sort'];
		} else {
			$page = null;
			$columnName = "username";
			$sort = "asc";
		}

		$pdo = connect_db();

		$limit = 3;
		if (!empty($page)) {
			$start_from = ($page - 1) * $limit;
		} else {
			$start_from = 0;
		}

		$total_pages = getTotalPages($limit, $pdo);

		$data = array();
		$data[] = array("count" => $total_pages);

		$stmt = $pdo->query("SELECT id, username, email, text, status, adminrev FROM phpToDo ORDER BY $columnName $sort LIMIT $start_from, $limit");

		while ($row = $stmt->fetch()) {
			$id = $row['id'];
			$username = $row['username'];
			$email = $row['email'];
			$text = $row['text'];
			$status = $row['status'];
			$adminrev = $row['adminrev'];

			$data[] = array("id" => $id, "username" => $username, "email" => $email, "text" => $text, "status" => $status, "adminrev" => $adminrev);
		}
		return $data;
	}
}
