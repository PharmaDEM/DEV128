<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects_model extends MY_Model {

protected $tabler = 'sresults';

	public $table = 'projects';

	public function attempt($data)
	{


		$this->db->where('username', $data['username']);
		$this->db->or_where('email', $data['username']);

		$query = $this->db->get($this->table);

		// validate user
		if(!empty($query) && $query->num_rows() > 0){

			// checks the password
			if($query->row()->password == hash( "sha256", $data['password'] )){

				if ($query->row()->status==='1')
					return 'valid'; // if valid password and username and allowed
				else
					return 'not_allowed';

			}
			else
				return 'invalid_password'; // if invalid password

		}

		return false;

	}



	public function getResultsall($min10cmgml, $max10cmgml, $min25cmgml, $max25cmgml, $min50cmgml, $max50cmgml, $jbid) {
		$this->db->select('results_data_10.ssystem_name, results_data_10.10cmgml, results_data_25.25cmgml, results_data_50.50cmgml');
		$this->db->from('results_data_10');
		$this->db->join('results_data_25', 'results_data_10.ssystem_name = results_data_25.ssystem_name AND results_data_10.job_id = results_data_25.job_id', 'inner');
		$this->db->join('results_data_50', 'results_data_10.ssystem_name = results_data_50.ssystem_name AND results_data_10.job_id = results_data_50.job_id', 'inner');
		$this->db->where('results_data_10.job_id', $jbid);
		$this->db->where('results_data_25.job_id', $jbid);
		$this->db->where('results_data_50.job_id', $jbid);
		
		$this->db->where('results_data_10.10cmgml >=', $min10cmgml, false);
		$this->db->where('results_data_10.10cmgml <=', $max10cmgml, false);
		$this->db->where('results_data_25.25cmgml >=', $min25cmgml, false);
		$this->db->where('results_data_25.25cmgml <=', $max25cmgml, false);
		$this->db->where('results_data_50.50cmgml >=', $min50cmgml, false);
		$this->db->where('results_data_50.50cmgml <=', $max50cmgml, false);
		
		$query = $this->db->get();
		//echo $this->db->last_query();
		if ($query === false) {
			$error = $this->db->error();
			log_message('error', 'Database Error: ' . $error['message']);
			return []; // Return empty array on error
		}
		
		return $query->result_array();
	}
	
	
	
	

	// public function getResultsall($min10cmgml, $max10cmgml, $min25cmgml, $max25cmgml, $min50cmgml, $max50cmgml, $jbid) {
	// 	//echo $jbid;

    //     $results_10 = $this->getDistinctResultsFromTable('results_data_10', '10cmgml', $min10cmgml, $max10cmgml, $jbid);
    //     $results_25 = $this->getDistinctResultsFromTable('results_data_25', '25cmgml', $min25cmgml, $max25cmgml, $jbid);
    //     $results_50 = $this->getDistinctResultsFromTable('results_data_50', '50cmgml', $min50cmgml, $max50cmgml, $jbid);
        
    //     // Merge and remove duplicates
    //     $all_results = array_merge($results_10, $results_25, $results_50);
    //     $unique_results = array_map("unserialize", array_unique(array_map("serialize", $all_results)));
        
    //     return $unique_results;
    // }
    
    // Helper method to fetch distinct results from a specific table
    private function getDistinctResultsFromTable($table, $column, $min, $max, $jbid) {
		$this->db->distinct();
		$this->db->select('*'); // Select all columns from the main table
		$this->db->from($table);
		$this->db->join('job_results', 'job_results.job_id = ' . $table . '.job_id', 'left'); // Join with job_results table
		$this->db->where($table . '.job_id', $jbid); // Filter by job_id from the main table
		$this->db->where($column . ' >=', $min); // Filter by the specified column and min value
		$this->db->where($column . ' <=', $max); // Filter by the specified column and max value
		$query = $this->db->get();

        //echo $this->db->last_query();
        // Check for query execution errors
        if ($query === false) {
            $error = $this->db->error();
            log_message('error', 'Database Error: ' . $error['message']);
            return []; // Return empty array on error
        }
        
        return $query->result_array();
    }

	public function getJobIdByProjectId($projectId) {
        $this->db->select('jm.id');
        $this->db->from('projects p');
        $this->db->join('jobs_master jm', 'p.id = jm.project_id', 'inner');
        $this->db->where('p.id', $projectId);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->id; // Return job_id directly
        } else {
            return null;
        }
    }

	public function getProjects() {
        $this->db->distinct();
        $this->db->select('pm.id, pm.project_name');
        $this->db->from('projects pm');
        $this->db->join('jobs_master jm', 'pm.id = jm.project_id', 'inner');
		$query = $this->db->get();
		//echo $this->db->last_query(); // Output the generated SQL query

		if (!$query) {
			$error = $this->db->error();
			log_message('error', 'Database Error: ' . $error['message']);
			return false; // Or handle the error appropriately
		}
		return $query->result_array();
		
    }



	public function getjob_id($id) {

		$query = $this->db->order_by('id', 'desc')
		->where('project_id', $id)
		->limit(1)
		->get('jobs_master');

if ($query === FALSE) {
$error = $this->db->error();
die('Database error: ' . $error['message']);
} else {
$last = $query->row();
return $last->id;
}

	
	
	}
	
	public function get_temperature_data($project_id, $temperature) {
		
		//echo $temperature;

		$last_job_id = $this->projects_model->getjob_id($project_id);
    
		$input_temp_column = 'input_temp_' . $temperature;
		$table_n = 'results_data_' . $temperature;
		$cvl_column = $temperature . 'cvl';
		$cyl_column = $temperature . 'cyl';
		
		
		
		if($temperature=='25') {
			$table_n = 'results_data_25';
			$this->db->select("rd.25cvl, rd.25cyl");
		$this->db->from('job_results jr');
		$this->db->join($table_n . ' rd', 'rd.result_job_id = jr.id', 'inner');
		$this->db->where('jr.input_temp_20', $temperature);
		$this->db->where('jr.job_id', $last_job_id);

		}
		else {

		$this->db->select("sm.w1_solvent_system,rd.$cvl_column, rd.$cyl_column");
		$this->db->from('job_results jr');
		$this->db->join($table_n . ' rd', 'rd.result_job_id = jr.id', 'inner');
		$this->db->join('solvents_master sm', 'jr.s_id = sm.s_id', 'inner');

		$this->db->where('jr.input_temp_'.$temperature, $temperature);
		$this->db->where('jr.job_id', $last_job_id);
		}


//$this->db->limit(10);
	
		$query = $this->db->get();
	
		// Print the last executed SQL query for debugging
		//echo $this->db->last_query();
	
		if ($query === false) {
			$error = $this->db->error();
			log_message('error', 'Database Error: ' . $error['message']);
			return array(); // Return an empty array on error
		} else {
			return $query->result();
		}
	}
	
    

	public function getProjectName($id)

	{
	
		$this->db->select('*');
		$this->db->from('projects');
		$this->db->where('id', $id);
		$query = $this->db->get();
		$result = $query->row();
		
		$pname = $result->project_name;
		return $pname;

	}

	public function checktempprocess($id,$temp) {

		$this->db->where('job_id', $id);
		if($temp=="10") {
		$this->db->where('input_temp_10', 'Yes');
		}
		if($temp=="20") {
			$this->db->where('input_temp_10', 'Yes');
		}
		
		if($temp=="50") {
				$this->db->where('input_temp_10', 'Yes');
		}
			
		$query = $this->db->get('job_results');
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		return $query->result();

	}

	public function createjob($data) {

		$this->db->insert('jobs_master', $data);
        
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

	}
	public function createjobresults($data) {

		$this->db->insert('job_results', $data);
        
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

	}
public function get_jobrecords($id) {
	//echo $id;

		$this->db->select('*');
		$this->db->from('job_results');
		$this->db->where('job_id',$id);
		$this->db->group_by('id'); 

		$q = $this->db->get();
		if($q->num_rows() > 0){
		//$row = $q->row();
		//return $row->solvents;
		//$query = $this->db->get(); 
        return $q->result();
		}
return false;
}

public function getPendingqJobCount() {
    
	$this->db->select('COUNT(tasks_queue.job_id) as pending_count');
$this->db->from('tasks_queue');
$this->db->join('job_results', 'job_results.job_id = tasks_queue.job_id', 'left');
$this->db->where('tasks_queue.status', 'pending');
$this->db->where('job_results.job_id IS NULL', null, false);
//print_r($this->db->last_query());
$query = $this->db->get();
$result = $query->row();

$pendingCount = $result->pending_count;
return $pendingCount;

}

public function checkJobExistsinQueue($jobid) {
    $existingRecord = $this->db->get_where('tasks_queue', array('job_id' => $jobid,'status'=>'pending'))->row();
    return ($existingRecord !== null);
}

public function get_jobrecords1($id) {

	$last = $this->db->order_by('id',"desc")
			->where('job_id',$id)
		->limit(1)

		->get('job_results')
		
		->row();
		echo $last->id;


}

public function check_records_status($last_id) {
	
   // $last_id = $_POST['last_id'];

    // Retrieve the latest records from the database
    $this->db->select('*');
    $this->db->from('job_results');
    $this->db->where('id >', $last_id);
    $this->db->order_by('id', 'ASC');
    $query = $this->db->get();

    // Return the records as a JSON object
    $records = $query->result();
    echo json_encode($records);
}

public function getsolvents() {
	//$query = $this->db->query("SELECT jr.solvents,jr.solvent_result_name,jr.solvent_result FROM `jobs_master` jm, job_results jr, job_results_count jrc where jm.id=jr.job_id and jr.job_id=jrc.job_id and jm.project_id=$id and jr.job_id=$jid and jr.result_type='".$resulttype."'");
	$query = $this->db->query("SELECT * FROM solvents_master where s_id between 1 and 68");
	//echo $this->db->last_query();
	return $query->result_array();
}

public function getsolventsb() {
	$query = $this->db->query("SELECT * FROM solvents_master where s_id between 69 and 2346");
	//$query = $this->db->query("SELECT * FROM solvents_master where s_id =2346");
	//$query = $this->db->query("SELECT * FROM solvents_master where s_id between 69 and 2346 and (solvent1_name like '%,%' or solvent2_name like '%,%' or solvent3_name like '%,%')");
	//echo $this->db->last_query();
	return $query->result_array();
}

public function getsolventscustom($ids) {
	$resultsArray = array();

    // Generate matrix combinations of 2x2
    for ($i = 0; $i < count($ids); $i++) {
        for ($j = $i + 1; $j < count($ids); $j++) {
            $id1 = $ids[$i];
            $id2 = $ids[$j];

            $solvent1 = $this->db->select('solvent1_name')->where('s_id', $id1)->get('solvents_master')->row();
            $solvent2 = $this->db->select('solvent1_name')->where('s_id', $id2)->get('solvents_master')->row();



            if ($solvent1 && $solvent2) {
                $solventName1 = $solvent1->solvent1_name;
                $solventName2 = $solvent2->solvent1_name;

				//$matchingRecords = $this->db->where('solvent1_name', $solventName1,'solvent2_name', $solventName2)->get('solvents_master')->result_array();
				$matchingRecords = $this->db->where(array('solvent1_name' => $solventName1, 'solvent2_name' => $solventName2,'solvent3_name' => ''))
                            ->get('solvents_master')
                            ->result_array();

				//echo $this->db->last_query();
				foreach ($matchingRecords as $record) {
					// Perform desired operations with each matching record
					// For example, you can access $record['column_name'] to get specific values
			
					// Add the record to the results array
					$resultsArray[] = $record;
				}

           
            }
        }
    }


    return $resultsArray;


}

public function getsolventscustomt($ids) {
    $resultsArray = array();

    // Generate matrix combinations of 3x3
    for ($i = 0; $i < count($ids); $i++) {
        for ($j = $i + 1; $j < count($ids); $j++) {
            for ($k = $j + 1; $k < count($ids); $k++) {
                $id1 = $ids[$i];
                $id2 = $ids[$j];
                $id3 = $ids[$k];

                $solvent1 = $this->db->select('solvent1_name')->where('s_id', $id1)->get('solvents_master')->row();
                $solvent2 = $this->db->select('solvent1_name')->where('s_id', $id2)->get('solvents_master')->row();
                $solvent3 = $this->db->select('solvent1_name')->where('s_id', $id3)->get('solvents_master')->row();

                if ($solvent1 && $solvent2 && $solvent3) {
                    $solventName1 = $solvent1->solvent1_name;
                    $solventName2 = $solvent2->solvent1_name;
                    $solventName3 = $solvent3->solvent1_name;

                    $matchingRecords = $this->db->where(array('solvent1_name' => $solventName1, 'solvent2_name' => $solventName2, 'solvent3_name' => $solventName3))
                                                ->get('solvents_master')
                                                ->result_array();

                    foreach ($matchingRecords as $record) {
                        // Perform desired operations with each matching record
                        // For example, you can access $record['column_name'] to get specific values
                
                        // Add the record to the results array
                        $resultsArray[] = $record;
                    }
                }
            }
        }
    }

    return $resultsArray;
}



public function getsolventsbs($stype,$sstart) {

	if(($stype=="Pure_68") && ($sstart==0))
	{
		$sstart=1;
		$query = $this->db->query("SELECT * FROM solvents_master where s_id between $sstart and 68");

	}

	if(($stype=="Pure_68") && ($sstart>0))
	{
		//$sstart=1;
		$query = $this->db->query("SELECT * FROM solvents_master where s_id between $sstart and 68");

	}

	if(($stype=="Binary_1085") && ($sstart==0))
	{
		$sstart=69;
		$query = $this->db->query("SELECT * FROM solvents_master where s_id between $sstart and 2346");

	}

	if(($stype=="Binary_1085") && ($sstart>0))
	{
		//$sstart=69;
		$query = $this->db->query("SELECT * FROM solvents_master where s_id between $sstart and 2346");

	}
	
	//$query = $this->db->query("SELECT * FROM solvents_master where s_id between $sstart and 2346");
	//$query = $this->db->query("SELECT * FROM solvents_master where s_id =2346");
	//$query = $this->db->query("SELECT * FROM solvents_master where s_id between 69 and 2346 and (solvent1_name like '%,%' or solvent2_name like '%,%' or solvent3_name like '%,%')");
	//echo $this->db->last_query();
	return $query->result_array();
}
	
	
public function getsolventst() {
	//$query = $this->db->query("SELECT * FROM solvents_master where s_id between 69 and 2346");
	$query = $this->db->query("SELECT * FROM solvents_master where s_id > 2346 and s_id <= 2356");
	//echo $this->db->last_query();
	return $query->result_array();
}

public function updateactivity_log($data) {
    // Perform the necessary database update query using the provided $data
    // For example:
     $this->db->where('id', $data['id']);
    $this->db->update('activity_log_table', $data);
}


public function getsolventsbpatch($id) {
	//$query = $this->db->query("select id,job_id,solvent1_name, input_temp_10,input_temp_20,input_temp_50,result_type from job_results jr, solvents_master sm where (jr.s_id=sm.s_id and jr.job_id=$id) and jr.solvent_result='0' limit 100");
	$query = $this->db->query("select id,job_id,solvent1_name, input_temp_10,input_temp_20,input_temp_50,result_type,solvent1_name,solvent2_name,solvent3_name from job_results jr, solvents_master sm where (jr.s_id=sm.s_id and jr.job_id=$id) and 	jr.pure_data1 like '%, ,%' limit 100");
	return $query->result_array();
}

public function gettempcalcs()
{
	$query = $this->db->query("select * from temp_calcs");

		if(!empty($query) && $query->num_rows() > 0){
			//echo $query->num_rows();
			return $query->result();
		}
}

	public function addactivity_log($data) {

		$this->db->insert('job_results_count', $data);   
		$insertId = $this->db->insert_id();
		return  $insertId;

	}

	public function checkjobexists($id) {


		$this->db->where('project_id', $id);
		
		$query = $this->db->get('jobs_master');
		//echo $this->db->last_query();
		// validate user
		if(!empty($query) && $query->num_rows() > 0){
			//echo $query->num_rows();
			return true;
		}
	}
public function checkjobresultsexits($jtype,$jid){

	$this->db->where('job_id', $jid);
	$this->db->where('solvent_type', $jtype);
		
	$query = $this->db->get('job_results_count');
	//echo $this->db->last_query();
	if($query->num_rows() > 0)
	return $query->result();

}

public function getsolvents_all() {
	$this->db->select('MIN(s_id) as s_id, solvent1_name');
	$this->db->group_by('solvent1_name');
	$result = $this->db->get('solvents_master')->result();
	//echo $this->db->last_query();
	return $result;
}

public function checkjobresultsexitsA($jtype,$jid){

	$this->db->where('job_id', $jid);
	$this->db->where('solvent_type', $jtype);
	$this->db->where('solvent_activity_finished IS NOT NULL');
		
	$query = $this->db->get('job_results_count');
	//echo $this->db->last_query();
	if($query->num_rows() > 0)
	return $query->result_array();

}

	public function checkactivityexists($id) {
		//echo $id;

		$query = $this->db->query("SELECT DISTINCT(jrc.solvent_type) FROM `jobs_master` jm, job_results jr, job_results_count jrc where jm.id=jr.job_id and jr.job_id=jrc.job_id and jm.project_id=$id");

		//echo $this->db->last_query();
		// validate user
		if(!empty($query) && $query->num_rows() > 0){
			//echo $query->num_rows();
			return true;
		}

	}

	function results_summary_data($data){
		$this->db->insert('results_data',$data);
	}

	public function push_results($id,$temp)
	{
		$query = $this->db->query("select sm.w1_density, sm.w1_solvent_system, jr.pure_data1,jr.job_id,jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where jr.input_temp_10 = 10  and sm.s_id=jr.s_id and jm.id=jr.job_id and jm.project_id=".$id);

				foreach($query->result() as $record){
		
				$data = array(
					'job_id'=>$record->jbid,
					'ssystem_name'=>$record->w1_solvent_system,
					'at50'=>$this->input->post('customer'),
					'lat50'=>$this->input->post('customer'),
					'50cmgml'=>$this->input->post('customer'),
					'50cvl'=>$this->input->post('customer'),
					'50cyl'=>$this->input->post('customer'),
					'at25'=>$this->input->post('customer'),
					'lat25'=>$this->input->post('customer'),
					'25cmgml'=>$this->input->post('customer'),
					'25cvl'=>$this->input->post('customer'),
					'25cyl'=>$this->input->post('customer'),
					'at10'=>$this->input->post('customer'),
					'lat10'=>$this->input->post('customer'),
					'10cmgml'=>$this->input->post('customer'),
					'10cvl'=>$this->input->post('customer'),
					'10cyl'=>$this->input->post('customer'),
					  );
					$this->projects_model->results_summary_data($data);

			}
	}


	function job_exists($job_id)
	{
    	$this->db->where('job_id',$job_id);
    	$query = $this->db->get('job_status');
		//echo $this->db->last_query();
    	if ($query->num_rows() > 0){
        return true;
    	}
    	else{
        return false;
    	}
	}

	public function getOldestJobId() {

		$this->db->select('COUNT(*) as pending_count');
		$this->db->where('status', 'pending');
		$query = $this->db->get('job_results_count');
		$result = $query->row();

		$pendingCount = $result->pending_count;
		

		if($pendingCount==0) {

				$this->db->select('job_id');
				$this->db->where('status', 'pending');
				$this->db->order_by('id', 'ASC');
				$this->db->limit(1);
				$query = $this->db->get('tasks_queue');
			
				if ($query->num_rows() > 0) {
					$row = $query->row();
					return $row->job_id;
				}
			
				return null;
		}
	}

	
	public function getPendingProjects()
{
    $this->db->select('job_id, COUNT(*) as jobs_pcount');
    $this->db->where('status', 'pending');
    $this->db->group_by('job_id');
    $query = $this->db->get('job_results_count');
    return $query->result();

		//$this->db->select('tasks_queue.job_id, COUNT(*) as jobs_pcount');
		//$this->db->where('tasks_queue.status', 'pending');
		//$this->db->where('job_results_count.status', 'pending');
		//$this->db->group_by('tasks_queue.job_id');
		//$this->db->join('job_results_count', 'tasks_queue.job_id = job_results_count.job_id', 'left');
		//$query = $this->db->get('tasks_queue');
		//return $query->result();
}

public function getPendingpatches($id)
{
    $this->db->select(' COUNT(*) as jobs_results');
    $this->db->where('pure_data1 like', '%, ,%');
	$this->db->where('job_id', $id);
    $this->db->group_by('job_id');
    $query = $this->db->get('job_results');
	//echo $this->db->last_query();
	$row = $query->row();
	if ($row) {
		// Access the row values
		return $row->jobs_results;
	}

}


	public function insertresults10($id) {


		$pureDataFields = array('pure_data1', 'pure_data2', 'pure_data3', 'pure_data4', 'pure_data5');

		foreach ($pureDataFields as $pureDataField) {
	
			$query = $this->db->query("SELECT sm.w1_density,jr.solvents, sm.w1_solvent_system,jr.result_type, jr.$pureDataField as pdata, jr.job_id, jr.id as jbid 
				FROM solvents_master sm, job_results jr, jobs_master jm 
				WHERE jr.input_temp_10 = 10 AND sm.s_id=jr.s_id 
					AND jr.solvent_result NOT LIKE '%Traceback%' AND jr.solvent_result <>'' 
					AND jm.id=jr.job_id AND jm.project_id=".$id);

		//$query = $this->db->query("select sm.w1_density,jr.solvents, sm.w1_solvent_system, jr.pure_data1,jr.job_id,jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where jr.input_temp_10 = 10  and sm.s_id=jr.s_id and jr.solvent_result not like '%Traceback%' and jr.solvent_result <>'' and jm.id=jr.job_id  and jm.project_id=".$id);
		$results_10 = $query->result();
		//echo $this->db->last_query();
		$jbd = $this->projects_model->getJobdetails($id);
		
		$exists	=    $this->projects_model->job_exists($jbd[0]->id);
		

		if($exists) {
			
			$datae = array(
				'input_10' => 'Yes',
				'status' => 'Pending'
			);

			$this->db->where('job_id', $jbd[0]->id);
			$this->db->update('job_status', $datae);

		}
		else {
			$datae = array(
				'job_id' => $jbd[0]->id,
				'input_10' => 'Yes',
				'status' => 'Pending'
			);

			$this->db->insert('job_status', $datae);
		}

		$data = array();
	
		foreach ($results_10 as $row)  {  // Call function to get values
  
		$result_job_id = $row->jbid;
		$job_id = $row->job_id;
		$ssystem_name = $row->solvents;
		if (!empty($row->pdata)) {

		$at10 = $this->projects_model->getfirstvalue10($row->pdata);
		$lat10 = $this->projects_model->getlat10($row->pdata);
		$tencmgml = $this->projects_model->get10mgmlcal($row->pdata,$row->job_id);
		$tencvl = $this->projects_model->get10cvlinsert($row->pdata,$row->w1_density,$row->job_id);
		$tencyl = $this->projects_model->get10cYNinsert($row->pdata,$row->job_id,$row->job_id,$row->solvents);

    // Add data to the array
   	 	$data[] = array(
      	 	 'result_job_id' =>  (int)$result_job_id,
       		 'job_id' => (int)$job_id,
       		 'ssystem_name' => $ssystem_name,
			 'at10' => $at10,
			 'lat10' => $lat10,
			 '10cmgml' => $tencmgml,
			 '10cvl' => $tencvl,
			 '10cyl' => $tencyl,
			 'wt_fraction' => $pureDataField

   		 );
	}
}	
		// Insert the data using batch insert

		$this->db->insert_batch('results_data_10', $data);

		
		$exists	=    $this->projects_model->job_exists($jbd[0]->id);
		

		if($exists) {
			
			$datae = array(
				'input_10' => 'Yes',
				'status' => 'Completed'
			);

			$this->db->where('job_id', $jbd[0]->id);
			$this->db->update('job_status', $datae);

		}
		else {
			$datae = array(
				'job_id' => $jbd[0]->id,
				'input_10' => 'Yes',
				'status' => 'Completed'
			);

			$this->db->insert('job_status', $datae);
		}

		
	}

		echo "10 Done ";
		
	}

	public function insertresults25($id) {


		$pureDataFields = array('pure_data1', 'pure_data2', 'pure_data3', 'pure_data4', 'pure_data5');

		foreach ($pureDataFields as $pureDataField) {

		$query = $this->db->query("select sm.w1_density,jr.solvents, sm.w1_solvent_system,jr.result_type, jr.$pureDataField as pdata,jr.job_id,jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where jr.input_temp_20 = 25  and sm.s_id=jr.s_id and jr.solvent_result not like '%Traceback%' and jr.solvent_result <>'' and jm.id=jr.job_id  and jm.project_id=".$id);
		$results_10 = $query->result();
		$jbd = $this->projects_model->getJobdetails($id);
		$exists	=    $this->projects_model->job_exists($jbd[0]->id);
		

		if($exists) {
			
			$datae = array(
				'input_25' => 'Yes',
				'status' => 'Pending'
			);

			$this->db->where('job_id', $jbd[0]->id);
			$this->db->update('job_status', $datae);

		}
		else {
			$datae = array(
				'job_id' => $jbd[0]->id,
				'input_25' => 'Yes',
				'status' => 'Pending'
			);

			$this->db->insert('job_status', $datae);
		}

		$data = array();
	
		foreach ($results_10 as $row)  {  // Call function to get values
  
		$result_job_id = $row->jbid;
		$job_id = $row->job_id;
		$ssystem_name = $row->solvents;
		if (!empty($row->pdata)) {

		$at10 = $this->projects_model->getfirstvalue10($row->pdata);
		$lat10 = $this->projects_model->getlat10($row->pdata);
		$tencmgml = $this->projects_model->get10mgmlcal($row->pdata,$row->job_id);
		$tencvl = $this->projects_model->get10cvlinsert($row->pdata,$row->w1_density,$row->job_id);
		$tencyl = $this->projects_model->get10cYNinsert($row->pdata,$row->job_id,$row->job_id,$row->solvents);

    // Add data to the array
   	 	$data[] = array(
      	 	 'result_job_id' =>  (int)$result_job_id,
       		 'job_id' => (int)$job_id,
       		 'ssystem_name' => $ssystem_name,
			 'at25' => $at10,
			 'lat25' => $lat10,
			 '25cmgml' => $tencmgml,
			 '25cvl' => $tencvl,
			 '25cyl' => $tencyl,
			 'wt_fraction' => $pureDataField


   		 );
	}
}	
		// Insert the data using batch insert

		$this->db->insert_batch('results_data_25', $data);

		$jbd = $this->projects_model->getJobdetails($id);
		$exists	=    $this->projects_model->job_exists($jbd[0]->id);
		

		if($exists) {
			
			$datae = array(
				'input_25' => 'Yes',
				'status' => 'Completed'
			);

			$this->db->where('job_id', $jbd[0]->id);
			$this->db->update('job_status', $datae);

		}
		else {
			$datae = array(
				'job_id' => $jbd[0]->id,
				'input_25' => 'Yes',
				'status' => 'Completed'
			);

			$this->db->insert('job_status', $datae);
		}

	}		
		echo "25 Done ";
		
	}

	public function insertresults50($id) {

		$pureDataFields = array('pure_data1', 'pure_data2', 'pure_data3', 'pure_data4', 'pure_data5');

		foreach ($pureDataFields as $pureDataField) {

		$query = $this->db->query("select sm.w1_density,jr.solvents, sm.w1_solvent_system,jr.result_type, jr.$pureDataField as pdata,jr.job_id,jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where jr.input_temp_50 = 50  and sm.s_id=jr.s_id and jr.solvent_result not like '%Traceback%' and jr.solvent_result <>'' and jm.id=jr.job_id  and jm.project_id=".$id);
		$results_10 = $query->result();
		$jbd = $this->projects_model->getJobdetails($id);
		$exists	=    $this->projects_model->job_exists($jbd[0]->id);
		

		if($exists) {
			
			$datae = array(
				'input_50' => 'Yes',
				'status' => 'Pending'
			);

			$this->db->where('job_id', $jbd[0]->id);
			$this->db->update('job_status', $datae);

		}
		else {
			$datae = array(
				'job_id' => $jbd[0]->id,
				'input_50' => 'Yes',
				'status' => 'Pending'
			);

			$this->db->insert('job_status', $datae);
		}

		$data = array();
	
		foreach ($results_10 as $row)  {  // Call function to get values
			

		$result_job_id = $row->jbid;
		$job_id = $row->job_id;
		$ssystem_name = $row->solvents;

		if (!empty($row->pdata)) {
		$at10 = $this->projects_model->getfirstvalue10($row->pdata);
		$lat10 = $this->projects_model->getlat10($row->pdata);
		$tencmgml = $this->projects_model->get10mgmlcal($row->pdata,$row->job_id);
		$tencvl = $this->projects_model->get10cvlinsert($row->pdata,$row->w1_density,$row->job_id);
		$tencyl = $this->projects_model->get10cYNinsert($row->pdata,$row->job_id,$row->job_id,$row->solvents);

    // Add data to the array
   	 	$data[] = array(
      	 	 'result_job_id' =>  (int)$result_job_id,
       		 'job_id' => (int)$job_id,
       		 'ssystem_name' => $ssystem_name,
			 'at50' => $at10,
			 'lat50' => $lat10,
			 '50cmgml' => $tencmgml,
			 '50cvl' => $tencvl,
			 '50cyl' => $tencyl,
			 'wt_fraction' => $pureDataField


   		 );
	}
}
		// Insert the data using batch insert

		$this->db->insert_batch('results_data_50', $data);

		$jbd = $this->projects_model->getJobdetails($id);
		$exists	=    $this->projects_model->job_exists($jbd[0]->id);
		

		if($exists) {
			
			$datae = array(
				'input_50' => 'Yes',
				'status' => 'Completed'
			);

			$this->db->where('job_id', $jbd[0]->id);
			$this->db->update('job_status', $datae);

		}
		else {
			$datae = array(
				'job_id' => $jbd[0]->id,
				'input_50' => 'Yes',
				'status' => 'Completed'
			);

			$this->db->insert('job_status', $datae);
		}
	}
		echo "50 Done ";
		
	}


	public function checkjobinserts($id) {
		$jbd = $this->projects_model->getJobdetails($id);
if($jbd) {	
	
	
	$query = $this->db->select('job_status.status')
	->from('job_status')
	->join('job_results_count', 'job_status.job_id = job_results_count.job_id')
	->where('job_status.job_id', $jbd[0]->id)
	->get();
	//echo $this->db->last_query(); 
if ($query->num_rows() > 0) {
	$rows = $query->result();
	foreach ($rows as $row) {
		if ($row->status != 'Completed') {
			return false; // Status is not completed for at least one row
		}
	}
	return true; // Status is completed for all rows
}

return false; // No rows found


	}
		
			}


			public function checkjobinsertsA($id) {
				$jbd = $this->projects_model->getJobdetails($id);
		if($jbd) {	
			
			
			$query = $this->db->select('job_status.status')
			->from('job_status')
			->join('job_results_count', 'job_status.job_id = job_results_count.job_id')
			->where('job_status.job_id', $jbd[0]->id)
			->get();
			//echo $this->db->last_query(); 
		if ($query->num_rows() > 0) {
			$rows = $query->result();
			foreach ($rows as $row) {
				if ($row->status == 'Pending') {
					return true; // Status is not completed for at least one row
				}
			}
			//return true; // Status is completed for all rows
		}
		
		return false; // No rows found
		
		
			}
				
					}

	public function get10cvlinsert($val,$density,$jid)
	{
		//echo $val;
		//(1000-((AT10/Density) /(AT10/Density) )
		//echo "D".$density."D";
		$mol = $this->projects_model->getJobstatus($jid);

		if(($mol[0]->mol_weight)<>"") {

			$mol_weight = $mol[0]->mol_weight;
		}

		$valuesArray = explode(", ", $val);
		$firstValue = $valuesArray[0];
		
		$finalv=((-0.3688)*($firstValue));
		$finalk = (($finalv-0.4287));
		//echo round($finalv,4); // Outputs "apple"
		$result = round(pow(10, $finalk),4);

		//echo round(($result*180.15),4); // Outputs 100000
		$mgmlval= $result*$mol_weight;

		$mgmlval=$mgmlval/$density;

		$ff=(1000-$mgmlval);
		if($mgmlval>0) {
		$ff = ($ff/$mgmlval);
		return $ff;
		}

		//echo round(($result*180.15),4); // Outputs 100000
	}

	public function get10cYNinsert($val,$jid,$jobid,$w1)
	{
		//echo $jobid;

		//echo $w1;

		$ffmg = $this->projects_model->get10mgdata($jobid,$w1,"50");
		//print_r($ffmg);


		//echo $val;
		$mol_weight=180.15;
		$mol = $this->projects_model->getJobstatus($jid);

		if(($mol[0]->mol_weight)<>"") {

			$mol_weight = $mol[0]->mol_weight;
		}

			//echo "Mol" . $mol_weight;
		
		$valuesArray = explode(", ", $ffmg[0]->pdata);
		$firstValue = $valuesArray[0];
		$finalv=((-0.3688)*((int)$firstValue));
		$finalk = (($finalv-0.4287));
		//echo round($finalv,4); // Outputs "apple"
		$result = round(pow(10, $finalk),4);

		//echo round(($result*180.15),4); // Outputs 100000
		$fiftymg= $result*$mol_weight;

		$tenmgml = $this->projects_model->get10mgdata($jobid,$w1,"10");
//print_r($tenmgml);


		$valuesArray1= explode(", ",$tenmgml[0]->pdata);

		//$valuesArray = explode(", ", $val);
		$firstValue10 = $valuesArray1[0];
		$finalv10=((-0.3688)*($firstValue10));
		$finalk10 = (($finalv10-0.4287));
		//echo round($finalv,4); // Outputs "apple"
		$result10 = round(pow(10, $finalk10),4);

		//echo round(($result*180.15),4); // Outputs 100000
		$tenmg= $result10*$tenmgml[0]->mol_weight;
		//echo "Fifty ".$fiftymg ."<br>";
		//echo "Ten ".$tenmg ."<br>";

		$calu = $fiftymg-$tenmg;
		//echo "Minus " .$calu;
		if($fiftymg>0) {
		$calu = $calu/$fiftymg;

		$calfinal = $calu*100;
		
		return $calfinal;
	}

	}

	public function getResultsData10WithComparison($id)
	{
		ini_set('memory_limit', '-1');
		$jbd = $this->projects_model->getJobdetails($id);

	// Select rows from results_data_10 where "10c" is greater than "50c" in results_data_50
$query = $this->db->query("
SELECT rd10.result_job_id, rd10.job_id, rd10.ssystem_name, rd10.10cmgml, rd10.10cvl, rd10.10cyl
FROM results_data_10 rd10
JOIN results_data_50 rd50 ON rd10.job_id = rd50.job_id
WHERE rd10.10cmgml > rd50.50cmgml AND rd10.job_id = '{$jbd[0]->id}'
");
		echo $this->db->last_query();
exit;
	
		if (!empty($query) && $query->num_rows() > 0) {
			return $query->result();
		}
	}
	


	public function getresultsdata10i($id)
	{
		ini_set('memory_limit', '-1');
		$jbd = $this->projects_model->getJobdetails($id);
		$query = $this->db->query("select rd10.result_job_id, rd10.job_id,rd10.ssystem_name,rd10.10cmgml,rd10.10cvl,rd10.10cyl, rd10.wt_fraction from results_data_10 rd10, jobs_master jm where rd10.job_id=jm.id and rd10.job_id=".$jbd[0]->id);
		//echo $this->db->last_query();
		if(!empty($query) && $query->num_rows() > 0){
			return $query->result();
		}
	}


	public function getresultsdata25i($id)
	{
		ini_set('memory_limit', '-1');
		$jbd = $this->projects_model->getJobdetails($id);
		$query = $this->db->query("select rd25.result_job_id, rd25.job_id,rd25.ssystem_name,rd25.25cmgml,rd25.25cvl,rd25.25cyl, rd25.wt_fraction from results_data_25 rd25, jobs_master jm where rd25.job_id=jm.id and rd25.job_id=".$jbd[0]->id);
		//echo $this->db->last_query();
		if(!empty($query) && $query->num_rows() > 0){
			return $query->result();
		}
	}
	

	public function getresultsdata50i($id)
	{
		ini_set('memory_limit', '-1');
		$jbd = $this->projects_model->getJobdetails($id);
		$query = $this->db->query("select rd50.result_job_id,rd50.job_id,rd50.ssystem_name,rd50.50cmgml,rd50.50cvl,rd50.50cyl, rd50.wt_fraction from results_data_50 rd50, jobs_master jm where rd50.job_id=jm.id and rd50.job_id=".$jbd[0]->id);
		
		if(!empty($query) && $query->num_rows() > 0){
			return $query->result();
		}
	}
	

	public function getresultsdata10($id)
	{
		ini_set('memory_limit', '-1');
		//$query = $this->db->query("select *,jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where sm.s_id=jr.s_id and jm.id=jr.job_id and jm.project_id=".$id);
		//$query = $this->db->query("select *, jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where (jr.input_temp_10 = 10 && jr.input_temp_20='' && jr.input_temp_50='') and sm.s_id=jr.s_id and jm.id=jr.job_id and jm.project_id=".$id);
		//echo $this->db->last_query();
		$query = $this->db->query("select sm.w1_density,jr.solvents, sm.w1_solvent_system, jr.pure_data1,jr.job_id,jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where jr.input_temp_10 = 10  and sm.s_id=jr.s_id and jr.solvent_result not like '%Traceback%' and jr.solvent_result <>'' and jm.id=jr.job_id  and jm.project_id=".$id);
		

		if(!empty($query) && $query->num_rows() > 0){
			return $query->result();
		}
	}

	public function getresultsdata25($id)
	{
		ini_set('memory_limit', '-1');
		//$query = $this->db->query("select *,jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where sm.s_id=jr.s_id and jm.id=jr.job_id and jm.project_id=".$id);
		//$query = $this->db->query("select *, jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where (jr.input_temp_20 = 25 && jr.input_temp_10='' && jr.input_temp_50='') and sm.s_id=jr.s_id and jm.id=jr.job_id and jm.project_id=".$id);
		$query = $this->db->query("select sm.w1_density,jr.solvents, sm.w1_solvent_system, jr.pure_data1,jr.job_id,jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where jr.input_temp_20 = 25  and sm.s_id=jr.s_id and jr.solvent_result not like '%Traceback%' and jr.solvent_result <>'' and jm.id=jr.job_id and jm.project_id=".$id);

		if(!empty($query) && $query->num_rows() > 0){
			//echo $query->num_rows();
			return $query->result();
		}
	}


	public function getresultsdata50($id)
	{
		ini_set('memory_limit', '-1');
		//$query = $this->db->query("select *,jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where sm.s_id=jr.s_id and jm.id=jr.job_id and jm.project_id=".$id);
		//$query = $this->db->query("select *, jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where (jr.input_temp_50 = 50 && jr.input_temp_10='' && jr.input_temp_20='') and sm.s_id=jr.s_id and jm.id=jr.job_id and jm.project_id=".$id);
		$query = $this->db->query("select sm.w1_density,jr.solvents, sm.w1_solvent_system, jr.pure_data1,jr.job_id,jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where jr.input_temp_50 = 50  and sm.s_id=jr.s_id and jr.solvent_result not like '%Traceback%' and jr.solvent_result <>'' and jm.id=jr.job_id and jm.project_id=".$id);

		if(!empty($query) && $query->num_rows() > 0){
			//echo $query->num_rows();
			return $query->result();
		}
	}


	public function getresultsdata($id)
	{
		//$query = $this->db->query("select *,jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where sm.s_id=jr.s_id and jm.id=jr.job_id and jm.project_id=".$id);
		$query = $this->db->query("select *,CONCAT(jr.pure_data1) AS combined_column, jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where (jr.input_temp_10 = 10 or jr.input_temp_50 =50) and sm.s_id=jr.s_id and jr.solvent_result not like '%Traceback%' and jr.solvent_result <>'' and jm.id=jr.job_id and jm.project_id=".$id);

		if(!empty($query) && $query->num_rows() > 0){
			//echo $query->num_rows();
			return $query->result();
		}
	}

	public function getfirstvalue($val)
	{
		//$commaSeparated = "apple, banana, cherry, durian";
		$valuesArray = explode(", ", $val);
		$firstValue = $valuesArray[0];

		echo $firstValue; // Outputs "apple"
	}

	public function getfirstvalue10($val)
	{
		//$commaSeparated = "apple, banana, cherry, durian";
		$valuesArray = explode(", ", $val);
		$firstValue = $valuesArray[0];

		return $firstValue; // Outputs "apple"
	}

	public function getlat10($val)
	{
		$valuesArray = explode(", ", $val);
		$firstValue = $valuesArray[0];
		
		//echo "Log ".$firstValue;

		//$finalv=(((-0.3688)*((int)$firstValue))-0.4287);
		$finalv=((-0.3688)*((int)$firstValue));
		$finalk = (($finalv-0.4287));
		return $finalk; // Outputs "apple"
	}

	public function get10mgml($val,$jid)
	{
		//echo $val;
		$mol_weight=180.15;
		$mol = $this->projects_model->getJobstatus($jid);

		if(($mol[0]->mol_weight)<>"") {

			$mol_weight = $mol[0]->mol_weight;
		}

			//echo "Mol" . $mol_weight;
		
		$valuesArray = explode(", ", $val);
		$firstValue = $valuesArray[0];
		$finalv=((-0.3688)*($firstValue));
		$finalk = (($finalv-0.4287));
		//echo round($finalv,4); // Outputs "apple"
		$result = round(pow(10, $finalk),4);

		//echo round(($result*180.15),4); // Outputs 100000
		echo $result*$mol_weight;
	}

	public function get10cvl($val,$density,$jid)
	{
		//echo $val;
		//(1000-((AT10/Density) /(AT10/Density) )
		//echo "D".$density."D";
		$mol = $this->projects_model->getJobstatus($jid);

		if(($mol[0]->mol_weight)<>"") {

			$mol_weight = $mol[0]->mol_weight;
		}

		$valuesArray = explode(", ", $val);
		$firstValue = $valuesArray[0];
		
		$finalv=((-0.3688)*($firstValue));
		$finalk = (($finalv-0.4287));
		//echo round($finalv,4); // Outputs "apple"
		$result = round(pow(10, $finalk),4);

		//echo round(($result*180.15),4); // Outputs 100000
		$mgmlval= $result*$mol_weight;

		$mgmlval=$mgmlval/$density;

		$ff=(1000-$mgmlval);
		if($mgmlval>0) {
		$ff = ($ff/$mgmlval);
		echo $ff;
		}

		//echo round(($result*180.15),4); // Outputs 100000
	}

	public function get10cY($val)
	{
		
		$valuesArray = explode(", ", $val);
		$firstValue = $valuesArray[0];
		$finalv=(((-0.3688)*((int)$firstValue))-0.4287);
		//echo round($finalv,4); // Outputs "apple"
		$result = round(pow(10, $finalv),4);

		$tenmgml = round(($result*180.15),4); // Outputs 100000
		if($this->session->userdata('mgml')){
		$final = (($this->session->userdata('mgml')-$firstValue)/$this->session->userdata('mgml')*100);
		}
		else {

			if($tenmgml<>0) {
			$final = (($tenmgml-(int)$firstValue)/$tenmgml*100);
			echo round($final,4);
			}
		}
		
		
		$this->session->unset_userdata('mgml');


	}


	public function get10cYN($val,$jid,$jobid,$w1)
	{
		//echo $jobid;

		//echo $w1;

		$ffmg = $this->projects_model->get10mgdata($jobid,$w1,"50");
		//print_r($ffmg);


		//echo $val;
		$mol_weight=180.15;
		$mol = $this->projects_model->getJobstatus($jid);

		if(($mol[0]->mol_weight)<>"") {

			$mol_weight = $mol[0]->mol_weight;
		}

			//echo "Mol" . $mol_weight;
		
		$valuesArray = explode(", ", $ffmg[0]->pdata);
		$firstValue = $valuesArray[0];
		$finalv=((-0.3688)*($firstValue));
		$finalk = (($finalv-0.4287));
		//echo round($finalv,4); // Outputs "apple"
		$result = round(pow(10, $finalk),4);

		//echo round(($result*180.15),4); // Outputs 100000
		$fiftymg= $result*$mol_weight;

		$tenmgml = $this->projects_model->get10mgdata($jobid,$w1,"10");
//print_r($tenmgml);


		$valuesArray1= explode(", ",$tenmgml[0]->pdata);

		//$valuesArray = explode(", ", $val);
		$firstValue10 = $valuesArray1[0];
		$finalv10=((-0.3688)*($firstValue10));
		$finalk10 = (($finalv10-0.4287));
		//echo round($finalv,4); // Outputs "apple"
		$result10 = round(pow(10, $finalk10),4);

		//echo round(($result*180.15),4); // Outputs 100000
		$tenmg= $result10*$tenmgml[0]->mol_weight;
		//echo "Fifty ".$fiftymg ."<br>";
		//echo "Ten ".$tenmg ."<br>";

		$calu = $fiftymg-$tenmg;
		//echo "Minus " .$calu;
		if($fiftymg>0) {
		$calu = $calu/$fiftymg;

		$calfinal = $calu*100;
		
		echo $calfinal;
	}

	}
public function get10mgdata($jid,$w1,$temp){

	if($temp==10) {
	$query = $this->db->query("select jr.pure_data1 as pdata,jm.mol_weight as mol_weight from job_results jr, jobs_master jm where jr.solvents='".$w1."' and jr.job_id=$jid and jr.job_id=jm.id and jr.input_temp_10=10");
	}
	if($temp==50) {
		$query = $this->db->query("select jr.pure_data1 as pdata,jm.mol_weight as mol_weight from job_results jr, jobs_master jm where jr.solvents='".$w1."' and jr.job_id=$jid and jr.job_id=jm.id and jr.input_temp_50=50");
		}
	
	//echo $this->db->last_query();

	return $query->result();

}
	public function get10mgmlcal($val,$jid)
	{
		//echo $val;

			//echo $val;
			$mol_weight=180.15;
			$mol = $this->projects_model->getJobstatus($jid);
	
			if(($mol[0]->mol_weight)<>"") {
	
				$mol_weight = $mol[0]->mol_weight;
			}
	
				//echo "Mol" . $mol_weight;
			
			$valuesArray = explode(", ", $val);
			$firstValue = $valuesArray[0];
			$finalv=((-0.3688)*($firstValue));
			$finalk = (($finalv-0.4287));
			//echo round($finalv,4); // Outputs "apple"
			$result = round(pow(10, $finalk),4);
	
			//echo round(($result*180.15),4); // Outputs 100000
			return $result*$mol_weight;
	}


	public function get10mgmlforscatter($val,$jid)
	{
		//echo $val;
		$mol_weight=180.15;
		$mol = $this->projects_model->getJobstatus($jid);

		if(($mol[0]->mol_weight)<>"") {

			$mol_weight = $mol[0]->mol_weight;
		}

		
		$valuesArray = explode(", ", $val);
		$firstValue = $valuesArray[0];
		$finalv=(((-0.3688)*($firstValue))-0.4287);
		//echo round($finalv,4); // Outputs "apple"
		$result = round(pow(10, $finalv),4);

		//echo round(($result*180.15),4); // Outputs 100000
		return round(($result*$mol_weight),4);
	}

	public function get10cvlscatter($val,$density,$jid)
	{
		$mol = $this->projects_model->getJobstatus($jid);

		if(($mol[0]->mol_weight)<>"") {

			$mol_weight = $mol[0]->mol_weight;
		}

		$valuesArray = explode(", ", $val);
		$firstValue = $valuesArray[0];
		
		$finalv=((-0.3688)*($firstValue));
		$finalk = (($finalv-0.4287));
		//echo round($finalv,4); // Outputs "apple"
		$result = round(pow(10, $finalk),4);

		//echo round(($result*180.15),4); // Outputs 100000
		$mgmlval= $result*$mol_weight;

		$mgmlval=$mgmlval/$density;

		$ff=(1000-$mgmlval);
		if($mgmlval>0) {
		$ff = ($ff/$mgmlval);
		return $ff;
		}
	}
		//echo round(($result*180.15),4); // Outputs 100000
	


	public function get_scatterdatap($id) {
		//$this->db->limit(10);
       // $query = $this->db->query("SELECT Solvent_System,API_Cooling_yield, API_Solvent_vol_50C FROM sresults where (API_Solvent_vol_50C between 1 and 100) and (API_Cooling_yield >0)");
		$query = $this->db->query("select sm.w1_density, sm.w1_solvent_system, jr.pure_data1,jr.job_id,jr.id as jbid from solvents_master sm, job_results jr, jobs_master jm where jr.input_temp_10 = 10  and sm.s_id=jr.s_id and jm.id=jr.job_id and jm.project_id=".$id." limit 100");

        return $query->result_array();
    }

	public function getjobscompleted()
	{
		$query = $this->db->query("select DISTINCT(pr.id) as pid, pr.project_name from projects pr, jobs_master jm, job_results_count jrc where pr.id=jm.project_id and jrc.job_id=jm.id and jrc.status='Completed' order by pid desc");
		
        return $query->result_array();
	}

	public function get10cYforscatter($val)
	{
		
		$valuesArray = explode(", ", $val);
		$firstValue = $valuesArray[0];
		$finalv=(((-0.3688)*($firstValue))-0.4287);
		//echo round($finalv,4); // Outputs "apple"
		$result = round(pow(10, $finalv),4);

		$tenmgml = round(($result*180.15),4); // Outputs 100000
		if($this->session->userdata('mgml')){
		$final = (($this->session->userdata('mgml')-$firstValue)/$this->session->userdata('mgml')*100);
		}
		else {

			if($tenmgml<>0) {
			$final = (($tenmgml-$firstValue)/$tenmgml*100);
			return round($final,4);
			}
		}
	}


	public function checkjobcompleted($id) {
		//echo $id;

		$query = $this->db->query("select jrc.status from jobs_master jm, job_results_count jrc where jrc.job_id=jm.id and jm.project_id=".$id);

		//echo $this->db->last_query();
		// validate user
		if(!empty($query) && $query->num_rows() > 0){
			//echo $query->num_rows();
			$checkjob = $query->result_array();
			$allCompleted = true;
			if($checkjob) {
			foreach($checkjob as $status) {
				if($status['status'] !== "Completed") {
					$allCompleted = false;
					break;
				}
			}
			
			if($allCompleted) {
			  return 1;
			} else {
			  return 0;
			}
			}

			//return $query->result_array();
		}

	}

	public function getresults($id) {

		$query = $this->db->query("SELECT DISTINCT(jrc.solvent_type) FROM `jobs_master` jm, job_results jr, job_results_count jrc where jm.id=jr.job_id and jr.job_id=jrc.job_id and jm.project_id=$id");
		if($query->num_rows() > 0)
		return $query->result();
	}

	public function getresultsfull($id,$jid,$resulttype) {
		//$query = $this->db->query("SELECT jr.solvents,jr.solvent_result_name,jr.solvent_result FROM `jobs_master` jm, job_results jr, job_results_count jrc where jm.id=jr.job_id and jr.job_id=jrc.job_id and jm.project_id=$id and jr.job_id=$jid and jr.result_type='".$resulttype."'");
		$query = $this->db->query("SELECT * FROM job_results where result_type='".$resulttype."' and job_id=$jid");
		//echo $this->db->last_query();
		return $query->result_array();
	}

	public function getJobdetails ($id) {

		$this->db->select('*');
		$this->db->from('jobs_master');
		//$this->db->join('tbl_lining', 'products.lining=tbl_lining.id', 'left');
		$this->db->where('project_id', $id);  // Also mention table name here
		$query = $this->db->get();    
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		return $query->result();

	}

	public function getJobdetails1 ($id) {

		$this->db->select('*');
		$this->db->from('jobs_master');
		//$this->db->join('tbl_lining', 'products.lining=tbl_lining.id', 'left');
		$this->db->where('id', $id);  // Also mention table name here
		$query = $this->db->get();    
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		return $query->result();

	}

	public function getprojectdetails ($id) {

		$this->db->select('*');
		$this->db->from('projects');
		//$this->db->join('tbl_lining', 'products.lining=tbl_lining.id', 'left');
		$this->db->where('id', $id);  // Also mention table name here
		$query = $this->db->get();    
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		return $query->result();

	}

	public function getJobstatus ($id) {

		$this->db->select('*');
		$this->db->from('jobs_master');
		//$this->db->join('tbl_lining', 'products.lining=tbl_lining.id', 'left');
		$this->db->where('id', $id);  // Also mention table name here
		$query = $this->db->get();    
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		return $query->result();

	}

	public function getPython () {

		$this->db->select('*');
		$this->db->from('python_master');
		//$this->db->join('tbl_lining', 'products.lining=tbl_lining.id', 'left');
		$this->db->where('id', "1");  // Also mention table name here
		$query = $this->db->get();    
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		return $query->result();

	}


	public function getsolvent_byjbid ($id) {

		//$query = $this->$db->query(');

		$query = $this->db->query("select sm.w1_solvent_system from job_results jr, solvents_master sm where jr.s_id=sm.s_id and jr.id=".$id);
		
		if(!empty($query) && $query->num_rows() > 0){
			$row = $query->result();
			echo $row[0]->w1_solvent_system;
		}


	

	}

	public function getdensity_bp ($id) {

		//$query = $this->$db->query('select * from solvents_master where s_id='.$id);
		$this->db->select('*');
		$this->db->from('solvents_master');
		//$this->db->join('tbl_lining', 'products.lining=tbl_lining.id', 'left');
		$this->db->where('s_id', $id);  // Also mention table name here
		$query = $this->db->get();    
		//echo $this->db->last_query();
		$row = $query->result();
		echo "Density: ".$row[0]->w1_density;
		echo ", BP: ".$row[0]->w1_bp;
		//return $query->result();

	}

	public function login($row, $remember = false)
	{
		$time = time();

		// encypting userid and password with current time $time
		$login_token = sha1($row->id.$row->password.$time);

		if($remember===false){
			$array = [
				'login' => true,
				// saving encrypted userid and password as token in session
				'login_token' => $login_token,
				'logged' => [
					'id' => $row->id,
					'time' => $time,
				]
			];
			$this->session->set_userdata( $array );
		}else{

			$data = [
				'id' => $row->id,
				'time' => time(),
			];
			$expiry = strtotime('+7 days');
			set_cookie( 'login', true, $expiry );
			set_cookie( 'logged', json_encode($data), $expiry );
			set_cookie( 'login_token', $login_token, $expiry );

		}

		setUserlang('es');


		$this->update($row->id, [
			'last_login' => date('Y-m-d H:m:i')
		]);

		$this->activity_model->add($row->name.' ('.$row->username.') Logged in', $row->id);

	}


public function get_horibar(){

	$query = $this->db->query("SELECT * from sresults");
   return $query->result();
}

	public function get_scatterdata() {
		//$this->db->limit(10);
        $query = $this->db->query("SELECT Solvent_System,API_Cooling_yield, API_Solvent_vol_50C FROM sresults where (API_Solvent_vol_50C between 1 and 100) and (API_Cooling_yield >0)");
		
        return $query->result_array();
    }

    public function getRangeData($set, $set1Start, $selapi_10_operator,$set1End, $selapi_50_operator,$set2Start, $selimp1_10_operator,$set2End, $selimp1_50_operator,$set3Start, $selimp2_10_operator,$set3End, $selimp2_50_operator,$recordsp)
    {

//echo $start;
   $tt="";

If($set=1) {
if($selapi_10_operator =='greater_than') {
 	$this->db->where('10c_mg_ml >=', (float) $set1Start);
}


if($selapi_50_operator =='greater_than') {
	$this->db->where('50C_mg_ml >=', (float) $set1End);
}

if($selimp1_10_operator =='greater_than') {
	$this->db->where('IMP1_mgml_10C >=', (float) $set2Start);
}

if($selimp1_50_operator =='greater_than') {
 	$this->db->where('IMP1_50C_mg_ml >=', (float) $set2End);
}

if($selimp2_10_operator =='greater_than') {
 	$this->db->where('IMP2_mgml_10C >=', (float) $set3Start);
}

if($selimp2_50_operator =='greater_than') {
 	$this->db->where('IMP2_mgml_50C >=', (float) $set3End);
}


	

if($selapi_10_operator =='less_than') {
 	$this->db->where('10c_mg_ml <=', (float) $set1Start);
}

if($selapi_50_operator =='less_than') {
 	$this->db->where('50C_mg_ml <=', (float) $set1End);
}

if($selimp1_10_operator =='less_than') {
 	$this->db->where('IMP1_mgml_10C <=', (float) $set2Start);
}

if($selimp1_50_operator =='less_than') {
 	$this->db->where('IMP1_50C_mg_ml <=', (float) $set2End);
}

if($selimp2_10_operator =='less_than') {
 	$this->db->where('IMP2_mgml_10C <=', (float) $set3Start);
}

if($selimp2_50_operator =='less_than') {
 	$this->db->where('IMP2_mgml_50C <=', (float) $set3End);
}

	


}


    // $set1Data = $chartModel->getRangeData(1, $set1Start, $selapi_10_operator,$set1End, $selapi_50_operator,$set2Start, $selimp1_10_operator,$set2End, $selimp1_50_operator,$set3Start, $selimp2_10_operator,$set3End, $selimp2_50_operator);

$this->db->select('*,CAST(log10_10C AS float)');
	$this->db->select('CAST(log10_50C AS float)');
	$this->db->select('CAST(IMP1_mgml_10C AS float)');
	$this->db->select('CAST(IMP1_50C_mg_ml AS float)');
	$this->db->select('CAST(IMP2_mgml_10C AS float)');
	$this->db->select('CAST(IMP2_mgml_50C AS float)');
	
	$this->db->limit($recordsp);
$query = $this->db->get('sresults');
	//print_r($this->db->last_query());
	

	$results = $query->result_array();
    return $results;
        //return $query->result_array();
    }



  function get_chart_data() {
$this->db->limit(10);
        $query = $this->db->get('sresults');
	
        $results['chart_data'] = $query->result();
        
        return $results;
    }

 function fetch_year()
 {
  //$this->db->select('year');
  $this->db->from('sresults');
  //$this->db->group_by('year');
  //$this->db->order_by('year', 'DESC');
  return $this->db->get();
 }

function fetch_chart_data()
 {
  //$this->db->where('year', $year);
  //$this->db->order_by('year', 'ASC');
  print_r($this->db->get('sresults'));
exit;
 }

	public function logout()
	{
		// Deleting Sessions
		$this->session->unset_userdata('login');
		$this->session->unset_userdata('logged');
		// Deleting Cookie
		delete_cookie('login');
		delete_cookie('logged');
		delete_cookie('login_token');
	}
	

	public function resetPassword($data)
	{

		$this->db->where('username', $data['username']);
		$this->db->or_where('email', $data['username']);

		$user = $this->db->get_where($this->table)->row();

		if(!empty($user)){ }else{
			return 'invalid';
		}

		$reset_token	=	password_hash((time().$user->id), PASSWORD_BCRYPT);

		$this->db->where('id', $user->id);
		$this->db->update($this->table, compact('reset_token'));

		$this->email->from(setting('company_email'), setting('company_name') );
		$this->email->to($user->email);

		$this->email->subject('Reset Your Account Password | ' . setting('company_name') );

		$reset_link = url('login/new_password?token='.$reset_token);

		$data = getEmailShortCodes();
		$data['user_id'] = $user->id;
		$data['user_name'] = $user->name;
		$data['user_email'] = $user->email;
		$data['user_username'] = $user->username;
		$data['reset_link'] = $reset_link;

		$html = $this->parser->parse('templates/email/reset', $data, true);

		$this->email->message( $html );

		$this->email->send();

		return $user->email;

	}

	public function appendToSelectStr() {
			return NULL;
	}

	public function fromTableStr() {
		return 'users';
	}

	public function joinArray(){
		return NULL;
	}

	public function whereClauseArray(){
		return NULL;
	}


	public function getRangeDataA($set, $set1Start,$set2Start,$set3Start, $selapi_50_operator,$selapi_10_operator ,$selapi_20_operator,$projectid,$recordsp)
    {


//echo $start;
   $tt="";

If($set=1) {

	if($selapi_50_operator =='greater_than') {
		$this->db->where('50C_mg_ml >=', (float) $set1_start);
	}

if($selapi_20_operator =='greater_than') {
 	$this->db->where('20c_mg_ml >=', (float) $set1Start);
}




if($selimp1_10_operator =='greater_than') {
	$this->db->where('IMP1_mgml_10C >=', (float) $set2Start);
}

if($selimp1_50_operator =='greater_than') {
 	$this->db->where('IMP1_50C_mg_ml >=', (float) $set2End);
}

if($selimp2_10_operator =='greater_than') {
 	$this->db->where('IMP2_mgml_10C >=', (float) $set3Start);
}

if($selimp2_50_operator =='greater_than') {
 	$this->db->where('IMP2_mgml_50C >=', (float) $set3End);
}


	

if($selapi_10_operator =='less_than') {
 	$this->db->where('10c_mg_ml <=', (float) $set1Start);
}

if($selapi_50_operator =='less_than') {
 	$this->db->where('50C_mg_ml <=', (float) $set1End);
}

if($selimp1_10_operator =='less_than') {
 	$this->db->where('IMP1_mgml_10C <=', (float) $set2Start);
}

if($selimp1_50_operator =='less_than') {
 	$this->db->where('IMP1_50C_mg_ml <=', (float) $set2End);
}

if($selimp2_10_operator =='less_than') {
 	$this->db->where('IMP2_mgml_10C <=', (float) $set3Start);
}

if($selimp2_50_operator =='less_than') {
 	$this->db->where('IMP2_mgml_50C <=', (float) $set3End);
}

	


}


    // $set1Data = $chartModel->getRangeData(1, $set1Start, $selapi_10_operator,$set1End, $selapi_50_operator,$set2Start, $selimp1_10_operator,$set2End, $selimp1_50_operator,$set3Start, $selimp2_10_operator,$set3End, $selimp2_50_operator);

$this->db->select('*,CAST(log10_10C AS float)');
	$this->db->select('CAST(log10_50C AS float)');
	$this->db->select('CAST(IMP1_mgml_10C AS float)');
	$this->db->select('CAST(IMP1_50C_mg_ml AS float)');
	$this->db->select('CAST(IMP2_mgml_10C AS float)');
	$this->db->select('CAST(IMP2_mgml_50C AS float)');
	
	$this->db->limit($recordsp);
$query = $this->db->get('sresults');
	//print_r($this->db->last_query());
	

	$results = $query->result_array();
    return $results;
        //return $query->result_array();
    }



	public function getRangeDataA1($set, $set1Start,$set2Start,$set3Start, $selapi_50_operator,$selapi_10_operator ,$selapi_20_operator,$projectid,$recordsp)
    {
		$jbd = $this->projects_model->getJobdetails($projectid);

		//echo $set;
	//echo $jbd[0]->id;

//echo $start;
   $tt="";
  // $recordsp=$recordsp;
   
If($set==1) {


if($selapi_10_operator =='greater_than') {
	$this->db->where('results_data_10.10cmgml >=', (float) $set3Start);
	$this->db->where('results_data_10.10cmgml <=', 200);

}

if($selapi_10_operator =='less_than') {
$this->db->where('results_data_10.10cmgml <=', (float) $set3Start);
//$this->db->order_by('results_data_10.10cmgml', 'desc');
}


// Check if the session for s_ids exists
//if (isset($_SESSION['s_ids'])) {
    // Retrieve the s_id values from the session
   // $sIds = $_SESSION['s_ids'];
   // $this->db->where_in('job_results.s_id', $sIds);
//}

	//$this->db->select('results_data_10.*,CAST(at10 AS float), job_results.s_id');
	//$this->db->where('results_data_10.job_id =', $jbd[0]->id);
	//$this->db->limit($recordsp);
	//$this->db->from('results_data_10');
	//$this->db->order_by('results_data_10.id');

	//$this->db->join('job_results', 'job_results.id = results_data_10.result_job_id', 'left');

	$this->db->select('results_data_10.*, CAST(at10 AS float), job_results.s_id, solvents_master.w1_solvent_system');
	$this->db->where('results_data_10.job_id', $jbd[0]->id);
	//$this->db->order_by('results_data_10.10cmgml', 'desc');

	$this->db->limit($recordsp);
	$this->db->from('results_data_10');
	//$this->db->order_by('results_data_10.id');

	$this->db->join('job_results', 'job_results.id = results_data_10.result_job_id', 'left');
	$this->db->join('solvents_master', 'solvents_master.s_id = job_results.s_id', 'left');


    $query =  $this->db->get();
    return $query->result_array();
	//echo $this->db->last_query();

    }

	If($set==2) {


		if($selapi_20_operator =='greater_than') {
			$this->db->where('results_data_25.25cmgml >=', (float) $set2Start);
			$this->db->where('results_data_25.25cmgml <=', 200);
			//$this->db->order_by('results_data_25.25cmgml', 'desc');

		}
		
		if($selapi_20_operator =='less_than') {
		$this->db->where('results_data_25.25cmgml <=', (float) $set2Start);
		//$this->db->where('results_data_25.25cmgml <=', 200);

		}
		
			// Check if the session for s_ids exists
			//if (isset($_SESSION['s_ids'])) {
    		// Retrieve the s_id values from the session
   			// $sIds = $_SESSION['s_ids'];
    		//$this->db->where_in('job_results.s_id', $sIds);
			//}
				//$this->db->select('*,CAST(at25 AS float)');
				//$this->db->where('job_id =', $jbd[0]->id);
				//$this->db->limit($recordsp);
				//$query = $this->db->get('results_data_25');
				//return $query->result_array();

				$this->db->select('results_data_25.*,CAST(at25 AS float), job_results.s_id, solvents_master.w1_solvent_system');
				$this->db->where('results_data_25.job_id =', $jbd[0]->id);
				//$this->db->order_by('results_data_25.25cmgml', 'desc');

				$this->db->limit($recordsp);
				
				$this->db->from('results_data_25');
				

				$this->db->order_by('results_data_25.id');

				$this->db->join('job_results', 'job_results.id = results_data_25.result_job_id', 'left');
				$this->db->join('solvents_master', 'solvents_master.s_id = job_results.s_id', 'left');

				$query =  $this->db->get();
				return $query->result_array();
				//echo $this->db->last_query();

		}

		If($set==3) {

			//$set1Start="5000";
			if($selapi_50_operator =='greater_than') {
				$this->db->where('results_data_50.50cmgml >=', (float) $set1Start);
				$this->db->where('results_data_50.50cmgml <=', 200);

				//$this->db->where('results_data_50.50cmgml <=', (float) 200);
			}
			
			if($selapi_50_operator =='less_than') {
			$this->db->where('results_data_50.50cmgml <=', (float) $set1Start);
			//$this->db->order_by('results_data_50.50cmgml', 'desc');

			}
			
			// Check if the session for s_ids exists
			//if (isset($_SESSION['s_ids'])) {
   			 // Retrieve the s_id values from the session
    		///$sIds = $_SESSION['s_ids'];
    		//$this->db->where_in('job_results.s_id', $sIds);
			//}
					//$this->db->select('*,CAST(at50 AS float)');
					//$this->db->where('job_id =', $jbd[0]->id);
					//$this->db->limit($recordsp);
				 	//$query = $this->db->get('results_data_50');
					//return $query->result_array();

					$this->db->select('results_data_50.*,CAST(at50 AS float), job_results.s_id, solvents_master.w1_solvent_system');
					$this->db->where('results_data_50.job_id =', $jbd[0]->id);
					$this->db->limit($recordsp);
					$this->db->from('results_data_50');
					//$this->db->order_by('results_data_50.50cmgml', 'desc');

					//$this->db->order_by('results_data_50.id');

		
					$this->db->join('job_results', 'job_results.id = results_data_50.result_job_id', 'left');
					$this->db->join('solvents_master', 'solvents_master.s_id = job_results.s_id', 'left');

					$query =  $this->db->get();
					return $query->result_array();
				//echo $this->db->last_query();
			}
			//print_r($this->db->last_query());	
			

}




}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */