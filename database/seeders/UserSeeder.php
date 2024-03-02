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
        $libe = User::firstOrCreate([
            'name' => 'Libe', 
            'email' => 'luis@libe.dev', 
            'password' => Hash::make('password'), 
        ]);

        $libe->assignRole('admin');
        // $libe->profile_photo_path = '/images/libe.jpg';
        $libe->save();

        // mrlibelula - guest
        $mrlibelula = User::firstOrCreate([
            'name' => 'Luis', 
            'email' => 'mrlibelula2@gmail.com', 
            'password' => Hash::make('password'), 
        ]);

        $mrlibelula->assignRole('guest');
        $mrlibelula->save();

        // timo - recruiter
        $timo = User::firstOrCreate([
            'name' => 'Timio', 
            'email' => 'timo@libe.dev', 
            'password' => Hash::make('password'), 
        ]);

        $timo->assignRole('recruiter');
        $timo->save();

        
    }
}
