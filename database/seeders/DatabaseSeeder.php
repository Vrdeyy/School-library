<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\BookItem;
use App\Models\Borrow;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ADMIN
        $admin = User::factory()->create([
            'name' => 'Admin Perpus',
            'email' => 'admin@perpus.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'nis' => 'ADM-001',
        ]);

        // 2. USERS (25 Users + 2 Demo Users)
        $demoUser = User::factory()->create([
            'name' => 'Idham Pratama',
            'email' => 'user@perpus.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'nis' => 'USR001',
            'pin' => '123456',
        ]);

        User::factory()->count(24)->create();

        // 3. BOOKS (100 Books, each with 1-5 items)
        $books = Book::factory()->count(100)->create()->each(function ($book) {
            $qty = rand(1, 5);
            for ($i = 1; $i <= $qty; $i++) {
                $item = $book->items()->create([
                    'code' => $book->id . '-' . now()->format('ymd') . '-' . strtoupper(Str::random(5)),
                    'status' => 'available',
                ]);
                $item->generateQrSignature(); // Generate QR signature
            }
        });

        // 4. BORROW HISTORY (Dummy Logs)
        $users = User::where('role', 'user')->get();
        $allItems = BookItem::all();

        // Create 50 random history logs
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $item = $allItems->random();

            // Random status: returned, approved (active), pending
            $status = collect(['returned', 'approved', 'pending'])->random();
            
            // Dates logic
            $borrowDate = now()->subDays(rand(1, 60));
            $dueDate = $borrowDate->copy()->addDays(7);
            
            if ($status === 'returned') {
                $returnDate = $borrowDate->copy()->addDays(rand(3, 10));
                
                Borrow::create([
                    'user_id' => $user->id,
                    'book_item_id' => $item->id,
                    'borrow_date' => $borrowDate,
                    'due_date' => $dueDate,
                    'return_date' => $returnDate,
                    'status' => 'returned',
                    'approved_by' => $admin->id,
                ]);
            } elseif ($status === 'approved') {
                // Ensure item is available before marking as borrowed/approved
                // In dummy data, we might have multiple active borrows for same item if not careful.
                // For simplicity, we just create log. If active, we mark item as borrowed.
                
                // Only mark as borrowed if item is currently available to avoid conflict in seeder logic
                if ($item->status === 'available') {
                    Borrow::create([
                        'user_id' => $user->id,
                        'book_item_id' => $item->id,
                        'borrow_date' => $borrowDate,
                        'due_date' => $dueDate,
                        'status' => 'approved',
                        'approved_by' => $admin->id,
                    ]);
                    $item->update(['status' => 'borrowed']);
                }
            } else { // Pending
                if ($item->status === 'available') {
                    Borrow::create([
                        'user_id' => $user->id,
                        'book_item_id' => $item->id,
                        'status' => 'pending',
                        'borrow_date' => now(), // Pending usually just created
                        'due_date' => now()->addDays(7),
                    ]);
                }
            }
        }
    }
}