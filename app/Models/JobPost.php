<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPost  extends Model
{
    protected $table = 'job_posts';
    
    const REJECTED = 0;
    const ACTIVE = 1;
    const PENDING = 2;

    protected $fillable = [
        'title',
        'description',
        'company',
        'location',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function savedJob()
    {
        return $this->belongsTo(related: SavedJob::class);
    }
}
