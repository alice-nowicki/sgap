<?
class Seances_model extends CI_Model {
	
	public function __construct()
	{
		$this->load->database();
	}

	function creer($accompagnement_id,$cycle_id,$enseignant_id) 
	{
		$this->load->model('cycles_model');
		$dates = $this->cycles_model->getDates($cycle_id);
		foreach($dates as $date){
			$date=date_create_from_format("d/m/Y",$date);
			$timestamp=$date->getTimestamp(); 
			$date=strftime( "%Y-%m-%d", $timestamp );
			$seance = array( 'accompagnement_id'=>$accompagnement_id,'date'=>$date, 'enseignant_id'=>$enseignant_id);
			$this->db->insert('seances', $seance);
		}
	} 	
	
	function getIdsAndDates($cycle_id, $matiere_id)
	{
		$accompagnement = array('accompagnement.matiere_id'=>$matiere_id,'accompagnement.cycle_id'=>$cycle_id );
		// ,'accompagnement.actif'=>1
		$this->db->select('seances.id AS seance_id, seances.date AS date, seances.validee, cycles.horaire, accompagnement.id AS accompagnement_id');
		$this->db->from('seances');
		$this->db->where($accompagnement);
		$this->db->join('accompagnement', 'accompagnement.id = seances.accompagnement_id');
		$this->db->join('cycles', 'accompagnement.cycle_id = cycles.id');
		$query=$this->db->get();	
		$res=$query->result_array();
		return($res);	
		
	}
	
	function getIdsAndDatesWithProfs($cycle_id, $matiere_id)
	{
		$accompagnement = array('accompagnement.matiere_id'=>$matiere_id,'accompagnement.cycle_id'=>$cycle_id );
		// ,'accompagnement.actif'=>1
		$this->db->select('seances.id AS seance_id, seances.date AS date, seances.validee, cycles.horaire, accompagnement.id AS accompagnement_id, users.nom AS nom_prof, users.prenom AS prenom_prof');
		$this->db->from('seances');
		$this->db->where($accompagnement);
		$this->db->join('accompagnement', 'accompagnement.id = seances.accompagnement_id');
		$this->db->join('cycles', 'accompagnement.cycle_id = cycles.id');
		$this->db->join('users', 'seances.enseignant_id = users.id');
		$query=$this->db->get();	
		$res=$query->result_array();
		return($res);	
		
	}
	
	function setAbsence($seance_id, $eleve_id, $abs){
		if ($abs == 'true'){
			$data = array(
			   'absent' => true ,
			   'seance_id' => $seance_id ,
			   'eleve_id' => $eleve_id
			);
			$this->db->insert('presences', $data);
		}	else {
			$this->db->delete('presences', array('seance_id' => $seance_id,
				 								'eleve_id' => $eleve_id ) );
		}
		return(true);
	}
	
	function getPresences($seance_id)
	{
		$this->db->select('inscriptions.eleve_id AS eleve_id, presences.absent AS absent, users.nom, users.prenom, users.classe, users.groupe, seances.id AS seance_id, inscriptions.commentaire AS commentaire, inscriptions.accompagnement_id' );
		$this->db->from('inscriptions');
		$this->db->where(array('seances.id'=>$seance_id));
		$this->db->join('seances', 'inscriptions.accompagnement_id = seances.accompagnement_id');
		$this->db->join('presences', 'presences.seance_id = seances.id AND presences.eleve_id = inscriptions.eleve_id', 'left');
		$this->db->join('users', 'users.id=inscriptions.eleve_id');
		$this->db->order_by("nom", "asc");
		$query=$this->db->get();	
		$res=$query->result_array();
		return($res);	
	}
	
	function valider($seance_id){
		$this->db->where('id', $seance_id);
		$this->db->update('seances', array("validee"=>true) ); 
	}
	
	function historique($eleve_id)
	{
		$this->db->select('presences.eleve_id AS eleve_id, seances.id AS seance_id, seances.date AS seance_date, matieres.nom AS matiere_nom, cycles.debut AS cycle_debut, cycles.actif AS actif');
		$this->db->from('presences');
		$this->db->join('seances', 'presences.seance_id = seances.id');
		$this->db->join('accompagnement', 'seances.accompagnement_id = accompagnement.id');
		$this->db->join('cycles', 'accompagnement.cycle_id = cycles.id');
		$this->db->join('matieres', 'matieres.id = accompagnement.matiere_id');
		$this->db->where(array('seances.validee'=>true));
		$this->db->where(array('cycles.actif'=>true));
		$this->db->where(array('presences.absent'=>true));
		$this->db->where(array('presences.eleve_id'=>$eleve_id));
		$query=$this->db->get();	
		$res=$query->result_array();
		return($res);	
	}
	
	function getSeancesOf($prof_id)
	{
		$this->db->select('users.nom, prenom AS prénom, matieres.nom AS matière, seances.date, seances.id AS seance_id, matieres.id AS matiere_id, cycle_id, cycles.horaire AS horaire, accompagnement.salle');
		$this->db->from('users');
		$this->db->join('seances', 'seances.enseignant_id = users.id');
		$this->db->join('accompagnement', 'accompagnement.id = seances.accompagnement_id');
		$this->db->join('cycles', 'accompagnement.cycle_id = cycles.id');
		$this->db->join('matieres', 'matieres.id = accompagnement.matiere_id');
		$this->db->where(array('users.id'=>$prof_id));
		$this->db->where(array('accompagnement.actif'=>1));
		$this->db->order_by("date");
		$query=$this->db->get();
		$res=$query->result_array();
		return($res);	
	}
	
	function setCommentaire($accompagnement_id, $eleve_id, $commentaire)
	{
		$this->db->where(array('accompagnement_id'=>$accompagnement_id, 'eleve_id'=>$eleve_id));
		$this->db->update('inscriptions', array("commentaire"=>$commentaire) );
		return(true);
	}
	
	function setProfesseur($seance_id, $enseignant_id)
	{
		$this->db->where(array('id'=>$seance_id ));
		$this->db->update('seances', array("enseignant_id"=>$enseignant_id) );
		return(true);
	}
	
	function setProfesseurByDay($accompagnement_id, $enseignant_id, $date)
	{
		/*
		$this->db->where(array('accompagnement_id'=>$accompagnement_id ));
		$this->db->where(array('date'=>$date )); //Il faudrait un dayoftheweek => non dispo
		$this->db->update('seances', array("enseignant_id"=>$enseignant_id) );
		*/
		// Manque une sanitization mais vu que c'est une requête admin...
		$q = "UPDATE seances SET enseignant_id = $enseignant_id WHERE accompagnement_id = $accompagnement_id AND  DAYOFWEEK(date) = DAYOFWEEK('$date')  ";
		$this->db->query($q);
		
		return(true);
	}
}
