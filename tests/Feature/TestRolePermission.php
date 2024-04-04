<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\PermissionCategory;
use App\Models\Role;
use App\Models\User;
use Spatie\Permission\Exceptions\RoleAlreadyExists;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class TestRolePermission extends TestCase
{
    public function testCreateRole()
    {
        $role = Role::create(['name' => 'writer']);
        self::assertNotNull($role);

    }

    public function testRoleAlreadyExist()
    {
        $this->expectException(RoleAlreadyExists::class);
        $role = Role::create(['name' => 'writer']);

    }

    public function testCreatePermissionCategory()
    {

        $result = PermissionCategory::create([
            'name' => fake()->name
        ]);

        assertNotNull($result);
    }

    public function testCreatePermission()
    {

        $category = PermissionCategory::first();

        assertNotNull($category);

        $permision = Permission::create([
            'name' => 'create_warehouse',
            'permission_categories_id' => $category->id
        ]);
        assertNotNull($permision);

    }

    public function testGivePermission()
    {

        $role = Role::first();

        $role->givePermissionTo('create_warehouse');
        assertNotNull($role);

    }


    public function testUserGiveRole()
    {

        $user = User::first();
        $role = Role::first();

        $user->assignRole($role);
        assertNotNull($role);

    }


}
