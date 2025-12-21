<?php

class Model_auth extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function check_email($email)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');

        return $query->num_rows() > 0;
    }

    public function login($email, $password)
    {
        $this->db->where('email', $email);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            $result = $query->row_array();

            // Check if the stored password is hashed
            if (password_verify($password, $result['password'])) {
                return $result;
            } elseif ($result['password'] === md5($password)) {
                // Update the stored password to the hashed version
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $this->db->where('id', $result['id']);
                $this->db->update('users', ['password' => $hashed_password]);

                return $result;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
?>

