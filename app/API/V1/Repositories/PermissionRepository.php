<?php

namespace App\API\V1\Repositories;

use App\API\V1\Entities\Permission;
use App\Repositories\Repository;

/** @noinspection LongInheritanceChainInspection */
class PermissionRepository extends Repository
{
	protected /** @noinspection ClassOverridesFieldOfSuperClassInspection */
        $entity = Permission::class;
}