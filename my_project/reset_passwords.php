<?php

// Check if the script is being run from the command line
if (php_sapi_name() == "cli") {
    $_SERVER['HTTP_HOST'] = 'localhost'; // Set your desired default host
}

// Include CodeIgniter framework
require 'index.php';  // Adjust the path according to your project structure

class Reset_Passwords extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_users');
    }

    public function index()
    {
        // Define new passwords for users
        $passwords = [
            'admin@admin.com' => 'newpassword123',
            'ramon.matos@upr.edu' => 'newpassword123',
            'newadmin@example.com' => 'newpassword123',
            'testing@name.com' => 'newpassword123',
            'test@test.com' => 'newpassword123',
        ];

        foreach ($passwords as $email => $new_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $this->db->where('email', $email);
            $this->db->update('users', ['password' => $hashed_password]);
        }

        echo "Passwords have been reset.";
    }
}

// Create an instance and run the reset function
$reset_passwords = new Reset_Passwords();
$reset_passwords->index();

?>

