<?php

namespace App\Models;

use App\Pivots\ProjectAsigneePivot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        "code",
        "full_name",
        "client_name",
        "client_email",
    ];

    public function asignees(){
        return $this->belongsToMany(User::class, 'project_asignees', 'project_id', 'user_id')
                    ->using(ProjectAsigneePivot::class)
                    ->withPivot(['assigned_at', 'is_active', 'id', 'added_by_id']);
    }
}
