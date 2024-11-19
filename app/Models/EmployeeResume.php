<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $resume_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $patronymic
 * @property string|null $sex
 * @property int|null $age
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $linkedin
 * @property string|null $telegram
 * @property string|null $city
 * @property string|null $country
 * @property string|null $citizenship
 * @property string|null $position
 * @property string|null $expected_salary
 * @property string|null $expected_salary_currency
 * @property array|null $education
 * @property array|null $work_experience
 * @property array|null $skills
 * @property array|null $languages
 * @property array|null $personal_qualities
 * @property int|null $work_experience_in_months
 * @property boolean|null $is_work_experience_continuous
 * @property \Illuminate\Support\Carbon|null $resume_updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class EmployeeResume extends Model
{
    use HasFactory, HasUuids;

    public const TABLE = 'employee_resumes';
    protected $table = self::TABLE;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'resume_id',
        'first_name',
        'last_name',
        'patronymic',
        'sex',
        'age',
        'date_of_birth',
        'phone',
        'email',
        'linkedin',
        'telegram',
        'education',
        'work_experience',
        'skills',
        'languages',
        'personal_qualities',
        'city',
        'country',
        'citizenship',
        'position',
        'expected_salary',
        'expected_salary_currency',
        'resume_updated_at',
        'work_experience_in_months',
        'is_work_experience_continuous',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'resume_updated_at' => 'date',
        'education' => 'array',
        'work_experience' => 'array',
        'skills' => 'array',
        'languages' => 'array',
        'personal_qualities' => 'array',
        'is_work_experience_continuous' => 'boolean',
    ];

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class, 'resume_id');
    }

    public function getNameAttribute(): string
    {
        return "{$this->last_name} {$this->first_name}";
    }
}
