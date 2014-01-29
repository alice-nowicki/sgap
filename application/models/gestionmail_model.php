<?
class Gestionmail_model extends CI_Model {
	
	public function __construct()
	{
		$this->load->database();
		$this->load->library('email');
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


		$lastdate = $res -> daterappel;
		$date3=time();
		$date0=date("Y-m-d");
		if($date0 == $lastdate){
			return false;
		}else{
			$data = array('id' => $date3,'daterappel' => $date0);
			$this->db->insert('rappel', $data);
			return true;
		}
	
	}

	function sendMail($from,$to,$message){
		$listeE=array("listeEchec"=>array(),"listeEnvoi"=>array());
		$this->email->clear();
		$this->email->from($from, 'Administrateur');
		$this->email->to($to);
		$this->email->subject('Email');
		$this->email->message($message);	
		if(!$this->email->send()){
			array_push($listeE["listeEchec"], $to);
		}else{ 
			array_push($listeE["listeEnvoi"], $to);
		}
		return $listeE;
	}


	
}
