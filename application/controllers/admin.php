<?php
class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form'));
		if ( !isset($this->session->userdata['profil']) ) redirect('user/login');
		if ( $this->session->userdata['profil'] > 3 ) {
			$this->load->helper('datefr');
			//$this->output->enable_profiler(TRUE);
			return(true);
		}  
		$this->session->set_flashdata('messages', "<p>Vos droits actuels sont insuffisants pour afficher la page demandée. Vous avez été redirigé vers l'écran d'authentification.</p>" );
		redirect('user/login');
	}
	
	
	public function vueEleve($eleve_id)
	{
		$this->load->model('users_model');
		$this->load->model('accompagnement_model');
		$this->load->model('inscriptions_model');
		$this->load->model('seances_model');
		$data = $this->accompagnement_model->getAllActiveWithCyclesAndMatieres();
		$this->session->userdata['forcemode']=true;
		$this->session->userdata['forced_id']=$eleve_id;
		$tmp=$this->users_model->getNameOf($eleve_id);
		$nom_data=$tmp[0];
		$data['eleve_id']=$eleve_id;
		$data['nom']= strtoupper($nom_data['nom']).' '.$nom_data['prenom'];
		$data['title']='Élève - Mode forcé';
		$data['messages'] = $this->session->flashdata('messages');
		$data['historiqueAccompagnements'] = $this->inscriptions_model->getHistory($eleve_id);
		$data['historiqueSeances'] = $this->seances_model->historique($eleve_id);
		$this->load->view('templates/header', $data);
		$this->load->view('eleve/selection', $data);
		$this->load->view('eleve/infos', $data);
		$this->load->view('eleve/js', $data);
		$this->load->view('templates/footer');
	}
	
	public function inscription() {
		if (count($_POST)==0) redirect('admin/');
		$cycle_id = $this->input->post('cycle_id');
		$matiere_id = $this->input->post('matiere_id');
		$this->load->model('accompagnement_model');
		$this->load->model('inscriptions_model');
		$accompagnement_id = $this->accompagnement_model->getId($cycle_id,$matiere_id);
		if ($accompagnement_id<0){
			$this->session->set_flashdata('messages', 'Accompagnement non ouvert.' );
			redirect('admin/');
		}
		$eleve_id = $this->input->post('eleve_id');
		$ins = $this->inscriptions_model->inscrire($eleve_id,$accompagnement_id,true); 
		$this->session->set_flashdata('messages', $ins['message'] );
		redirect('admin/vueEleve/'.$eleve_id);
	}
	
	public function index()
	{
		$this->load->model('cycles_model');
		$this->load->model('matieres_model');
		$this->load->model('inscriptions_model');
		$this->load->model('accompagnement_model');
		$this->load->model('users_model');
		$data['title']='Administration';
		$data['messages'] = $this->session->flashdata('messages');
		$data['matieres'] = $this->matieres_model->getAll();
		$data['cycles']   = $this->cycles_model->getAll();
		$data['profs']   = $this->users_model->getAllProfs();
		$data['salles']   = $this->accompagnement_model->getAllSalles();
		// $data['accompagnement']   = $this->accompagnement_model->getAllActiveHumanReadable();
		$data['accompagnement']   = $this->accompagnement_model->getAllHumanReadable();
		$this->load->view('templates/header', $data);
		$this->load->view('admin/index', $data);
		$this->load->view('templates/footer');
	}
	
	public function resetBD()
	{
		$this->load->model('users_model');
		$this->users_model->resetBD();
		redirect('admin');
	}
	
	private function prepareArrayforUsers($tab){
		$this->load->model('users_model');
		$data = $this->users_model->check($tab);
		return($data);
	}

	private function prepareArrayforCycles($tab){
		$this->load->model('cycles_model');
		$data = $this->cycles_model->check($tab);
		return($data);
	}
	
	private function prepareArrayforMatieres($tab){
		$this->load->model('matieres_model');
		$data = $this->matieres_model->check($tab);
		return($data);
	}

	public function uploadFile($type)
	{
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'csv';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload($type.'file') )
		{
			$error = array('messages' => $this->upload->display_errors());
			$error['title']='Administration';
			$this->load->view('templates/header', $error);
			$this->load->view('admin/index', $error);
			$this->load->view('templates/footer');
		}
		else
		{ 	
			$uploaded =  $this->upload->data();
			$this->load->library('csvreader');
			$result =   $this->csvreader->parse_file($uploaded['full_path']);
			switch ($type){
				case 'users':
				$data = $this->prepareArrayforUsers($result);
				break;
				case 'cycles':
				$data = $this->prepareArrayforCycles($result);
				break;
				case 'matieres':
				$data = $this->prepareArrayforMatieres($result);
				break;
			}
			$data['type']=$type;
			$this->session->set_userdata('file'.$type, $uploaded['full_path'] ); 
			//$this->load->view('templates/json', $data);
			$this->load->view('templates/header', array("title"=>'CSV '.$type));
			$this->load->view('admin/arrayUpdate', $data); 
			$this->load->view('templates/footer');
		}
	
	     
	}
	
	
	public function cancelUpload(){
		// unlink($this->session->userdata['file'.$type]);
		$files = glob('./uploads/*'); 
		foreach($files as $file){ 
			if(is_file($file))
			unlink($file); 
		}
		redirect('admin');
	}
	
	public function confirm($type){
		//print_r($this->session->userdata['commitData'.$type]);
		//$result = ( $this->session->userdata['commitData'.$type] );
		$this->load->library('csvreader');
		$result = $this->csvreader->parse_file($this->session->userdata['file'.$type]);
		unlink($this->session->userdata['file'.$type]);
		switch ($type){
			case 'users':
			$this->load->model('users_model');
			$data = $this->users_model->commitArray($result);
			break;
			case 'cycles':
			$this->load->model('cycles_model');
			$data = $this->cycles_model->commitArray($result);
			break;
			case 'matieres':
			$this->load->model('matieres_model');
			$data = $this->matieres_model->commitArray($result);
			break;
		}
		$this->session->set_flashdata('messages', 'Fichier des '.$type.' mis à jour');
		redirect('admin');
	}
	
	public function rapport( $cont, $name, $type ){
		$this->load->model('rapports_model');
		$data['title'] = rawurldecode($name);
		switch($cont) {
			case 'Eleves': 
				$report = $this->rapports_model->getListeEleves();
				break;
			case 'Inscriptions':
				$report = $this->rapports_model->getListeInscriptions();
			break;
			case 'Absents': 
				$report = $this->rapports_model->getListeAbsents();
			break;
			case 'NonInscrits': 
				$report = $this->rapports_model->getListeNonInscrits();
			break; 
			case 'Professeurs':
				$report = $this->rapports_model->getProfesseurs();
			break;
			case 'Matieres': 
				$report = $this->rapports_model->getMatieres();
			break;	
		}
		$data['json'] = $report->result_array();
		if ( $type == 'csv' ) {
			$this->load->dbutil();
			$this->load->helper('download');
			$new_report=$this->dbutil->csv_from_result($report, ";", "\n");	
			$new_report=substr( $new_report, strpos($new_report, "\n")+1 );
			$fl = '';
			$keys = array_keys($data['json'][0]);
			foreach ($keys as $key ) {
				$fl = $fl.'"'.ucfirst($key).'";';
			}
			//$fl=rtrim($fl, ";");
			$new_report=$fl."\n".$new_report;	
			force_download("$name.csv", $new_report); 
		} else {
			$this->load->view('templates/header',$data);
			$this->load->view('templates/array',$data);
			$this->load->view('templates/footer');
		}	
	}

}
