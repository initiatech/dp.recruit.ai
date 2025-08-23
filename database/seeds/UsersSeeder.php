<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    public function getDependencies(): array
    {
        return [];
    }

    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'full_name' => 'מנהל מערכת',
                'email'    => 'admin@example.com',
                'role' => 'admin',
                'status' => 'active',
                'password_hash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // "password"
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'full_name' => 'מגייס ראשי',
                'email'    => 'recruiter@example.com',
                'role' => 'recruiter',
                'status' => 'active',
                'password_hash' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // "password"
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $users = $this->table('users');
        // Truncate table to start from a clean state
        $users->truncate();
        $users->insert($data)->saveData();
    }
}
