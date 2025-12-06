<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $permissions = [
      // Dashboard
      'view_dashboard',

      // User management
      'manage_users',
      'manage_roles',
      'manage_permissions',

      // Account management
      'view_accounts',
      'create_accounts',
      'update_accounts',
      'delete_accounts',
      'restore_accounts',
      'force_delete_accounts',
    ];

    // Create permissions if not exists
    foreach ($permissions as $permission) {
      Permission::firstOrCreate(['name' => $permission]);
    }

    // Create roles
    $admin = Role::firstOrCreate(['name' => 'admin']);
    $user = Role::firstOrCreate(['name' => 'user']);

    // Admin gets ALL permissions
    $admin->syncPermissions($permissions);

    // User role gets ONLY safe/basic permissions
    // (Ownership rules in policies handle access)
    $user->syncPermissions([
      'view_dashboard',
      'view_accounts',
      'create_accounts',
    ]);
  }
}
