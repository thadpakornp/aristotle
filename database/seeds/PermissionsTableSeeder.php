<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Permission Types
         *
         */
        $Permissionitems = [
            [
                'name'        => 'Can View Users',
                'slug'        => 'view.users',
                'description' => 'Can view users',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Create Users',
                'slug'        => 'create.users',
                'description' => 'Can create new users',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Edit Users',
                'slug'        => 'edit.users',
                'description' => 'Can edit users',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Delete Users',
                'slug'        => 'delete.users',
                'description' => 'Can delete users',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can View Store',
                'slug'        => 'view.store',
                'description' => 'Can view store',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Create Store',
                'slug'        => 'create.store',
                'description' => 'Can create new store',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Edit Store',
                'slug'        => 'edit.store',
                'description' => 'Can edit store',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Delete Store',
                'slug'        => 'delete.store',
                'description' => 'Can delete store',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can View Course',
                'slug'        => 'view.course',
                'description' => 'Can view course',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Create Course',
                'slug'        => 'create.course',
                'description' => 'Can create new course',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Edit Course',
                'slug'        => 'edit.course',
                'description' => 'Can edit course',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Delete Course',
                'slug'        => 'delete.course',
                'description' => 'Can delete course',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can View Post',
                'slug'        => 'view.post',
                'description' => 'Can view post',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Create Post',
                'slug'        => 'create.post',
                'description' => 'Can create new post',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Edit Post',
                'slug'        => 'edit.post',
                'description' => 'Can edit post',
                'model'       => 'Permission',
            ],
            [
                'name'        => 'Can Delete Post',
                'slug'        => 'delete.post',
                'description' => 'Can delete post',
                'model'       => 'Permission',
            ],
        ];

        /*
         * Add Permission Items
         *
         */
        foreach ($Permissionitems as $Permissionitem) {
            $newPermissionitem = config('roles.models.permission')::where('slug', '=', $Permissionitem['slug'])->first();
            if ($newPermissionitem === null) {
                $newPermissionitem = config('roles.models.permission')::create([
                    'name'          => $Permissionitem['name'],
                    'slug'          => $Permissionitem['slug'],
                    'description'   => $Permissionitem['description'],
                    'model'         => $Permissionitem['model'],
                ]);
            }
        }
    }
}
