<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\Borrow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DebugBorrowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or Find Admin (to be approver)
        $admin = User::where('role', 'admin')->first() ?? User::create([
            'name' => 'Admin Library',
            'email' => 'admin@debug.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'id_pengenal_siswa' => 'ADM001',
            'pin' => '123456',
        ]);

        // 2. Create Regular Users with unique IDs for this run
        $runId = Str::random(4);
        $users = collect();
        for ($i = 1; $i <= 3; $i++) {
            $users->push(User::create([
                'name' => "User Debug $i ($runId)",
                'role' => 'user',
                'id_pengenal_siswa' => "DEBUG-$runId-$i",
                'pin' => '123456',
                'kelas' => 'XII',
                'jurusan' => 'RPL',
                'angkatan' => '2023',
            ]));
        }

        // 3. Create Books and Items
        $books = collect(['Bumi', 'Laskar Pelangi', 'Filosofi Teras', 'Rich Dad Poor Dad'])->map(fn($title) => 
            Book::create([
                'title' => "$title ($runId)",
                'author' => 'Author ' . Str::random(5),
                'publisher' => 'Publisher ' . Str::random(5),
                'year' => rand(2010, 2024),
            ])
        );

        $items = collect();
        foreach ($books as $book) {
            for ($i = 1; $i <= 3; $i++) {
                $items->push(BookItem::create([
                    'book_id' => $book->id,
                    'code' => $book->id . '-' . $runId . '-' . rand(100, 999),
                    'status' => 'available',
                ]));
            }
        }

        // 4. Create Borrows for different months
        $months = [
            'Current Month' => now(),
            'Last Month' => now()->subMonth(),
            '2 Months Ago' => now()->subMonths(2),
        ];

        // 4. Create Borrows for different months
        $months = [
            '2 Months Ago' => now()->subMonths(2),
            'Last Month' => now()->subMonth(),
            'Current Month' => now(),
        ];

        // Flatten all items and shuffle them to ensure variety without duplication
        $availableItems = $items->shuffle();

        foreach ($months as $label => $date) {
            foreach ($users as $user) {
                // If we ran out of items, just break (we have 12 items and 9 iteration slots)
                if ($availableItems->isEmpty()) break;

                // Take one item and remove it from pool for this seeder run to be safe
                $item = $availableItems->pop();
                
                // Set borrowing dates within the target month
                $borrowDate = $date->copy()->startOfMonth()->addDays(rand(1, 25));
                $dueDate = $borrowDate->copy()->addDays(7);
                
                // Random status (shifted to prefer non-pending for older months)
                $statusType = ($label === 'Current Month') ? rand(1, 3) : rand(1, 2);
                
                if ($statusType === 1) { // Returned
                    $returnDate = $borrowDate->copy()->addDays(rand(1, 6));
                    Borrow::create([
                        'user_id' => $user->id,
                        'book_item_id' => $item->id,
                        'borrow_date' => $borrowDate,
                        'due_date' => $dueDate,
                        'return_date' => $returnDate,
                        'status' => 'returned',
                        'approved_by' => $admin->id,
                        'created_at' => $borrowDate,
                    ]);
                    $item->update(['status' => 'available']);
                } elseif ($statusType === 2) { // Approved (Borrowed)
                    $item->update(['status' => 'borrowed']);
                    Borrow::create([
                        'user_id' => $user->id,
                        'book_item_id' => $item->id,
                        'borrow_date' => $borrowDate,
                        'due_date' => $dueDate,
                        'status' => 'approved',
                        'approved_by' => $admin->id,
                        'created_at' => $borrowDate,
                    ]);
                } else { // Pending
                    Borrow::create([
                        'user_id' => $user->id,
                        'book_item_id' => $item->id,
                        'borrow_date' => $borrowDate,
                        'due_date' => $dueDate,
                        'status' => 'pending',
                        'created_at' => $borrowDate,
                    ]);
                }
            }
        }

        $this->command->info('Successfully seeded debug borrow data across several months!');
    }
}
