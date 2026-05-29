<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Exports\UsersTemplateExport;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:user.index')->only('index');
        $this->middleware('permission:user.create')->only('create', 'store');
        $this->middleware('permission:user.edit')->only('edit', 'update');
        $this->middleware('permission:user.destroy')->only('destroy');
        $this->middleware('permission:user.import')->only('import', 'template');
        $this->middleware('permission:user.export')->only('export');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userName = $request->input('user');
        $user = $request->input('username');

        $query = User::withTrashed()
            ->select(
                'users.id',
                'users.username',
                'users.email',
                'users.name',
                'users.nik',
                'users.role_name',
                'users.is_karyawan',
                'users.deleted_at',
                'users.isverified',
                'users.created_at',
                'users.phone as phone',
                'users.address as address',
                'users.selfie_ktp_path',
                'users.kartu_keluarga_path'
            )
            ->leftJoin('profiles', 'profiles.id_user', '=', 'users.id')
            ->when($request->input('username'), function ($query, $user) {
                return $query->where('users.username', 'like', '%' . $user . '%');
            })
            ->paginate(10);
        $query->appends(['user' => $userName]);

        return view('users.index')->with([
            'users' => $query,
            'userName' => $userName,
            'user' => $user,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // halaman tambah user
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        //simpan data
        User::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'password' => Hash::make($request['password']),
        ]);
        return redirect(route('user.index'))->with('success', 'Data Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //nampilkan detail satu user
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit')
            ->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // Check role hierarchy before allowing edit
        if (!$this->canEditUser($user)) {
            return redirect()->route('user.index')->with('error', 'Anda tidak dapat mengedit user dengan role lebih tinggi');
        }

        //mengupdate data user ke database
        $validate = $request->validated();

        $user->update($validate);
        return redirect()->route('user.index')->with('success', 'User Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Check role hierarchy before allowing delete
        if (!$this->canEditUser($user)) {
            return redirect()->route('user.index')->with('error', 'Anda tidak dapat blacklist user dengan role lebih tinggi');
        }

        $user->forceFill([
            'is_blacklisted' => 1,
        ])->save();

        $user->delete();
        return redirect()->route('user.index')->with('success', 'User masuk blacklist');
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->first();

        // Check role hierarchy before allowing restore
        if (!$this->canEditUser($user)) {
            return redirect()->route('user.index')->with('error', 'Anda tidak dapat mengaktifkan user dengan role lebih tinggi');
        }

        $user->forceFill([
            'is_blacklisted' => 0,
        ])->save();
        $user->restore();
        return redirect()->route('user.index')->with('success', 'User Diaktifkan');
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'import_file' => ['required', 'file', 'mimes:xlsx,xls,csv,ods'],
        ]);

        Excel::import(new UsersImport, $validated['import_file']);

        return redirect()->route('user.index')->with('success', 'Data user berhasil diimport');
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function template()
    {
        return Excel::download(new UsersTemplateExport, 'users_template.xlsx');
    }

    public function approve(Request $request, User $user)
    {
        // Check role hierarchy before allowing approve
        if (!$this->canEditUser($user)) {
            return redirect()->route('user.index')->with('error', 'Anda tidak dapat approve user dengan role lebih tinggi');
        }

        $allowedRoles = $user->is_karyawan
            ? ['super-admin', 'admin_ho', 'admin_branch', 'user']
            : ['user'];

        $validated = $request->validate([
            'role' => ['required', 'in:' . implode(',', $allowedRoles)],
        ]);

        $role = $validated['role'];

        $user->forceFill([
            'isverified' => 1,
            'role_name' => $role,
        ])->save();

        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles([$role]);
        }

        return redirect()->route('user.index')->with('success', 'User berhasil di-approve sebagai ' . str_replace(['-', '_'], ' ', $role));
    }

    /**
     * Check if login user can edit target user based on role hierarchy
     * Role hierarchy: super-admin (0) > admin_ho (1) > admin_branch (2) > user (3)
     * 
     * @param User $targetUser
     * @return bool
     */
    private function canEditUser(User $targetUser): bool
    {
        $roleHierarchy = [
            'super-admin' => 0,
            'admin_ho' => 1,
            'admin_branch' => 2,
            'user' => 3,
        ];

        $loginUserRole = strtolower((string) (auth()->user()->role_name ?? 'user'));
        $targetUserRole = strtolower((string) ($targetUser->role_name ?? 'user'));

        $loginUserLevel = $roleHierarchy[$loginUserRole] ?? 999;
        $targetUserLevel = $roleHierarchy[$targetUserRole] ?? 999;

        // Allow if login user is not admin_branch, OR if target user has equal or lower role
        return $loginUserRole !== 'admin_branch' || $targetUserLevel >= $loginUserLevel;
    }
}
