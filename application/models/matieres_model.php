<?
class Matieres_model extends CI_Model {
	
	public function __construct()
	{
		$this->load->database();
	}
	
	function getAll(){
		//$query = $this->db->get('matieres');
		$this->db-> select('id, nom, salle, niveau');
		$this->db-> from('matieres');
		$this->db->order_by("nom", "asc");
		$query = $this -> db -> get();
		return($query->result_array()); 
	}
	
	
	function getPlaces($matiere_id)
	{
		/*
		$this->db-> select('id, nom, salle');
		$this->db-> limit(1);
		$this->db-> from('matieres');
		$query = $this -> db -> get();*/
		$query=$this->db->get_where('matieres',array('id'=>$matiere_id),1);
		$res=$query->row_array();
		return($res['places']);
	} 
	
	function commitMatiere( $matiere )
	{
		//Ajouter des vérifications de cohérence ? 
		$matiere['actif']=1;
		$dup ='  ON DUPLICATE KEY UPDATE';
		$fieldsToUpdate = array('type','niveau','places','actif','salle' );
		foreach ( $fieldsToUpdate as $champ ) $dup .= " `$champ`=VALUES(`$champ`)," ;
		$dup = rtrim($dup, ",");
		$dup .= ';';
		$sql = $this->db->insert_string('matieres', $matiere) . $dup;
		$this->db->query($sql);
	}
		
	
	
	function commitArray($tab)
	{
		//Inactive all users not in array. It will temporarly break everything
		$this->db->update('matieres', array('actif'=>0) ); 
		foreach ($tab as $matiere ){
			$this->commitMatiere( $matiere );
		}
	}	
		
	function check( $tab )
	{
		//Quick and dirty. I just assume it's not used often nor for big files
		$data['ajouts']=array();
		$data['modifications']=array();
		$data['desactive']=array();	
		$noms = array();
		foreach ($tab as $row ){
			array_push( $noms, $row['nom'] );
		}
			
		$this -> db -> select('nom,');
		$this -> db -> from('matieres');
		$query = $this -> db -> get();
		$allmatieres= $query->result_array();
		$dbnoms = array();
		foreach( $allmatieres as $matiere ){
			$nom = $row['nom'];
			array_push( $dbnoms, $nom );
			if ( ! in_array( $nom, $dbnoms ) ) {
				array_push( $data['desactive'], $matiere );
			}
		}
		foreach( $tab as $matiere ){
			$nom = reset($matiere);
			if (  in_array( $nom, $dbnoms ) ) { 
				array_push( $data['modifications'], $matiere );
			} else {
				array_push( $data['ajouts'], $matiere );
			}
		}
		return($data);
	}
	
}