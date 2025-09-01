<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['name'];

    // Доходы по статье
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    // Расходы по статье
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

}
