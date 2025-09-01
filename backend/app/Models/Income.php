<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $fillable = ['description' ,'amount', 'article_id', 'office_id', 'created_at'];

    // Доход принадлежит статье.
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    //Доход принадлежит офису
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

}
