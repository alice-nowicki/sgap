<?php
class Inscription extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if ( !isset($this->session->userdata['profil']) ) {
			$data['json']=array('logged'=>false);
			$this->load->view('templates/json', $data);
		};
		if ( $this->session->userdata['profil'] > 0 ) {
			$this->load->model('accompagnement_model');
			$this->load->model('inscriptions_model');
			$this->load->model('matieres_model');
			$this->load->model('cycles_model');
			$this->load->helper(array('datefr'));
			//$this->load->helper(array('form'));
			return(true);
		}  
		$this->session->set_flashdata('messages', "<p>Vos droits actuels sont insuffisants pour afficher la page demandée. Vous avez été redirigé vers l'écran d'authentification.</p>" );
		redirect('user/login');
	}

	public function index()
	{
		return(true);
	}

	public function required($level){
		if ( (!isset($this->session->userdata['profil']) )or (!($this->session->userdata['profil']>=$level))){
			$json['logged']=true;
			$json['success']=false;
			$data['json']=$json;
			$this->load->view('templates/json', $data);	
		} else {
			return(true);			
		}
	}
	
	public function delete($accompagnement_id, $eleve_id){
		$this->required(2);
		$this->inscriptions_model->delete( $accompagnement_id, $eleve_id );
		$json['logged']=true;
		$json['success']=true;
		$data['json']=$json;
		$this->load->view('templates/json', $data);	
	}
	
	public function rencontre( $classe, $groupe, $accompagnement_id ) {
		$this->required(2);
		$this->inscriptions_model->rencontre( $classe, $groupe, $accompagnement_id );
		$json['logged']=true;
		$json['success']=true;
		$data['json']=$json;
		$this->load->view('templates/json', $data);	
	}
	
	public function getNonInscrits($cycle_id){
		$this->required(2);
		$json['liste']=$this->inscriptions_model->getNonInscrits($cycle_id);
		$json['cycle_id']=$cycle_id;
		$json['logged']=true;
		$json['success']=true;
		$data['json']=$json;
		$this->load->view('templates/json', $data);		
	}

	public function getInscrits($cycle_id,$matiere_id) {
		if ( $this->session->userdata['profil'] == 1 ) return(false);
		if ($this->accompagnement_model->isActif($cycle_id,$matiere_id)){
			$inscrits = $this->accompagnement_model->getInscrits($cycle_id,$matiere_id);
			$json['logged']=true;
			$json['inscrits']=$inscrits;
		} else {
			$json=array('logged'=>true);
		}
		$data['json']=$json;
		$this->load->view('templates/json', $data);
	}

	public function getNbPlacesRestantes($cycle_id,$matiere_id) {
		//Il est possible d'optimiser facilement cette partie en sacrifiant un peu de lisibilité : il ne devrait y avoir qu'une seule requête au modèle des accompagnements.
		if ($this->accompagnement_model->isActif($cycle_id,$matiere_id)){
			$tmp = $this->cycles_model->getDatesAndHoraires($cycle_id);
			$dates = $tmp['dates'];
			//$dates=$this->cycles_model->getDates($cycle_id);
			$horaire= $tmp['horaire'];
			foreach ($dates as &$date) $date=datefr($date);
			$nb_dispo=$this->matieres_model->getPlaces($matiere_id);
			$nb_inscrits=$this->accompagnement_model->getNbInscrits($cycle_id,$matiere_id);
			$acc=$this->accompagnement_model->getInfos($cycle_id,$matiere_id);
			$type = $acc['type']==1 ? "Personnalisé" : "Rencontre";
			$salle = $acc['salle'];
			$json=array('places'=>$nb_dispo, 'nb_inscrits'=>$nb_inscrits,'salle'=>$salle, 'dates'=>$dates, 'horaire'=>$horaire, 'accompagnement_id'=>$acc['accompagnement_id'], 'commentaire'=>$acc['commentaire'],'type'=>$type ); 
		} else {
			$json = array('places'=>'.', 'nb_inscrits'=>'.','salle'=>'.', 'dates'=>array('.'),'horaire'=>'.', 'accompagnement_id'=>'', 'commentaire'=>'', 'type'=>'.' );
		}
		$json['logged']=true;
		$data['json']=$json;
		$this->load->view('templates/json', $data);
	}



}
