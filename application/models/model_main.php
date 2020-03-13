<?php

class Model_Main extends Model
{
	public function add_post($data)
	{
		$xss_filter = new xssClean;
		$clean_text = $xss_filter->clean_input($data['text']);

		$pdo = connect_db();

		try {
			$stmt = $pdo->prepare('INSERT INTO phpToDo(username, email, text) VALUES (?,?,?)');
			$stmt->execute([$data['username'], $data['email'], $clean_text]);
			header('Location:/');
		} catch (\Throwable $th) {
			header('Location:/');
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
