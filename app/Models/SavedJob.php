<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedJob extends Model
{
    protected $table = 'saved_jobs';

    protected $fillable = [
        'job_post_id',
        'user_id',
    ];



    public function job()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }
    
}
