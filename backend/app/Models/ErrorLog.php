<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    use HasFactory;
    public $table = 'error_logs';
    protected $fillable = ['user_id', 'file', 'error_summary', 'log_trace'];
}
