<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth-login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // First check if the email belongs to an admin user
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !$user->isAdmin()) {
            return back()->withErrors([
                'email' => 'Access denied. Only admin accounts can access this system.',
            ])->withInput($request->only('email'));
        }

        // Now attempt authentication only for admin users
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Show user management page
     */
    public function users(Request $request)
    {
        $users = User::orderBy('created_at', 'desc')->paginate(50);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'users' => $users
            ]);
        }

        return view('users', compact('users'));
    }

    /**
     * Show only website customers (non-admin users) — read-only
     */
    public function customers(Request $request)
    {
        $customers = User::where('user_type', 'user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('customers', compact('customers'));
    }

    /**
     * Show user details (read-only)
     */
    public function showUser(User $user)
    {
        return view('user-show', compact('user'));
    }

    /**
     * Show create user form
     */
    public function showCreateUserForm()
    {
        return view('create-user');
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
        ]);

        return redirect()->route('users')->with('success', 'User created successfully.');
    }

    /**
     * Show edit user form
     */
    public function showEditUserForm(User $user)
    {
        return view('edit-user', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'user_type' => 'required|in:admin,user',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'user_type' => $request->user_type,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('users')->with('success', 'User updated successfully.');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        // Prevent user from deleting themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('users')->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user status
     */
    public function toggleUserStatus(User $user)
    {
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';

        $user->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'message' => "User {$statusText} successfully.",
            'status' => $newStatus,
            'badge_class' => $newStatus === 'active' ? 'success' : 'danger',
            'badge_text' => ucfirst($newStatus)
        ]);
    }
}
