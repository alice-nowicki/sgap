<?php
class Eleve extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ( !isset($this->session->userdata['profil']) ) redirect('user/login');
		if ( $this->session->userdata['profil'] > 0 ) {
			$this->load->model(array('inscriptions_model','accompagnement_model','seances_model', 'cycles_model'));
			$this->load->helper(array('form','datefr'));
			//$this->output->enable_profiler(TRUE);
			return(true);
		}  
		$this->session->set_flashdata('messages', "<p>Vos droits actuels sont insuffisants pour afficher la page demandée. Vous avez été redirigé vers l'écran d'authentification.</p>" );
		redirect('user/login');
	}

	public function index()
	{
		$data = $this->accompagnement_model->getAllActiveWithCyclesAndMatieres();
		$data['title']='Élève';
		$data['messages'] = $this->session->flashdata('messages');
		$eleve_id = $this->session->userdata['id'];
		$data['eleve_id'] = $eleve_id;
		$data['historiqueAccompagnements'] = $this->inscriptions_model->getHistory($eleve_id);
		$data['historiqueSeances'] = $this->seances_model->historique($eleve_id);
		$this->load->view('templates/header', $data);
		$this->load->view('eleve/selection', $data);
		$this->load->view('eleve/infos', $data);
		$this->load->view('eleve/js', $data);
		$this->load->view('templates/footer');
	}

	public function inscription() {
		if (count($_POST)==0) redirect('eleve/');
		// Faire une validation supplémentaire ? => Bof 
		$cycle_id = $this->input->post('cycle_id');
		$matiere_id = $this->input->post('matiere_id');
		$accompagnement_id = $this->accompagnement_model->getId($cycle_id,$matiere_id);
		if ($accompagnement_id<0){
			$this->session->set_flashdata('messages', 'Accompagnement non ouvert.' );
			redirect('eleve/');
		}
		$eleve_id = $this->session->userdata['id'];
		$ins = $this->inscriptions_model->inscrire($eleve_id,$accompagnement_id); 
		$this->session->set_flashdata('messages', $ins['message'] );
		redirect('eleve/');
	}



}
