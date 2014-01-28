<?
class Inscriptions_model extends CI_Model {
	
	public function __construct()
	{
		$this->load->database();
	}
	
	function getAll()
	{
		$query = $this -> db -> get( 'inscriptions' );
		return($query->result_array()); 
	}
	
	function getNonInscrits($cycle_id){
		$this->db->select('users.id');
		$this->db->from('users');
		$this->db->join('inscriptions', 'users.id=inscriptions.eleve_id');
		$this->db->join('accompagnement', 'accompagnement.id = inscriptions.accompagnement_id');
		$this->db->join('cycles', 'cycles.id = accompagnement.cycle_id');
		$this->db->where(array('cycles.id'=>$cycle_id));
		$query=$this->db->get();
		$inscrits=$query->result_array();
		
		$this->db->select('users.id AS eleve_id, nom, prenom, classe, groupe');
		$this->db->from('users');
		$this->db->where(array('profil'=>1));
		$this->db->where_not_in(array('users.id'=>$inscrits));
		$this->db->order_by("nom", "asc");
		$query=$this->db->get(); 
		$res=$query->result_array();
		return($res);	
	}
	
	function delete( $accompagnement_id, $eleve_id )
	{
		$this->db->delete('inscriptions', array('eleve_id' => $eleve_id, 'accompagnement_id'=>$accompagnement_id)); 
		//Supprimer aussi les absences et commentaires ? 
	}
	
	function rencontre( $classe, $groupe, $accompagnement_id ) {
		$this->db->select('users.id AS eleve_id');
		$this->db->from('users');
		$this->db->where(array('classe'=>$classe, 'groupe'=>$groupe));
		$query=$this->db->get(); 
		$inscriptions=$query->result_array();
		foreach ($inscriptions as &$ins){
			$ins['accompagnement_id']=$accompagnement_id ;
		}
		$this->db->insert_batch('inscriptions', $inscriptions );
	}
	
	function getGroups(){
		$this->db->select('classe, groupe');
		$this->db->distinct();
		$this->db->from('users');
		$this->db->where(array('profil'=>1));
		$this->db->order_by("classe", "asc");
		$this->db->order_by("groupe", "asc");
		$query=$this->db->get(); 
		$res=$query->result_array();
		return($res);	
	}
	
	function getHistory($eleve_id)
	{
		$this->db->select('cycles.id AS cycle_id, cycles.debut AS cycle_debut, matieres.id AS matiere_id , matieres.nom AS matiere_nom, accompagnement.commentaire AS commentaire_general, inscriptions.commentaire AS commentaire_perso');
		$this->db->from('inscriptions');
		$this->db->where(array('eleve_id'=>$eleve_id));
		$this->db->join('accompagnement', 'accompagnement.id = inscriptions.accompagnement_id');
		$this->db->join('matieres', 'matieres.id = accompagnement.matiere_id');
		$this->db->join('cycles', 'cycles.id = accompagnement.cycle_id');
		$query=$this->db->get();
		$res=$query->result_array();
		return($res);	
	}
	
	
	function validate($eleve_id,$accompagnement_id)
	{
		//Verification places  
		$this->db->select('inscriptions.id');
		$this->db->from('inscriptions');
		$this->db->where(array('inscriptions.accompagnement_id'=>$accompagnement_id));
		$query=$this->db->get();	
		$nb_inscrits = ($query->num_rows());	
		
		$this->db->select('matieres.places, cycles.debut, cycles.id AS cycle_id');
		$this->db->from('matieres');
		$this->db->join('accompagnement', 'accompagnement.matiere_id = matieres.id');
		$this->db->join('cycles', 'accompagnement.cycle_id = cycles.id');
		$this->db->where(array('accompagnement.id'=>$accompagnement_id));
		$this->db->limit(1);
		$query=$this->db->get();
		$foo=($query->result_array());	
		$foo=$foo[0];
		$nb_places = $foo['places'];
		
		if ($nb_inscrits >= $nb_places ) {
			return ( array( 'valid'=>false, 'msg'=>"<p>Plus de places disponibles dans cet accompagnement !</p>") );
		}
		
		//Verification date
		$date_strfr = $foo['debut'];
		$date = str_replace('/', '-', $date_strfr);
		$nbSecAvantDebut = strtotime($date)-time();
		if ($nbSecAvantDebut < 0 ) {
			return ( array( 'valid'=>false, 'msg'=>"<p>Cet accompagnement a déjà commencé. L'inscription ne peut être réalisée que par un administrateur.</p>") );
		}
		
		//Verification inscription courante au parcours rencontre pour le cycle sélectionné
		$cycle_id = $foo['cycle_id'];
		$this->db->select('inscriptions.id');
		$this->db->from('inscriptions');
		$this->db->join('accompagnement', 'accompagnement.id = inscriptions.accompagnement_id');
		$this->db->join('matieres', 'matieres.id = accompagnement.matiere_id');
		$this->db->where(array('cycle_id'=>$cycle_id));
		$this->db->where(array('matieres.type'=>2));
		$this->db->limit(1);
		$query=$this->db->get();	
		$rencontre = ($query->num_rows());
		
		if ($rencontre > 0 ) {
			return ( array( 'valid'=>false, 'msg'=>"<p>Vous êtes inscrit au parcours rencontre pour ce cycle, vous ne pouvez pas suivre un autre accompagnement pour ce cycle. </p>") );
		}
		
		return ( array( 'valid'=>true, 'msg'=>'') );
	}
	
	function inscrire($eleve_id,$accompagnement_id,$force=false)
	{
		$this->db->trans_start();
		if ( !$force ){ 
			$foo=$this->validate($eleve_id,$accompagnement_id);
			if (!$foo['valid']){
				return(array('success'=>false,'message'=>$foo['msg']));
			}
		}
		$inscription=array('accompagnement_id'=>$accompagnement_id,'eleve_id'=>$eleve_id);
		$query = $this->db->get_where('inscriptions',$inscription,1);
		if ($query->num_rows() == 0) {
			$this->db->insert('inscriptions', $inscription);
			$this->db->trans_complete(); 
			return(array('success'=>true,'message'=>'<p>Inscription validée.</p>'));
		 } else {
			$this->db->trans_complete();  
			return(array('success'=>false,'message'=>'<p>Vous êtes déjà inscrit !</p>'));
		 }
		
	}
		
	
	
}
