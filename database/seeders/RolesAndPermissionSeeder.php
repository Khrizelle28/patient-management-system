<?php

namespace Database\Seeders;

use App\Classes\UserRoles;
use App\Classes\UserStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use ReflectionClass;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('role_has_permissions')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminRole = Role::create(['name' => 'Administrator']);
         // Create roles and permissions
         $roles       = (new ReflectionClass(UserRoles::class))->getConstants() ?? [];

        foreach($roles as $roleName){
            $role = Role::where('name', $roleName)->first();
            if(empty($role)){
                $role = Role::create([
                    'name' => $roleName,
                ]);
            }
        }

        $adminUser = User::updateOrCreate(['email' => "sablankhrizelle@gmail.com"],[
            'first_name'        => "Khrizelle",
            'middle_name'       => "Primero",
            'last_name'         => "Sablan",
            'username'          => "administrator",
            'email'             => "sablankhrizelle@gmail.com",
            'password'          => Hash::make('Secret123'),
            'status'            => UserStatus::ACTIVATED,
            'email_verified_at' => Carbon::now(),
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        $adminUser->assignRole('Administrator');
    }
}
