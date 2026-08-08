<?php

namespace Database\Seeders;

use App\Tool;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission_create = Permission::create(['name' => 'create']);
        $permission_read = Permission::create(['name' => 'read']);
        $permission_update = Permission::create(['name' => 'update']);
        $permission_delete = Permission::create(['name' => 'delete']);

        $role_admin = Role::create(['name' => 'admin']);
        $role_customer = Role::create(['name' => 'customer']);
        $role_guest = Role::create(['name' => 'guest']);
        $role_recruiter = Role::create(['name' => 'recruiter']);

        $role_admin->givePermissionTo($permission_create);
        $role_admin->givePermissionTo($permission_read);
        $role_admin->givePermissionTo($permission_update);
        $role_admin->givePermissionTo($permission_delete);

        $role_customer->givePermissionTo($permission_read);
        $role_customer->givePermissionTo($permission_create);

        $role_guest->givePermissionTo($permission_read);

        $role_recruiter->givePermissionTo($permission_create);
        $role_recruiter->givePermissionTo($permission_read);
        $role_recruiter->givePermissionTo($permission_update);
        $role_recruiter->givePermissionTo($permission_delete);

        // libe - admin
        $libe = User::firstOrCreate(
            ['email' => 'luis@libe.dev'],
            ['name' => 'Libe', 'password' => Hash::make('password')]
        );
        if (!$libe->hasRole('admin')) {
            $libe->assignRole('admin');
        }

        // mrlibelula - guest
        $mrlibelula = User::firstOrCreate(
            ['email' => 'mrlibelula2@gmail.com'],
            ['name' => 'Luis', 'password' => Hash::make('password')]
        );
        if (!$mrlibelula->hasRole('guest')) {
            $mrlibelula->assignRole('guest');
        }

        // timo - recruiter
        $timo = User::firstOrCreate(
            ['email' => 'timo@libe.dev'],
            ['name' => 'Timo', 'password' => Hash::make('password')]
        );
        if (!$timo->hasRole('recruiter')) {
            $timo->assignRole('recruiter');
        }

        
    }
}
