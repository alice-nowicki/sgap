<?php
class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
		$this->load->helper(array('form'));
	}

	public function index()
	{
		redirect('user/login');
	}

	public function login()
	{		
		$data['title'] = "SGAP login";	
		$data['messages'] = $this->session->flashdata('messages');
		$this->load->view('templates/header', $data);
		$this->load->view('user/login');
		$this->load->view('templates/footer');
	}
	
	public function checkLogin(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('login', 'login', 'trim|required|xss_clean');
		$this->form_validation->set_rules('passwd', 'passwd','trim|required'); 
		if($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('messages', validation_errors());
			redirect('user/login');
		}
		$login = $this->input->post('login');
		$passwd = $this->input->post('passwd');
		if ($this->users_model->login($login,$passwd) ) {
			$user = $this->users_model->getUser($login);
			$this->session->set_userdata($user);
			$this->goToMainPage();
		} else {
			$this->session->set_flashdata('messages', '<p>Couple login/password incorrect</p>' );
			redirect('user/login');
		}
	}
	
	public function logout(){
		$this->session->sess_destroy();
		redirect('user/login');
	}
	
	private function goToMainPage(){
		if ( !isset($this->session->userdata['profil']) ) redirect('user/login');
		$profil = $this->session->userdata['profil'];
		switch ($profil) {
			case 1:
			redirect('eleve/');
			break;
			case 2:
			case 3:
			redirect('enseignant/');
			break;
			case 4:
			redirect('admin/');
			break;	
			default:
			echo "User avec status incorrect. La BD aurait du refuser cette insertion. Contactez un administateur avec une copie de ce message. ";
			print_r($this->session->userdata['id']);
		}	
	}
	
	public function changePasswd(){	
		if (count($_POST) > 0) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('opasswd', 'opasswd','trim|required|xss_clean'); 
			$this->form_validation->set_rules('npasswd', 'npasswd','trim|required|xss_clean'); 
			if($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('messages', validation_errors());
				redirect('user/changePasswd');
			}
			$login = $this->session->userdata('login');
			$passwd = $this->input->post('opasswd');
			if ($this->users_model->login($login,$passwd) ) { 
				$this->users_model->updatePassword($login,  $this->input->post('npasswd') );
				$this->session->set_flashdata('messages', '<p>Mot de passe modifié.</p>' );
				$this->goToMainPage();	
			} else {
				$this->session->set_flashdata('messages', '<p>Ancien mot de passe incorrect.</p>' );
				redirect('user/changePasswd');
			}
		} 
		$data['title'] = "Changement de mot de passe";
		$data['messages'] = $this->session->flashdata('messages');
		$this->load->view('templates/header', $data);
		$this->load->view('user/changepasswd');
		$this->load->view('templates/footer');	
	}
	
	public function reset() {
		if (count($_POST) > 0) {		
			$this->load->library('form_validation');
			$this->form_validation->set_rules('mail', 'mail', 'trim|required|xss_clean');
			if ($this->form_validation->run() == FALSE) {
				$this->session->set_flashdata('messages', validation_errors());
			} else {
				$mail = $this->input->post('mail');
				$result = $this->users_model->resetPassword($mail);
				if ($result==false){
					$this->session->set_flashdata('messages', 'Le mail entré ne trouve aucun homologue dans la base de données');
				}else{
					$this->session->set_flashdata('mail', $mail);
					$this->session->set_flashdata('login', $result['login']);
					$this->session->set_flashdata('password', $result['password']);
					redirect('gestionmail/mailreinit');
				}
			}
		}
		$data['title'] = "Réinitialisation de mot de passe";
		$data['messages'] = $this->session->flashdata('messages');
		$this->load->view('templates/header', $data);
		$this->load->view('user/reset');
		$this->load->view('templates/footer');	
	}
}
