<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

class Tsa2Seeder extends Seeder
{
    public function run()
    {
        $today = new \DateTimeImmutable('today', new \DateTimeZone('Asia/Manila'));
        $now = (new \DateTimeImmutable('now', new \DateTimeZone('Asia/Manila')))->format('Y-m-d H:i:s');
        $user = $this->db->table('users')->orderBy('id', 'ASC')->get()->getRowArray();
        $configured = env('demo.password');
        $password = is_string($configured) && $configured !== '' ? $configured : 'Tsa2Demo123!';
        if (strlen($password) < 8 || strlen($password) > 72) {
            throw new \RuntimeException('demo.password must contain 8 to 72 bytes.');
        }
        $this->db->transException(true)->transStart();
        if ($user === null) {
            $this->db->table('users')->insert([
                'username' => 'demo_user', 'full_name' => 'Alex Santos',
                'email' => 'alex.santos@example.com', 'created_at' => $now,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
        } elseif (empty($user['password']) || (is_string($configured) && $configured !== '')) {
            // Do not reset an existing hash unless demo.password explicitly requests it.
            $this->db->table('users')->where('id', $user['id'])
                ->update(['password' => password_hash($password, PASSWORD_DEFAULT)]);
        }
        // Existing records, including archives, are never replaced or duplicated.
        if ($this->db->table('tasks')->countAllResults() === 0) {
            $samples = [
                ['Review project requirements', 'completed', -1],
                ['Design the database schema', 'completed', -1],
                ['Prepare the interface layout', 'completed', -1],
                ['Build the daily task dashboard', 'completed', 0],
                ['Check task filtering by date', 'in progress', 0],
                ['Review the demo user profile', 'pending', 0],
                ['Complete project documentation', 'pending', 0],
                ['Upload source files to GitHub', 'pending', 1],
                ['Verify the hosted application', 'pending', 1],
            ];
            $rows = [];
            foreach ($samples as [$title, $status, $offset]) {
                $rows[] = ['title' => $title, 'status' => $status,
                    'task_date' => $today->modify(sprintf('%+d days', $offset))->format('Y-m-d'),
                    'created_at' => $now, 'is_archived' => 0];
            }
            $this->db->table('tasks')->insertBatch($rows);
        }
        $this->db->transComplete();
        if (! $this->db->transStatus()) {
            throw new \RuntimeException('Seeding failed; the transaction was rolled back.');
        }
    }
}
