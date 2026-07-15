<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::orderBy('hierarchy_level')->orderBy('created_at')->get();
        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        return view('admin.members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|in:' . implode(',', array_keys(Member::$roles)),
            'division' => 'nullable|string|in:' . implode(',', Member::$divisions),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if (in_array($validated['role'], ['Kadiv', 'Anggota']) && empty($validated['division'])) {
            return back()->withInput()->withErrors(['division' => 'Divisi harus diisi untuk peran Kadiv atau Anggota.']);
        }

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('members', 'public');
        }

        Member::create($validated);

        return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Member $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|in:' . implode(',', array_keys(Member::$roles)),
            'division' => 'nullable|string|in:' . implode(',', Member::$divisions),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if (in_array($validated['role'], ['Kadiv', 'Anggota']) && empty($validated['division'])) {
            return back()->withInput()->withErrors(['division' => 'Divisi harus diisi untuk peran Kadiv atau Anggota.']);
        }

        if ($request->hasFile('image')) {
            if ($member->image_path) {
                Storage::disk('public')->delete($member->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('members', 'public');
        }

        $member->update($validated);

        return redirect()->route('admin.members.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Member $member)
    {
        if ($member->image_path) {
            Storage::disk('public')->delete($member->image_path);
        }
        $member->delete();

        return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
