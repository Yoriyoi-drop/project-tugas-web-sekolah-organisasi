<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PPDB extends Model
{
    /**
     * The table associated with the model.
     * Eloquent would otherwise try to use the plural snake_case (p_p_d_b_s).
     *
     * @var string
     */
    protected $table = 'ppdb';

    /**
     * Mass assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'nik',
        'email',
        'birth_date',
        'birth_place',
        'gender',
        'address',
        'phone',
        'parent_name',
        'parent_phone',
        'previous_school',
        'desired_major',
        'status'
    ];
}
