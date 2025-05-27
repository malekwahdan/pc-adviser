<?php


namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        $adminsQuery = Admin::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $adminsQuery->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $admins = $adminsQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.admins.index', compact('admins'));
    }


    public function create()
    {
        return view('admin.admins.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,super_admin',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $admin = Admin::create($validated);

        return redirect()->route('admins.index')
            ->with('success', 'Admin created successfully.');
    }


    public function show(Admin $admin)
    {
        return view('admin.admins.show', compact('admin'));
    }


    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }


    public function update(Request $request, Admin $admin)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins')->ignore($admin->id),
            ],
            'role' => 'required|in:admin,super_admin',
        ];


        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validated = $request->validate($rules);


        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        return redirect()->route('admins.show', $admin)
            ->with('success', 'Admin updated successfully.');
    }


    public function destroy(Admin $admin)
    {
        $admin->delete();

        return redirect()->route('admins.index')
            ->with('success', 'Admin deleted successfully.');
    }


  
}
