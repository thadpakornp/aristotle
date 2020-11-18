<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Role Types
         *
         */
        $RoleItems = [
            [
                'name'        => 'SuperUser',
                'slug'        => 'super.user',
                'description' => 'ผู้ดูแลระบบ สามารถดำเนินการจัดการได้ทุกอย่างในระบบ',
                'level'       => 0,
            ],
            [
                'name'        => 'StoreAdmin',
                'slug'        => 'store.admin',
                'description' => 'ผู้ใช้งานที่ได้รับสิทธิ์การใช้งานที่สูงกว่าผู้ใช้งานทั่วไปแต่จะถูกจำกัดไว้เฉพาะไซต์งานของตนเองเท่านั้น',
                'level'       => 1,
            ],
            [
                'name'        => 'User',
                'slug'        => 'user',
                'description' => 'ผู้ใช้งานทั่วไป',
                'level'       => 2,
            ],
        ];

        /*
         * Add Role Items
         *
         */
        foreach ($RoleItems as $RoleItem) {
            $newRoleItem = config('roles.models.role')::where('slug', '=', $RoleItem['slug'])->first();
            if ($newRoleItem === null) {
                $newRoleItem = config('roles.models.role')::create([
                    'name'          => $RoleItem['name'],
                    'slug'          => $RoleItem['slug'],
                    'description'   => $RoleItem['description'],
                    'level'         => $RoleItem['level'],
                ]);
            }
        }
    }
}
