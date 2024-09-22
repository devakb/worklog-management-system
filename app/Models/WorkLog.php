<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'date_of_work',
        'work_duration_in_minutes',
        'work_description',
    ];

    protected function casts(): array
    {
        return [
            'date_of_work' => 'date',
        ];
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getWorkDurationFormattedAttribute() {
        $totalMinutes = $this->work_duration_in_minutes;

        return $this->calculateWorkDuration($totalMinutes);
    }

    public function calculateWorkDuration($totalMinutes){

        $hours = intdiv($totalMinutes, 60); // Calculate hours
        $minutes = $totalMinutes % 60;      // Calculate remaining minutes

        if($hours <= 0){
            return sprintf('%dm', $minutes);
        }

        return sprintf('%dh %dm', $hours, $minutes);
    }

}
