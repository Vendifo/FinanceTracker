<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = ['name'];

    // Доходы офиса
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    // Расходы офиса
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
