<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookItem;
use App\Models\User;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function userCard(User $user)
    {
        return view('print.user-card', compact('user'));
    }

    public function bookLabel(BookItem $item)
    {
        return view('print.book-label', compact('item'));
    }

    public function bulkUsers(Request $request)
    {
        $ids = $request->ids;
        if (is_string($ids)) $ids = explode(',', $ids); // Handle simple GET query if needed
        
        $users = User::whereIn('id', $ids)->get();
        return view('print.bulk-users', compact('users'));
    }

    public function bulkBooks(Request $request)
    {
        $ids = $request->ids;
        if (is_string($ids)) $ids = explode(',', $ids);

        $items = BookItem::with('book')->whereIn('id', $ids)->get();
        return view('print.bulk-books', compact('items'));
    }
}
