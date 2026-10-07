<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Job extends Model
{
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory;

    public static array $experience = ['entry', 'intermediate', 'senior'];
    public static array $category = ['IT', 'Finance', 'Sales', 'Marketing'];

    public function employer(): BelongsTo {
        return $this->belongsTo(Employer::class);
    }

    public function jobApplications(): HasMany {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeFilter(Builder | QueryBuilder $query, array $filters): Builder|QueryBuilder {
        return $query->when($filters['search'] ?? null, function($query, $search) {
            // closure function in order to add parenthases to the query
            $query->where(function ($query) use($search) {
                $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhereHas('employer', function($query) use ($search) {
                    // orWhereHas is needed for nested relationships
                    $query->where('company_name', 'like', '%' . $search . '%');
                });
            });
        })->when($filters['min_salary'] ?? null, function($query, $minSalary) {
            return $query->where('salary', '>=', $minSalary);
        })->when($filters['max_salary'] ?? null, function($query, $maxSalary) {
            return $query->where('salary', '<=', $maxSalary);
        })->when($filters['experience'] ?? null, function($query, $experience) {
            return $query->where('experience', $experience);
        })->when($filters['category'] ?? null, function($query, $category) {
            return $query->where('category', $category);
        });
    }
}
