<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_curriculum","view_any_curriculum","create_curriculum","update_curriculum","restore_curriculum","restore_any_curriculum","replicate_curriculum","reorder_curriculum","delete_curriculum","delete_any_curriculum","force_delete_curriculum","force_delete_any_curriculum","view_customer","view_any_customer","create_customer","update_customer","restore_customer","restore_any_customer","replicate_customer","reorder_customer","delete_customer","delete_any_customer","force_delete_customer","force_delete_any_customer","view_delivery","view_any_delivery","create_delivery","update_delivery","restore_delivery","restore_any_delivery","replicate_delivery","reorder_delivery","delete_delivery","delete_any_delivery","force_delete_delivery","force_delete_any_delivery","view_educational::class","view_any_educational::class","create_educational::class","update_educational::class","restore_educational::class","restore_any_educational::class","replicate_educational::class","reorder_educational::class","delete_educational::class","delete_any_educational::class","force_delete_educational::class","force_delete_any_educational::class","view_educational::level","view_any_educational::level","create_educational::level","update_educational::level","restore_educational::level","restore_any_educational::level","replicate_educational::level","reorder_educational::level","delete_educational::level","delete_any_educational::level","force_delete_educational::level","force_delete_any_educational::level","view_educational::subject","view_any_educational::subject","create_educational::subject","update_educational::subject","restore_educational::subject","restore_any_educational::subject","replicate_educational::subject","reorder_educational::subject","delete_educational::subject","delete_any_educational::subject","force_delete_educational::subject","force_delete_any_educational::subject","view_estimation","view_any_estimation","create_estimation","update_estimation","restore_estimation","restore_any_estimation","replicate_estimation","reorder_estimation","delete_estimation","delete_any_estimation","force_delete_estimation","force_delete_any_estimation","view_product","view_any_product","create_product","update_product","restore_product","restore_any_product","replicate_product","reorder_product","delete_product","delete_any_product","force_delete_product","force_delete_any_product","view_publisher","view_any_publisher","create_publisher","update_publisher","restore_publisher","restore_any_publisher","replicate_publisher","reorder_publisher","delete_publisher","delete_any_publisher","force_delete_publisher","force_delete_any_publisher","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_semester","view_any_semester","create_semester","update_semester","restore_semester","restore_any_semester","replicate_semester","reorder_semester","delete_semester","delete_any_semester","force_delete_semester","force_delete_any_semester","view_stock::inbound","view_any_stock::inbound","create_stock::inbound","update_stock::inbound","restore_stock::inbound","restore_any_stock::inbound","replicate_stock::inbound","reorder_stock::inbound","delete_stock::inbound","delete_any_stock::inbound","force_delete_stock::inbound","force_delete_any_stock::inbound","view_supplier","view_any_supplier","create_supplier","update_supplier","restore_supplier","restore_any_supplier","replicate_supplier","reorder_supplier","delete_supplier","delete_any_supplier","force_delete_supplier","force_delete_any_supplier","view_type","view_any_type","create_type","update_type","restore_type","restore_any_type","replicate_type","reorder_type","delete_type","delete_any_type","force_delete_type","force_delete_any_type","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user"]},{"name":"admin_pengiriman","guard_name":"web","permissions":["view_delivery","view_any_delivery","create_delivery","update_delivery","restore_delivery","restore_any_delivery","replicate_delivery","reorder_delivery"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
