<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Copytext extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// 引入資料庫套件
		$this->load->database();
		$this->db->order_by('updatetime','DESC');
		$this->db->limit(50);
		$query = $this->db->get('textlist');
		$listdata = $query->result_array();
		$refactor_input_string = "尚無資料";
		$refactor_preview_string = "尚無資料";
		$origin_string = "尚無資料";
		if (count($listdata) > 0) {
			$origin_string = $listdata[0]["origintext"];
			$refactor_input_string = $this->replace_star($listdata[0]["copytext"], "input");
			$refactor_preview_string = $this->replace_star($listdata[0]["copytext"], "preview");
		}
		$listdata = $this->replace_listdata($listdata);

		$data["ogdesc"] = "複製文產生器，一個讓你方便製作複製文的好所在。";
		$data["listdata"] = $listdata;
		$data["origin_string"] = $origin_string;
		$data["refactor_input_string"] = $refactor_input_string;
		$data["refactor_preview_string"] = $refactor_preview_string;
		$this->load->view('home', $data);
	}

	public function create()
	{
		$this->load->view('create');
	}

	public function textno($textno)
	{
		$where_arr = array('no' => $textno);

		// 引入資料庫套件
		$this->load->database();
		$query = $this->db->get_where('textlist', $where_arr);
		$data_arr = $query->result_array();

		$this->db->order_by('updatetime','DESC');
		$this->db->limit(50);
		$query = $this->db->get('textlist');
		$listdata = $query->result_array();

		$listdata = $this->replace_listdata($listdata);
		$origin_string = (count($data_arr) > 0) ? $data_arr[0]["origintext"] : "尚無資料";
		$refactor_input_string = $this->replace_star($data_arr[0]["copytext"], "input");
		$refactor_preview_string = $this->replace_star($data_arr[0]["copytext"], "preview");

		$data["ogdesc"] = strip_tags($origin_string);
		$data["listdata"] = $listdata;
		$data["origin_string"] = $origin_string;
		$data["refactor_input_string"] = $refactor_input_string;
		$data["refactor_preview_string"] = $refactor_preview_string;

		$this->load->view('home', $data);
	}

	public function datalist($pageno = 0)
	{
		$this->load->database();
		$this->db->order_by('updatetime','DESC');
		$this->db->limit(200);
		$query = $this->db->get('textlist');
		$listdata = $query->result_array();
		$listdata = $this->replace_listdata($listdata);
		$data["listdata"] = $listdata;
		$this->load->view('datalist', $data);
	}

	public function about()
	{
		$this->load->view('about');
	}

	public function ajaxcreate()
	{
		if (empty($_POST["newtext"])) {
			echo json_encode(array("status" => "fail", "errormsg" => "文章怪怪的喔！要不要回報下製作方？"));
			return;
		}

		$starcount = substr_count($_POST["newtext"], "#");
		if ($starcount > 20 || $starcount == 0) {
			echo json_encode(array("status" => "fail", "errormsg" => "關鍵字怪怪的喔！要不要回報下製作方？"));
			return;
		}

		// assemble the message from the POST fields
		// getting the captcha
		$captcha = "";
		if (isset($_POST["grecaptcha"])){
			$captcha = $_POST["grecaptcha"];
		}

		if (!$captcha){
			echo json_encode(array("status" => "fail", "errormsg" => "驗證有問題呦！"));
			return;
		}
		// handling the captcha and checking if it's ok
		$secret = "6LcVkCkTAAAAAFpDFY1aG3Z_kylsHHkZTgfJpTtF";
		$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$_SERVER["REMOTE_ADDR"]), true);

		// if the captcha is cleared with google, send the mail and echo ok.
		if ($response["success"] == false) {
			echo json_encode(array("status" => "fail", "errormsg" => "驗證有問題呦！"));
			return;
		}

		// 引入資料庫套件
		$this->load->database();
		$insert_arr = array(
			'copytext' => nl2br(strip_tags($_POST["newtext"])),
			'origintext' => nl2br(strip_tags($_POST["origintext"])),
			'updatetime' => time(),
			'flag' => 1	
		);
		$this->db->insert('textlist', $insert_arr);
		$new_text_id = $this->db->insert_id();
		echo json_encode(array("status" => "success", "newtextid" => $new_text_id));
	}

	private function replace_star($origin_text, $replace_type)
	{
		for ($i = 1; $i < 21; $i++) { 
			$texid = str_pad(strval($i),3,'0',STR_PAD_LEFT);
			$search = "#".$texid;
			if ($replace_type == "input") {
				$replaced = "<div class='form-group'><input type='text' class='form-control input_keyword' id='input_key_".$texid."' key-index='".$texid."' placeholder='輸入關鍵字' aria-describedby='basic-addon".$texid."'></div>";
			} else {
				$replaced = "<span class='preview_key' id='preview_key_".$texid."'></span>";
			}
			$origin_text = str_replace($search, $replaced, $origin_text);
		}
		return $origin_text;
	}

	private function replace_listdata($listdata)
	{
		$refactor_listdata = array();
		foreach ($listdata as $key => $value) {
			for ($i = 1; $i < 21; $i++) {
				$texid = str_pad(strval($i),3,'0',STR_PAD_LEFT);
				$search = "#".$texid;
				$replaced = "";
				$value["copytext"] = str_replace($search, $replaced, $value["copytext"]);
			}
			$refactor_listdata[] = $value;
		}
		return $refactor_listdata;
	}

	/**
	* @version $Id: str_split.php 10381 2008-06-01 03:35:53Z pasamio $
	* @package utf8
	* @subpackage strings
	*/
	function utf8_str_split($str, $split_len = 1)
	{
	    if (!preg_match('/^[0-9]+$/', $split_len) || $split_len < 1)
	        return FALSE;
	 
	    $len = mb_strlen($str, 'UTF-8');
	    if ($len <= $split_len)
	        return array($str);
	 
	    preg_match_all('/.{'.$split_len.'}|[^\x00]{1,'.$split_len.'}$/us', $str, $ar);
	 
	    return $ar[0];
	}
}
