<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'amount', 'user_id', 'article_id', 'office_id', 'created_at'];

    /**
     * Расход принадлежит пользователю.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Расход принадлежит статье.
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Расход принадлежит офису.
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
