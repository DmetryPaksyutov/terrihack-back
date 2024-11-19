<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * @package App\Models
 *
 * @property string $id
 * @property int $user_id Id пользователя
 * @property string $type Тип соц. сети
 * @property string $account_id Id аккаунта
 * @property ?string $profile_image Ссылка на аватар
 * @property ?array $data
 * @property ?Carbon $created_at Дата создания
 * @property ?Carbon $updated_at Дата коследнего обновления
 *
 * @property User $user пользователь
 */

class UserAccount extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $keyType = 'string';

    public $incrementing = false;

    public const TYPE_TELEGRAM = 'telegram';

    public const TYPES = [
        self::TYPE_TELEGRAM,
    ];

    protected $fillable = [
        'user_id',
        'type',
        'account_id',
        'profile_image',
        'data',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
