<?php 

class PersonalInfo_Model extends CI_Model 
{
	public function __construct()
	{
		$this->load->database();
	}

	public function getInfo()
	{
		$query = $this->db->get('personal_informations');

		return $query->result_array();
	}

	public function generate_fake_data()
    {
        $faker = Faker\Factory::create();

        // * ARRAY TO STORE FAKE DATA * //
        $fake_data = [];

        for ($i = 0; $i < 20; $i++) {
            $fake_data[] = [
                'fullname' => $faker->firstName . ' ' . $faker->lastName,
                'age' => $faker->numberBetween(18, 65),
                'gender' => $faker->randomElement(['MALE', 'FEMALE']),
                'address' => $faker->address,
            ];
        }

        // * SAVE THE GENERATED DATA TO THE DATABASE * //
        foreach ($fake_data as $data) {
            $this->PersonalInfo_Model->createData($data);
        }

        // * RETURN THE GENERATED DATA AS A JSON RESPONSE * //
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($fake_data));
    }

	public function createData($data)
	{
		return $this->db->insert('personal_informations', $data);
	}

	public function updateData($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('personal_informations', $data);

        return $this->db->affected_rows() > 0;
    }

	public function updateDataBatch($id)
    {
		$data = array('isStudent' => 1);

        $this->db->where('id', $id);
        $this->db->update('personal_informations', $data);

        return $this->db->affected_rows() > 0;
    }

	public function deleteData($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('personal_informations');

        return $this->db->affected_rows() > 0;
    }

	public function deleteDataBatch($ids)
    {
        $this->db->where_in('id', $ids);
        $this->db->delete('personal_informations');

        return $this->db->affected_rows() > 0;
    }

	public function loadDatatable()
	{
		require_once APPPATH . 'libraries/ssp.class.php';

		$table = '(SELECT *FROM personal_informations ORDER BY fullname) temp';

		$primaryKey = 'id';

		$counter = isset($_GET['start']) ? $_GET['start'] + 1 : 1;

		$columns = array(
			array('db' => 'id', 'dt' => 0, 'formatter' => function() use (&$counter) {
				// * AMPERSAND MEANS IT REFERENCE THE VARIABLE COUNTER TO PASS THE VALUE, THEN PERFORM THE INCREMENTING * //
				return $counter++;
			}),
			array('db' => 'id', 'dt' => 1,
				'formatter' => function($data, $row) {
					$disabled = $row['isStudent'] == 1 ? 'disabled' : '';
					return '<div class="form-check d-flex justify-content-center">' .
								'<input class="form-check-input" type="checkbox" name="checkboxStudentID" id="checkboxStudentID" data-id="' . base64_encode($data) . '" data-is_student="' . $row['isStudent'] . '"  ' . $disabled . '>' .
							'</div>';
			}),
			array('db' => 'fullname', 'dt' => 2),
			array('db' => 'age', 'dt' => 3),
			array('db' => 'gender', 'dt' => 4),
			array('db' => 'address', 'dt' => 5),
			array('db' => 'isStudent', 'dt' => 6,
				'formatter' => function($data, $row) {
					return '<div class="form-check form-switch d-flex justify-content-center">' .
								'<input class="form-check-input p-2 isStudent" type="checkbox" name="isStudent" id="is_student" data-id="'. base64_encode($row['id']) . '" value="1" ' . ($data == 1 ? ' checked' : '') . '>' .
							'</div>';
			}),
			array('db' => 'id', 'dt' => 7,
				'formatter' => function($data, $row) {
					return '<button class="btn btn-primary editBtn" id="editBtn" data-fullname="' . $row['fullname'] . '" data-gender="' . $row['gender'] . '" data-address="' . $row['address'] .  '" data-age="' . $row['age'] .  '" data-id="' . base64_encode($data) . '">'.
									'<i class="bi bi-pencil-square"></i>' .
							'</button > '.

							'<button class="btn btn-danger deleteBtn text-white ml-2" id="deleteBtn" data-id="' . base64_encode($data) . '">'.   
								'<i class="bi bi-trash3-fill"></i>' .
							'</button > ';
			}),
		);
	
		$sql_details = array(
			'user' => $this->db->username,
			'pass' => $this->db->password,
			'db' => $this->db->database,
			'host' => $this->db->hostname,
		);
		
		$data = SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns);

		echo json_encode($data);
	}
}
