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
}
