<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KelolaRoleController extends Controller
{
    protected $roles = [
        ['id' => 1, 'name' => 'Admin Keuangan', 'key' => 'admin', 'description' => 'Memiliki akses penuh ke sistem'],
        ['id' => 2, 'name' => 'Staff Keuangan', 'key' => 'staff', 'description' => 'Dapat mengelola pembayaran dan tagihan'],
        ['id' => 3, 'name' => 'Mahasiswa', 'key' => 'mahasiswa', 'description' => 'Dapat melihat tagihan dan melakukan pembayaran']
    ];

    public function index()
    {
        // Check admin access
        if (Session::get('role') !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        $roles = $this->roles;

        return view('admin.dashboard.kelola-role.kelola-role', compact('roles'));
    }

    public function create()
    {
        // Check admin access
        if (Session::get('role') !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        return view('admin.dashboard.kelola-role.kelola-role-create');
    }

    public function store(Request $request)
    {
        // Check admin access
        if (Session::get('role') !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:50|unique:roles,key',
            'description' => 'nullable|string|max:500'
        ]);

        // Implement store logic here
        // Since we're using a static array, this is just a placeholder

        return redirect()->route('admin.kelola-role')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Check admin access
        if (Session::get('role') !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        $role = collect($this->roles)->firstWhere('id', $id);

        if (!$role) {
            return redirect()->route('admin.kelola-role')->withErrors(['error' => 'Role tidak ditemukan.']);
        }

        return view('admin.dashboard.kelola-role.kelola-role-edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        // Check admin access
        if (Session::get('role') !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        // Implement update logic here
        // Since we're using a static array, this is just a placeholder

        return redirect()->route('admin.kelola-role')
            ->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Check admin access
        if (Session::get('role') !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
        }

        // Implement delete logic here
        // Since we're using a static array, this is just a placeholder

        return redirect()->route('admin.kelola-role')
            ->with('success', 'Role berhasil dihapus.');
    }
}