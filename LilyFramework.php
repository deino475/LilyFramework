<?php
class Lily implements View{
	private $events = array();

	public function route ($name, $function) {
		if ($this->check($name) == false) {
			$this->events[$name] = $function;
		}
	}

	public function emit($name, $data = []) {
		if ($this->check($name)) {
			$this->events[$name]($data);
		}
	}

	public function check($name) {
		return array_key_exists($name, $this->events);
	}

	public function start($event = 'index', $params = []) {
		if(isset($_GET['m'])) {
			if (sizeof(explode('/',$_GET['m'])) == 0){
				$events = 'index';
				$params = array();
			} else {
				if ($this->check($this->parseURL($_GET['m'])[0])) {
					$parsedURL = $this->parseURL($_GET['m']);
					$event = $parsedURL[0];
					$params = $parsedURL[1];
				} else {
					$event = '404';
					$params = array();
				}
			}
		}
		$this->emit($event, $data = $params);
	}

	public function model($name) {
		include 'models/'.$name.'.php';
		return new $name;
	}

	public function action($name) {
		include 'actions/'.$name.'.php';
		return new $name;
	}

	public function view($name) {
		include 'views/'.$name.'.php';
		return new $name;
	}

	public function renderTemplate($name, $data = []) {
		include 'templates/'.$name.'.php';
	}

	public function renderHTML($text) {
		echo $text;
	}

	public function renderXML($data) {
		header('Content-Type: text/xml;');
		echo $data;
	}

	public function renderJSON($data) {
		echo json_encode($data);
	}

	public function renderCSV($data, $name = 'data') {
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename='.$name.'.csv');
		$output = fopen('php://output', 'w');
		foreach ($data as $row) {
			fputcsv($output, $row);
		}
	}

	public function redirectTo($name) {
		header('Location: ?m='.$name);
	}

	public function parseURL($url) {
		$data = explode('/', $url);
		unset($data[0]);
		$data = array_values($data);
		$event = $data[0];
		unset($data[0]);
		$params = array_values($data);
		return array($event, $params);
	}
}

interface View {
	function renderTemplate($name, $data = []);
	function renderHTML($text);
	function renderXML($data);
	function renderJSON($data);
	function renderCSV($data, $name = 'data');
}

interface ViewTemplate extends View {
	function __invoke();
}

interface Action {
	function __invoke();
}

class Session {
	public function __construct() {
		session_start();
	}

	public function get($name) {
		return $_SESSION[$name];
	}

	public function set($key, $value) {
		$_SESSION[$key] = $value;
	}

	public function is_set($key) {
		return isset($_SESSION[$key]);
	}

	public function kill() {
		session_destroy();
	}
}