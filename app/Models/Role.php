<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;

    /**
     * A role may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * A user may belong to multiple roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    /**
     * Assign the given permission to the role.
     *
     * @param  string $permission
     * @return mixed
     */
    public function assignPermission($permission)
    {
        return $this->permissions()->save(
                        Permission::whereName($permission)->firstOrFail()
        );
    }
}
