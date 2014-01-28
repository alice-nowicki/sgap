<?
class Accompagnement_model extends CI_Model {
	
	public function __construct()
	{
		$this->load->database();
	}

	function getAll()
	{
		$query = $this -> db -> get( 'accompagnement' );
		return ( $query->result_array() ); 
	}
	
	
	function getAllHumanReadable()
	{
		$this->db->select('accompagnement.id AS id, cycles.debut AS cycle_debut, cycles.id AS cycle_id , cycles.horaire AS horaire ,matieres.nom AS matiere,matieres.id AS matiere_id, users.nom AS nom , users.prenom AS prenom , matieres.salle AS salle, accompagnement.actif AS actif');
		$this->db->from('accompagnement');
		$this->db->join('matieres', 'matieres.id = accompagnement.matiere_id');
		$this->db->join('users', 'users.id = accompagnement.enseignant_id');
		$this->db->join('cycles', 'cycles.id = accompagnement.cycle_id');
		$this->db->order_by("matieres.nom", "asc"); 
		$this->db->order_by("cycles.debut", "asc");
		$query=$this->db->get();	
		return($query->result_array());
	}
	
	function getAllActiveHumanReadable()
	{
		$this->db->select('accompagnement.id AS id, cycles.debut AS cycle_debut, cycles.id AS cycle_id ,matieres.nom AS matiere,matieres.id AS matiere_id, matieres.type AS matiere_type ,  users.nom AS nom , users.prenom AS prenom , matieres.salle AS salle');
		$this->db->from('accompagnement');
		$this->db->where('accompagnement.actif = 1');
		$this->db->join('matieres', 'matieres.id = accompagnement.matiere_id');
		$this->db->join('users', 'users.id = accompagnement.enseignant_id');
		$this->db->join('cycles', 'cycles.id = accompagnement.cycle_id');
		$this->db->order_by("matieres.nom", "asc");
		$this->db->order_by("cycles.debut", "asc"); 
		$query=$this->db->get();	
		return($query->result_array());
	}

	function getAllActiveWithCyclesAndMatieres()
	{
		$data['accompagnement']=$this->getAllActiveHumanReadable();
		$cycles=array();
		$matieres=array();
		foreach ( $data['accompagnement'] as $acc ){
			array_push( $cycles, array('id'=>$acc["cycle_id"], 'debut'=>$acc['cycle_debut']) );
			array_push( $matieres, array('id'=>$acc["matiere_id"], 'nom'=>$acc['matiere'], 'type'=>$acc['matiere_type']) );
		}
		                                                                          
		$newArr = array();         
		foreach ($cycles as $val) {
		    $newArr[$val['id']] = $val;    
		}
		$data['cycles'] = array_values($newArr);
		$newArr = array();
		foreach ($matieres as $val) {
		    $newArr[$val['id']] = $val;    
		}
		$data['matieres'] = array_values($newArr);
		return($data);
	}
	
	function isActif($cycle_id,$matiere_id){
		$accompagnement = array('matiere_id'=>$matiere_id,'cycle_id'=>$cycle_id,'actif'=>true );
		$this->db->select('accompagnement.id');
		$this->db->from('accompagnement');
		$this->db->where($accompagnement);
		$this->db->limit(1);
		$query=$this->db->get();
		if ($query->num_rows() == 1 ) { 
			return(true);
		} else {
			return(false);
		}
	}
	
	function getSalle($cycle_id,$matiere_id)
	{
		$accompagnement = array('matiere_id'=>$matiere_id,'cycle_id'=>$cycle_id );
		$this->db->select('salle');
		$this->db->limit(1);
		$this->db->from('accompagnement');
		$this->db->where($accompagnement);
		$query=$this->db->get();
		$res = $query->row_array();
		return( $res['salle'] ) ;	
	}

	function getInfos($cycle_id,$matiere_id)
	{
		$accompagnement = array('matiere_id'=>$matiere_id,'cycle_id'=>$cycle_id );
		$this->db->select('accompagnement.salle, accompagnement.id AS accompagnement_id, accompagnement.commentaire, matieres.type AS type');
		$this->db->join('matieres', 'matieres.id = accompagnement.matiere_id');
		$this->db->limit(1);
		$this->db->from('accompagnement');
		$this->db->where($accompagnement);
		$query=$this->db->get();
		$res = $query->row_array();
		return( $res ) ;	
	}

	public function getInscrits($cycle_id,$matiere_id)
	{
		$accompagnement = array('matiere_id'=>$matiere_id,'cycle_id'=>$cycle_id );
		$this->db->select('users.id AS eleve_id, users.nom AS nom, users.prenom AS prenom, users.classe AS classe');
		$this->db->from('inscriptions');
		$this->db->where($accompagnement);
		$this->db->join('accompagnement', 'accompagnement.id = inscriptions.accompagnement_id');
		$this->db->join('users', 'inscriptions.eleve_id = users.id');
		$query=$this->db->get();	
		$res=$query->result_array();
		return($res);	
	}

	function getNbInscrits($cycle_id,$matiere_id)
	{	
		$accompagnement = array('matiere_id'=>$matiere_id,'cycle_id'=>$cycle_id );
		$this->db->select('inscriptions.id');
		$this->db->from('inscriptions');
		$this->db->where($accompagnement);
		$this->db->join('accompagnement', 'accompagnement.id = inscriptions.accompagnement_id');
		$query=$this->db->get();	
		return($query->num_rows());	
	}
	
	function getId($cycle_id, $matiere_id)
	{
		$accompagnement = array('matiere_id'=>$matiere_id,'cycle_id'=>$cycle_id );
		$query = $this->db->get_where('accompagnement',$accompagnement,1);
		if ($query->num_rows() == 1 ) { 
			$res = $query->row_array();
			return( $res['id'] ) ;
		} else {
			$res = -1; 
			return($res);
			//$this->db->insert('accompagnement', $accompagnement);
			//return( $this->db->insert_id() );
		}
	}
	
	function creer($cycle_id,$matiere_id,$enseignant_id,$salle) 
	{
		$accompagnement = array('matiere_id'=>$matiere_id,'cycle_id'=>$cycle_id , 'salle'=>$salle, 'enseignant_id'=>$enseignant_id);
		$this->db->insert('accompagnement', $accompagnement);
		$accompagnement_id = $this->db->insert_id();
		//Insertion des seances correspondantes. 
		$this->load->model('seances_model');
		$this->seances_model->creer($accompagnement_id,$cycle_id,$enseignant_id);
	} 	
	
	function supprimer($id)
	{
		$this->db->delete('accompagnement',array('id'=>$id));
		$this->db->delete('seances',array('accompagnement_id'=>$id));
		$this->db->delete('inscriptions',array('accompagnement_id'=>$id));
	}
	
	function inactiver($id)
	{
		$this->db->where('id', $id);
		$this->db->update('accompagnement', array('actif'=>false)); 
		
	}
	
	function activer($id)
	{
		$this->db->where('id', $id);
		$this->db->update('accompagnement', array('actif'=>true)); 
	}
	
	function getAllSalles()
	{
		$this->db->distinct();
		$this->db->select('salle');
		$this->db->from('matieres');
		$this->db->order_by("salle"); 
		$query=$this->db->get();
		$res=$query->result_array();
		return($res);	
	}
	
	function setCommentaire($accompagnement_id, $commentaire)
	{
		$this->db->where(array('id'=>$accompagnement_id));
		$this->db->update('accompagnement', array("commentaire"=>$commentaire) );
		return(true);
	}
	
}
