<?php
class Accompagnement extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ( !isset($this->session->userdata['profil']) ) redirect('user/login');
		if ( $this->session->userdata['profil'] > 3 ) {
			$this->load->model('accompagnement_model');
			return(true);
		}  
		$this->session->set_flashdata('messages', "<p>Vos droits actuels sont insuffisants pour afficher la page demandée. Vous avez été redirigé vers l'écran d'authentification.</p>" );
		redirect('user/login');
	}

	public function index()
	{
		return(true);
	}

	public function creer() {
		$cycle_id=$this->input->post('cycle_id',TRUE);
		$matiere_id=$this->input->post('matiere_id',TRUE);
		$enseignant_id=$this->input->post('enseignant_id',TRUE);
		$salle=$this->input->post('salle');
		$this->accompagnement_model->creer($cycle_id,$matiere_id,$enseignant_id,$salle);
		$this->session->set_flashdata('messages', "<p>Accompagnement créé.</p>" );
		redirect('admin/');
	}
	
	public function setCommentaire($id){
		$commentaire=$this->input->post('commentaire',TRUE);
		$success=$this->accompagnement_model->setCommentaire($id, $commentaire);
		$json['logged']=true;
		$json['success']=$success;
		$data['json']=$json;
		$this->load->view('templates/json', $data);	
	}
	
	public function supprimer($id)
	{
		$this->accompagnement_model->supprimer($id);
		$data['json']=array('success'=>true);
		$this->load->view('templates/json', $data);
	}
	
	public function inactiver($id)
	{
		$this->accompagnement_model->inactiver($id);
		$data['json']=array('success'=>true);
		$this->load->view('templates/json', $data);
	}
	
	public function activer($id)
	{
		$this->accompagnement_model->activer($id);
		$data['json']=array('success'=>true);
		$this->load->view('templates/json', $data);
	}


}
