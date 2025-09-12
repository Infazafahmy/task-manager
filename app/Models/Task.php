<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    } 

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'priority',
        'user_id',         
    ];

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'assigned_user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function postpones()
    {
        return $this->hasMany(TaskPostpone::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc'); // chronological
    }



}