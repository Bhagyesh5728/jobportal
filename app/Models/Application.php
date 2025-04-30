<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'applications';

    const REJECTED = 0;
    const ACTIVE = 1;
    const PENDING = 2;

    protected $fillable = [
        'user_id',
        'job_post_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobPost()
    {
        return $this->belongsTo(\App\Models\JobPost::class);
    }


}
