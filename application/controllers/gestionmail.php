<?php
class Gestionmail extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('gestionmail_model');
		$this->load->model('accompagnement_model');
		$this->load->model('inscriptions_model');
		$this->load->model('matieres_model');
		$this->load->model('cycles_model');
		$this->load->helper(array('datefr'));
		$this->load->library('email');
		
	
		
	}

	public function index()
	{
		return(true);
	}



	public function checkDateCycle(){
		$jetonrappel=$this->gestionmail_model->getrappel();
		if ($jetonrappel) {
			$this->load->library('email');
			$listeCycle=$this->cycles_model->cycleSoon();
			foreach ($listeCycle as $cycle) {
				$listeNonInscrits=$this->inscriptions_model->getNonInscrits($cycle);
				foreach ($listeNonInscrits as $eleve){
					$message = "vous devez vous inscrire ".$eleve['nom']." au cycle ".$cycle ;
					$from = 'alice.nowicki0@gmail.com';
					$to = $eleve['mail'];
					$listeE = $this->gestionmail_model->sendMail($from,$to,$message);
					
				}
			}
			return $listeE;
		}else{
			return "email deja envoye";
		}
	}

	

	public function mailreinit(){
		$mail = $this->session->flashdata('mail');
		$login = $this->session->flashdata('login');
		$password = $this->session->flashdata('password');
		$this->load->library('email');
		$this->email->clear();
		$this->email->from('sophie.lapersonne@gmail.com', 'Administrateur');
		$this->email->to($mail);
		$this->email->subject('Réinitialisation de mot de passe');
		$this->email->message('Votre mot de passe a été réinitialisé. Vous pouvez vous connecter sur le site grace au login suivant "' . $login . '" et au mot de passe suivant "' . $password . '". Vous pourrez changer ce mot de passe une fois connecté sur le site.');
		if ($this->email->send()) {
			$this->session->set_flashdata('messages', 'Votre mot de passe a été réinitialisé et un mail vous a été envoyé.');
		} else {
			$this->session->set_flashdata('messages', 'Echec de l\'envoi du mail pour la réinitialisation du mot de passe.');
		}
		redirect('user/login');
	}

}
