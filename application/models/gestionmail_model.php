<?
class Gestionmail_model extends CI_Model {
	
	public function __construct()
	{
		$this->load->database();
	}

	function getrappel(){
		$this->db-> select_max('id');
		$this->db-> from('rappel');
		$query = $this->db->get();
		$lastid = $query->result_array();

		$this->db-> select('daterappel');
		$this->db-> from('rappel');
		$this->db->where('id', $lastid[0]['id']);
		$query = $this->db->get();
		$res = $query->row();

		//echo var_dump($res);
		$lastdate = $res -> daterappel;
		//echo $lastdate ;
		$date3=time();
		$date0=date("m/d/y");
		if($date0 == $lastdate){
			return false;
		}else{
			$data = array('id' => $date3,'daterappel' => $date0);
			$this->db->insert('rappel', $data);
			return true;
		}
	
	}

	function sendMail($from,$to,$message){
		$this->email->clear();
		$this->email->from($from, 'Administrateur');
		$this->email->to($to);
		$this->email->subject('Email');
		$this->email->message($message);	
		$this->email->send();
	}
}
