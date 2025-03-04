<?php
namespace App\Services;

class UserService{ 
    // function getUserById 
    public function getUserById($id) {
        // Assuming you have a User model and a database connection
        return \App\Models\User::find($id);
    }

    // function getAllUser
    public function getAllUser() {
        // Assuming you have a User model and a database connection
        return \App\Models\User::all();
    }
    
}
?>