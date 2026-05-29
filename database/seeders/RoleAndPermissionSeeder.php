<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::firstOrCreate(['name' => 'dashboard', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'user.management', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'role.permission.management', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'menu.management', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'master.data', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'lelang', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'pdf', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'maps', 'guard_name' => 'web']);

        //user
        Permission::firstOrCreate(['name' => 'user.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'user.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'user.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'user.destroy', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'user.import', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'user.export', 'guard_name' => 'web']);

        //role
        Permission::firstOrCreate(['name' => 'role.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'role.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'role.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'role.destroy', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'role.import', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'role.export', 'guard_name' => 'web']);

        //permission
        Permission::firstOrCreate(['name' => 'permission.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'permission.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'permission.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'permission.destroy', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'permission.import', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'permission.export', 'guard_name' => 'web']);

        //assignpermission
        Permission::firstOrCreate(['name' => 'assign.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'assign.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'assign.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'assign.destroy', 'guard_name' => 'web']);

        //assingusertorole
        Permission::firstOrCreate(['name' => 'assign.user.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'assign.user.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'assign.user.edit', 'guard_name' => 'web']);

        //menu group
        Permission::firstOrCreate(['name' => 'menu-group.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'menu-group.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'menu-group.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'menu-group.destroy', 'guard_name' => 'web']);

        //menu item
        Permission::firstOrCreate(['name' => 'menu-item.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'menu-item.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'menu-item.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'menu-item.destroy', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'tahun.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'tahun.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'tahun.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'tahun.destroy', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'kecamatan.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'kecamatan.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'kecamatan.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'kecamatan.destroy', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'kelurahan.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'kelurahan.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'kelurahan.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'kelurahan.destroy', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'branch.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'branch.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'branch.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'branch.destroy', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'daerah.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'daerah.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'daerah.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'daerah.destroy', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'jabatan.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'jabatan.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'jabatan.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'jabatan.destroy', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'pejabat.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'pejabat.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'pejabat.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'pejabat.destroy', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'opd.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'opd.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'opd.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'opd.destroy', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'daftar.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'daftar.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'daftar.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'daftar.destroy', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'tkd.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'tkd.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'tkd.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'tkd.destroy', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'penawaran.index', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'penawaran.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'penawaran.edit', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'penawaran.destroy', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'pemenang.index', 'guard_name' => 'web']);
        // Permission::create(['name' => 'pemenang.create']);
        // Permission::create(['name' => 'pemenang.edit']);
        // Permission::create(['name' => 'pemenang.destroy']);

        Permission::firstOrCreate(['name' => 'rekap-sts.index', 'guard_name' => 'web']);
        // Permission::create(['name' => 'rekap-sts.create']);
        // Permission::create(['name' => 'rekap-sts.edit']);
        // Permission::create(['name' => 'rekap-sts.destroy']);

        Permission::firstOrCreate(['name' => 'gugur.index', 'guard_name' => 'web']);
        // Permission::create(['name' => 'gugur.create']);
        // Permission::create(['name' => 'gugur.edit']);
        // Permission::create(['name' => 'gugur.destroy']);

        Permission::firstOrCreate(['name' => 'detail.index', 'guard_name' => 'web']);

        // create roles
        $roleUser = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $roleUser->givePermissionTo([
            'dashboard',
            'master.data',
            'lelang',
            'pdf',
            'maps',
            'daftar.index',
            'daftar.create',
            'daftar.edit',
            'daftar.destroy',
            'tkd.index',
            'tkd.create',
            'tkd.edit',
            'tkd.destroy',
            'penawaran.index',
            'penawaran.create',
            'penawaran.edit',
            'penawaran.destroy',
            'daerah.index',
            'daerah.create',
            'daerah.edit',
            'daerah.destroy',
            'pemenang.index',
            'rekap-sts.index',
            'gugur.index',
            'detail.index',
        ]);

        // create Super Admin
        $role = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $role->givePermissionTo(Permission::all());

        // create admin_ho
        $roleAdminHO = Role::firstOrCreate(['name' => 'admin_ho', 'guard_name' => 'web']);
        $roleAdminHO->givePermissionTo([
            'dashboard',
            'master.data',
            'lelang',
            'pdf',
            'daftar.index',
            'daftar.create',
            'daftar.edit',
            'daftar.destroy',
            'tkd.index',
            'tkd.create',
            'tkd.edit',
            'tkd.destroy',
            'penawaran.index',
            'penawaran.create',
            'penawaran.edit',
            'penawaran.destroy',
            'pemenang.index',
            'rekap-sts.index',
            'gugur.index',
            'detail.index',
        ]);

        // create admin_branch
        $roleAdminBranch = Role::firstOrCreate(['name' => 'admin_branch', 'guard_name' => 'web']);
        $roleAdminBranch->givePermissionTo([
            'dashboard',
            'master.data',
            'lelang',
            'pdf',
            'daftar.index',
            'daftar.create',
            'daftar.edit',
            'daftar.destroy',
            'tkd.index',
            'tkd.create',
            'tkd.edit',
            'tkd.destroy',
            'penawaran.index',
            'penawaran.create',
            'penawaran.edit',
            'penawaran.destroy',
            'pemenang.index',
            'rekap-sts.index',
            'gugur.index',
            'detail.index',
        ]);

        //assign user id 1 ke super admin
        $user = User::find(1);
        $user->assignRole('super-admin');
        $user = User::find(2);
        $user->assignRole('user');
    }
}
