<?
class Cycles_model extends CI_Model {
	
	public function __construct()
	{
		$this->load->database();
	}
	
	function getAll(){
		//$query = $this -> db -> get( 'cycles' );
		$this->db-> select('id, debut, dates, actif, horaire');
		$this->db-> from('cycles');
		$this->db-> where(array('actif'=>true));
		$this->db-> order_by("debut", "asc");
		$query = $this->db->get();
		$cycles = $query->result_array();
		for ($i=0; $i<count($cycles); $i++) {
			$cycles[$i]['dates'] = unserialize($cycles[$i]['dates']);
		}
		return ( $cycles ); 
	}
	
	function getDates($cycles_id)
	{
		$this->db-> select('dates');
		$this->db-> limit(1);
		$this->db-> where(array('id'=>$cycles_id));
		$this->db-> from('cycles');
		$query=$this->db->get();
		$cycle=$query->row_array();
		return(unserialize($cycle['dates']));
	}
	
	function getDatesAndHoraires($cycles_id)
	{
		$this->db-> select('dates, horaire');
		$this->db-> limit(1);
		$this->db-> where(array('id'=>$cycles_id));
		$this->db-> from('cycles');
		$query=$this->db->get();
		$cycle=$query->row_array();
		return(array( 'horaire'=>$cycle['horaire'],'dates'=>unserialize($cycle['dates'])));
	}
	
	function commitCycle( $cycle )
	{
		//Ajouter des vérifications de cohérence ? 
		$cycle['actif']=1;
		$dup ='  ON DUPLICATE KEY UPDATE';
		$fieldsToUpdate = array('dates','actif','horaire' );
		foreach ( $fieldsToUpdate as $champ ) $dup .= " `$champ`=VALUES(`$champ`)," ;
		$dup = rtrim($dup, ",");
		$dup .= ';';
		$sql = $this->db->insert_string('cycles', $cycle) . $dup;
		$this->db->query($sql);
		//$cycle_id=$this->db->insert_id();
	}
		
	
	private function formatForDB($cycle) {
		$arr = array_values($cycle);
		$horaire = array_shift($arr);
		$arr = array_diff($arr, array('',' '));
		return ( array( 'horaire'=>$horaire,'debut'=>reset($arr), 'dates'=>serialize($arr) ) );
	}
	
	function commitArray($tab)
	{
		//Inactive all users not in array. It will temporarly break everything
		$this->db->update('cycles', array('actif'=>0) ); 
		foreach ($tab as $cycle ){
			$this->commitCycle( $this->formatForDB($cycle) );
		}
	}	
		
	function check( $tab )
	{
		//Quick and dirty. I just assume it's not used often nor for big files
		$data['ajouts']=array();
		$data['modifications']=array();
		$data['desactive']=array();	
		$debuts = array();
		foreach ($tab as $row ){
			array_push( $debuts, reset($row) );
		}
			
		$this -> db -> select('debut,dates');
		$this -> db -> from('cycles');
		$query = $this -> db -> get();
		$allcycles= $query->result_array();
		$dbdebuts = array();
		foreach( $allcycles as $cycle ){
			$first = array_slice($cycle, 1, 1, true);
			array_push( $dbdebuts, $first );
			if ( ! in_array( $first, $debuts ) ) {
				array_push( $data['desactive'], $cycle );
			}
		}
		foreach( $tab as $cycle ){
			 $first = array_slice($cycle, 1, 1, true); 
			if (  in_array( $first, $dbdebuts ) ) { 
				array_push( $data['modifications'], $cycle );
			} else {
				array_push( $data['ajouts'], $cycle );
			}
		}
		return($data);
	}

	public function cycleSoon(){
		$cyclesoon = array();
		$date0=date("m/d/y");
		$date1 = new DateTime($date0);
		$this->db-> select('id, debut,actif');
		$this->db-> from('cycles');
		$this->db-> where(array('actif'=>true));
		$this->db-> order_by("debut", "asc");
		$query = $this->db->get();
		$cycles = $query->result_array();
		foreach ($cycles as $cycle){
			//$date2 = new DateTime($cycle['debut']);
			$date2 = strtotime($cycle['debut']);
			$date3 = date("m/d/y",$date2);
			$date4 = new DateTime($date3);
			$interval = date_diff($date1,$date4);
			$interval2 = intval($interval->format('%R%a'));
			if($interval2 < 10 and $interval2> 0){
				array_push($cyclesoon, $cycle['id']);
			}
		}
		$res=array_unique($cyclesoon);
		return($res);
	}
	
}
