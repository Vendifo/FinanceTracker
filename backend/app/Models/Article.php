<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель Article
 *
 * Представляет категорию статьи (статью) для доходов и расходов.
 * Каждая статья может иметь множество доходов и расходов.
 */
class Article extends Model
{
    /**
     * Разрешённые для массового заполнения поля.
     * Позволяет использовать методы create() и update() безопасно.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Связь "один ко многим" с моделью Income
     *
     * Каждая статья может иметь несколько доходов.
     *
     * @return HasMany
     */
    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class); // связь через foreign key article_id
    }

    /**
     * Связь "один ко многим" с моделью Expense
     *
     * Каждая статья может иметь несколько расходов.
     *
     * @return HasMany
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class); // связь через foreign key article_id
    }
}
