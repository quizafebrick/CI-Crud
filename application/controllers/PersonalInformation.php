<?php

class PersonalInformation extends CI_Controller
{
	public function getInfos()
    {
        return $this->PersonalInfo_Model->loadDatatable();
    }

	public function createRandomInfo()
    {
        return $this->PersonalInfo_Model->generate_fake_data();
    }

	public function index()
	{
		$page = "index";

		if (!file_exists(APPPATH.'views/pages/'.$page. '.php')) {
			show_404();
		}		

		$data['title'] = "Personal Information";

		$this->load->view('template/header');
		$this->load->view('pages/'.$page, $data);
		$this->load->view('template/footer');
	}

	public function store()
	{
		$fullname = $this->input->post('fullname');
		$age = $this->input->post('age');
		$gender = $this->input->post('gender');
		$address = $this->input->post('address');

		$data = array(
			'fullname' => $fullname,
			'age' => $age,
			'gender' => $gender,
			'address' => $address
		);

		$this->load->model('PersonalInfo_Model');
		$insert = $this->PersonalInfo_Model->createData($data);

		if (!$insert) {
			echo json_encode(['success' => false, 'error' => 'Database error']); 
		} 
		
		echo json_encode(['success' => true]);
	}

	public function update()
	{
		$fullname = $this->input->post('fullname');
		$age = $this->input->post('age');
		$gender = $this->input->post('gender');
		$address = $this->input->post('address');

		$data = array(
			'fullname' => $fullname,
			'age' => $age,
			'gender' => $gender,
			'address' => $address
		);

		$this->load->model('PersonalInfo_Model');
		$update = $this->PersonalInfo_Model->updateData(base64_decode($this->input->post('id')), $data);

		if (!$update) {
			log_message('error', $this->db->error());
			echo json_encode(['success' => false, 'error' => 'Database error']); 
		} 
		
		echo json_encode(['success' => true]);
	}

	public function updateIsStudent()
	{
		$data = array('isStudent' => $this->input->post('isStudent') ? 1 : 0);

		$this->load->model('PersonalInfo_Model');
		$updateIsStudent = $this->PersonalInfo_Model->updateData(base64_decode($this->input->post('id')), $data);

		if (!$updateIsStudent) {
			echo json_encode(['success' => false, 'error' => 'Database error']);
		} 

		echo json_encode(['success' => true]);
	}

	public function updateIsStudentBatch()
	{
		$this->load->model('PersonalInfo_Model');

		foreach ($this->input->post('id') as $decryptedID) {
			$updateIsStudent = $this->PersonalInfo_Model->updateDataBatch(base64_decode($decryptedID));

			if (!$updateIsStudent) {
				echo json_encode(['success' => false, 'error' => 'Database error']);
				return;
			}
		}

		echo json_encode(['success' => true]);
	}

	public function destroy()
	{
		$this->load->model('PersonalInfo_Model');
		$delete = $this->PersonalInfo_Model->deleteData(base64_decode($this->input->post('id')));

		if (!$delete) {
			echo json_encode(['success' => false, 'error' => 'Database error']); 
		}
		
		echo json_encode(['success' => true]);
	}

	public function destroyBatch()
	{
		if (empty($this->input->post('id'))) {
			echo json_encode(['success' => false, 'error' => 'No IDs provided for batch delete']);
		}

		$this->load->model('PersonalInfo_Model');
		$success = true;

		foreach ($this->input->post('id') as $decryptedIDs) {
			$delete = $this->PersonalInfo_Model->deleteData(base64_decode($decryptedIDs));

			if (!$delete) {
				$success = false;
				break;
			}
		}

		echo json_encode(['success' => $success]);
	}

	public function destroyBatchNotStudent()
	{
		if (empty($this->input->post('id'))) {
			echo json_encode(['success' => false, 'error' => 'No IDs provided for batch delete']);
		}

		$this->load->model('PersonalInfo_Model');
		$success = true;

		foreach ($this->input->post('id') as $decryptedIDs) {
			$delete = $this->PersonalInfo_Model->deleteDataBatchNotStudent(base64_decode($decryptedIDs));

			if (!$delete) {
				$success = false;
				break;
			}
		}

		echo json_encode(['success' => $success]);
	}
}
