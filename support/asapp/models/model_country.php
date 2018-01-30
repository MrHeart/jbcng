<?php
class MCountry extends Model{

	function __construct()
	{
		parent::__construct();
	}
  
	/* Returns all the countries */
	function get_all()
	{
		$this->db->order_by("country", "asc");
		$query = $this->db->query('SELECT id,iso,country FROM countries');
		
		if($query->num_rows() > 0) 
		{
			foreach ($query->result_array() as $row)
			{
				$rows[]=$row;
			}
			
			//return $query->result_array();
		}else
		{
			$rows[]='No Record Was Retrieved';
		}
        
		echo json_encode($rows);
		
		//$this->db->select('id, iso, country FROM countries ORDER BY country', FALSE);
		//return $this->db->get();	
	}
}

?>