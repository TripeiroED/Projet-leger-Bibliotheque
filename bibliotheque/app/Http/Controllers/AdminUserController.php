<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(){ $users = User::all(); return view('admin.users.index', compact('users')); }
    public function create(){ return view('admin.users.create'); }
    public function store(Request $r){ User::create($r->only('name','email','role') + ['password'=>Hash::make($r->password)]); return redirect()->route('users.index'); }
    public function edit(User $user){ return view('admin.users.edit', compact('user')); }
    public function update(Request $r, User $user){ $user->update($r->only('name','email','role') + ($r->password ? ['password'=>Hash::make($r->password)] : [])); return redirect()->route('users.index'); }
    public function destroy(User $user){ $user->delete(); return redirect()->route('users.index'); }
}
