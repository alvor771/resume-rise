<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set a secure password for the admin user
        $password = '7714444'; // This is just an example, please change it after first login
        
        // Update the admin user with a properly hashed password
        DB::table('users')
            ->where('email', 'work.alvor@gmail.com')
            ->update([
                'password' => Hash::make($password),
                'updated_at' => now(),
            ]);
            
        echo "Admin password has been updated. New password: " . $password . "\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way migration, no need to implement down
    }
};
