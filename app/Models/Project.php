<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;


    protected $fillable = [
        "name",
    ];

    /**
     * Get Tasks associated with this Project
     *
     * @return Task | null
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
