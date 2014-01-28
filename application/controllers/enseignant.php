<?php
class Enseignant extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ( !isset($this->session->userdata['profil']) ) redirect('user/login');
		if ( $this->session->userdata['profil'] > 1 ) {
			$this->load->model(array('inscriptions_model','accompagnement_model','seances_model'));
			$this->load->helper(array('form','datefr'));
			return(true);
		}  
		
		$this->session->set_flashdata('messages', "<p>Vos droits actuels sont insuffisants pour afficher la page demandée. Vous avez été redirigé vers l'écran d'authentification.</p>" );
		redirect('user/login');
	}

	public function index()
	{
		$data = $this->accompagnement_model->getAllActiveWithCyclesAndMatieres();
		$data['cg']=$this->inscriptions_model->getGroups();
		$data['title']='Enseignant(e)';
		$data['messages'] = $this->session->flashdata('messages'); 
		$prof_id=$this->session->userdata['id']; 
		$data['seancesProf'] = $this->seances_model->getSeancesOf($prof_id);
		$this->load->view('templates/header', $data);
		$this->load->view('enseignant/index', $data);
		$this->load->view('templates/footer', $data);
	}




}
