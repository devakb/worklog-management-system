<?php

namespace App\Pivots;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProjectAsigneePivot extends Pivot
{

    public function addedBy(){
        return $this->belongsTo(User::class, 'added_by_id');
    }

}
