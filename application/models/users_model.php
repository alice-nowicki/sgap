<?php
class Users_model extends CI_Model {
	
	public function __construct()
	{
		$this->load->database();
	}
	
	function login($login, $password)  
	{
		$this->load->helper('security');
		$query = $this -> db -> get_where( 'users', array('login'=> $login, 'passwd'=>do_hash($password) ), 1 );
		if ($query->num_rows() == 1 ) {
			$this->db->where('login', $login);
			$this->db->update('users', array("lastlogin"=>date('Y-m-d H:i:s') ) );
			return(true);
		} else {
			return(false);
		}
	}
	
	function getNameOf($user_id){
		$this->db->select('id, nom, prenom, mail');
		$this->db->from('users');
		$this->db->where(array('id'=>$user_id));
		$query=$this->db->get();
		$res=$query->result_array();
		return($res);	
	}
	
	function getAllProfs()
	{
		$this->db->select('id, nom, prenom, mail');
		$this->db->from('users');
		$this->db->where( array('profil >='=>2) );
		// $this->db->or_where( array('profil'=>3) );
		$this->db->order_by("nom", "asc");
		$query=$this->db->get();
		$res=$query->result_array();
		return($res);
	}
	
	function getAllEleves()
	{
		$this->db->select('id, nom, prenom,classe, profil, groupe, mail, mail_parent');
		$this->db->from('users');
		$this->db->where(array('profil'=>1));
		$query=$this->db->get();
		$res=$query->result_array();
		return($res);	
	}
		
	function getUser( $login ) 
	{
		$this->db->select('id, nom, prenom, login, classe, profil, groupe, mail, mail_parent');
		$this->db->from('users');
		$this->db->where( array('login'=> $login ) );
		$this->db->limit(1);
		$query=$this->db->get();
		return ( $query->row_array() ); 
	}
	
	function updatePassword($login,$passwd){
		$this->load->helper('security');
		$this->db->where('login', $login);
		$this->db->update('users', array("passwd"=>do_hash($passwd)) ); 
	}

	function resetPassword($mail) {
		$this->db->select('login');
		$this->db->from('users');
		$this->db->where('mail', $mail);
		$this->db->limit(1);
		$query=$this->db->get();
		if ($query->num_rows() == 1) {
			$resultat = $query->row_array();
			$resultat['password'] = '';
			for ($i = 0; $i < 6; $i++) {
				$random = rand(97, 122);
				$resultat['password'] = $resultat['password'] . chr($random);
			}
			$this->updatePassword($resultat['login'], $resultat['password']);
			return($resultat);
		} else {
			return(false);
		}
	}
	
	
	function commitUser( $user )
	{
		$nb = $this->db->count_all('users');	
		//Ajouter des vérifications de cohérence ? 
		$this->load->helper('string');
		$user['passwd']=random_string('alnum',8);
		$user['actif']=1;
		$dup ='  ON DUPLICATE KEY UPDATE';
		$fieldsToUpdate = array('prenom','nom','mail','mail_parent','profil','classe','groupe','actif' );
		foreach ( $fieldsToUpdate as $champ ) $dup .= " `$champ`=VALUES(`$champ`)," ;
		$dup = rtrim($dup, ",");
		$dup .= ';';
		$sql = $this->db->insert_string('users', $user) . $dup;
		$this->db->query($sql);
		if ($nb != $this->db->count_all('users') ) { 
			//This was a new user 
			$this->sendPasswordMail( $user );
		}
		//$id = $this->db->insert_id();
	}
		
	function commitArray($tab)
	{
		//Inactive all users not present in array. It will temporally blocks logins
		$this->db->update('users', array('actif'=>0) ); 
		foreach ($tab as $user ){
			$this->commitUser($user);
		}
	}	
		
	function check( $tab )
	{
		//Quick and dirty. I just assume it's not used often not for big files
		$data['ajouts']=array();
		$data['errors']=array();
		$data['modifications']=array();
		$data['desactive']=array();	
		$logins = array();
		foreach ($tab as $row ){
			array_push( $logins, $row['login'] );
		}
			
		$this -> db -> select('nom, prenom, mail, mail_parent, profil, classe, groupe, login');
		$this -> db -> from('users');
		$query = $this -> db -> get();
		$allUsers= $query->result_array();
		$dblogins = array();
		foreach( $allUsers as $user ){
			array_push( $dblogins, $user['login'] );
			if ( ! in_array( $user['login'], $logins ) ) {
				array_push( $data['desactive'], $user );
			}
		}
		foreach( $tab as $user ){
			if (  in_array( $user['login'], $dblogins ) ) { 
				array_push( $data['modifications'], $user );
			} else {
				array_push( $data['ajouts'], $user );
			}
		}
		
		return($data);
	}

	function resetBD()
	{
		$this->db->empty_table('accompagnement');
		$this->db->empty_table('cycles');
		$this->db->empty_table('inscriptions');
		$this->db->empty_table('matieres');
		$this->db->empty_table('presences');
		$this->db->empty_table('rappel');
		$this->db->empty_table('seances');
		$this->db->where( array( "login !="=>$this->session->userdata['login'] ) );
		$this->db->delete('users');
	}	
	
}
