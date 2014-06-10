<?php 
class Database_model extends CI_Model {
	public function get_claim($is_processed=false,$per_page=50,$offset=0)
	{
		$this->db->order_by("id", "desc");
  		$query = $this->db->get('claim',$per_page,$offset);
  		if ($is_processed){
  			return $this->process_claim_query($query->result());
  		}else{
  			return $query->result();
  		}
	}

	public function get_school_name($school_id = -1)
	{
		$this->db->select('name');
		if ($school_id != -1){
			$query = $this->db->get_where('school', array('id' => $school_id));
			if($query->num_rows() > 0){
				return $query->row()->name;
			}else{
				return 'School Not Found';
			}
		}else{
			$query=$this->db->get('school');
			$query_result = $query->result_array();
			foreach ($query_result as &$value) {
				$value = $value['name'];
			}
			return $query_result;
		}

	}

	public function get_activity_name($activity_id = -1)
	{
		$this->db->select('event_name');
		if ($activity_id != -1){
			$query = $this->db->get_where('activity', array('id' => $activity_id));
			if($query->num_rows() > 0){
				return $query->row()->event_name;
			}else{
				return 'Activity Not Found';
			}
		}else{
			$query = $this->db->get('activity');
			$query_result = $query->result_array();
			foreach ($query_result as &$value) {
				$value = $value['event_name'];
			}
			return $query_result;
		}
	}

	public function get_user_name($user_id = -1)
	{
		$this->db->select('display_name');
		if($user_id != -1){
			$query = $this->db->get_where('user', array('id' => $user_id));
			if($query->num_rows() > 0){
				return $query->row()->display_name;
			}else{
				return 'User Not Found';
			}
		}else{
			$query = $this->db->get('user');
			$query_result =  $query->result_array();
			foreach ($query_result as &$value) {
				$value = $value['display_name'];
			}
			return $query_result;
		}
	}

	public function process_claim($row)
	{
		$row->school = $this->get_school_name($row->school_id);
		$row->event = $this->get_activity_name($row->event_id);
		$row->in_charge_name = $this->get_user_name($row->in_charge);
		$row->hours = round($row->hours/2.0,1);
		$row->status_str = ($row->status == -1) ? "PENDING" : "APPROVED BY ".$this->get_user_name($row->status);
		return $row;
	}

	public function process_claim_query($query)
	{
		foreach ($query as &$row) {
			$row = $this->process_claim($row);
		}
		return $query;
	}

	public function add_claim($post)
	{
		for ($i=0; $i < count($post['name']); $i++) { 
			for ($j=0; $j < count($post['event_id']); $j++) { 
				$data = array(
					'name' => $name,
					'nric' => $nric,
					'school_id' => $school_id+1,
					'class' => (isset($class) ? $class : ""),
					'event_id' => $event_id+1,
					'hours' => Round($hours*2,0),
					'remarks' => (isset($remarks) ? $remarks : ""),
					'in_charge' => $in_charge+1,
					'status' => -1
					);
				$this->db->insert('claim', $data); 

			}
		}
	}

	public function get_claim_number()
	{
    	return $this->db->count_all('claim');
	}

	public function approve_claim($id)
	{
		$data = array('status' => $this->session->userdata['user_id']);
		$this->db->where('id', $id);
    	$this->db->update('claim', $data);
	}
	public function new_event($event_name)
	{
		$data = array('event_name' => $event_name);
		$this->db->insert('activity',$data);
	}
	public function edit_event($event_id,$event_name)
	{
		$data = array('event_name' => $event_name);
		$this->db->where('id', $event_id);
    	$this->db->update('activity', $data);
	}
	public function new_subcom($nric,$subcom)
	{
		$data = array('nric' => $nric, 'subcom' => $subcom);
		$this->db->insert('subcom',$data);
	}
	public function edit_subcom($id,$nric,$subcom)
	{
		$data = array('nric' => $nric, 'subcom' => $subcom);
		$this->db->where('id', $id);
    	$this->db->update('subcom', $data);
	}
	public function delete_subcom($id)
  	{
	    $this->db->where('id', $id);
	    $this->db->delete('subcom');
	    if($this->db->affected_rows()==0){
      		return false;
    	}else{
      		return TRUE;
    	}
  	}
  	public function get_subcom($nric = -1)
	{
		
		if($nric != -1){
			$this->db->select('subcom');
			$query = $this->db->get_where('subcom', array('nric' => $nric));
			if($query->num_rows() > 0){
				return $query->row()->subcom;
			}else{
				return '';
			}
		}else{
			
			$query = $this->db->get('subcom');
			$query_result =  $query->result_array();
			return $query_result;
		}
	}
	public function generate_report()
	{
		$str1 = <<<'EOT'
SELECT GROUP_CONCAT( DISTINCT CONCAT(  'MAX(IF(`cipreg_activity`.`event_name` = ''',  REPLACE(`cipreg_activity`.`event_name`, '''', '''''') , ''', ROUND(`hours`/2.0,1), NULL)) AS ''',  REPLACE(`cipreg_activity`.`event_name`, '''', ''''''),'''' ) ) INTO @sql FROM  `cipreg_activity`;
EOT;
		$str2 = <<<'EOT'
SET @sql = CONCAT('SELECT `cipreg_claim`.`name` AS "Name",`cipreg_subcom`.`subcom` AS "Subcom",`cipreg_claim`.`nric` AS "NRIC", ROUND(SUM(`hours`)/2.0,1) AS "Total hours" ,`cipreg_school`.`name` AS "School" ,`class` AS "Class",', @sql, 'FROM `cipreg_claim`  INNER JOIN `cipreg_school` ON `cipreg_claim`.`school_id`=`cipreg_school`.`id` INNER JOIN `cipreg_activity` ON `cipreg_claim`.`event_id`=`cipreg_activity`.`id`LEFT JOIN `cipreg_subcom` ON `cipreg_claim`.`nric`=`cipreg_subcom`.`nric` WHERE  `status` >= 0 GROUP BY `nric` ');  
EOT;
		$queries_list=array("SET @sql = NULL;",$str1,$str2,"PREPARE stmt FROM @sql;");
		foreach ($queries_list as $query_str) {
			$this->db->query($query_str);
		}
		$query = $this->db->query("EXECUTE stmt;");
		//$this->db->query("DEALLOCATE PREPARE stmt;");
		return $query;
	}
	public function edit_claim($id,$name,$nric,$school_id,$class,$event_id,$hours,$remarks,$in_charge)
	{
		$data = array('name' => $name,
					'nric' => $nric,
					'school_id' => $school_id+1,
					'class' => (isset($class) ? $class : ""),
					'event_id' => $event_id+1,
					'hours' => Round($hours*2,0),
					'remarks' => (isset($remarks) ? $remarks : ""),
					'in_charge' => $in_charge+1);
		$this->db->where('id', $id);
    	$this->db->update('claim', $data);
	}
	public function delete_claim($id)
  	{
	    $this->db->where('id', $id);
	    $this->db->delete('claim');
	    if($this->db->affected_rows()==0){
      		return false;
    	}else{
      		return TRUE;
    	}
  	}
}