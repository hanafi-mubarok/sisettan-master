<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:branch.index')->only('index');
        $this->middleware('permission:branch.create')->only('create', 'store');
        $this->middleware('permission:branch.edit')->only('edit', 'update');
        $this->middleware('permission:branch.destroy')->only('destroy');
    }

    public function index(Request $request)
    {
        $branches = DB::table('branch')
            ->when($request->input('branch'), function ($query, $branch) {
                return $query->where('branch', 'like', '%' . $branch . '%');
            })
            ->whereNull('branch.deleted_at')
            ->paginate(10);

        return view('master data.branch.index', compact('branches'));
    }

    public function create()
    {
        return view('master data.branch.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch' => 'required|string|max:255|unique:branch,branch',
        ]);

        Branch::create([
            'branch' => $request->branch,
        ]);

        return redirect()->route('branch.index')->with('success', 'Tambah Data Branch Sukses');
    }

    public function show(Branch $branch)
    {
        return view('master data.branch.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        return view('master data.branch.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'branch' => 'required|string|max:255|unique:branch,branch,' . $branch->id,
        ]);

        $branch->update($request->all());

        return redirect()->route('branch.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('branch.index')->with('success', 'Branch deleted successfully.');
    }
}
