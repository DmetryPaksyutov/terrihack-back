<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class Resume
 * @package App\Models
 *
 * @property string $id
 * @property string $pdf_path Путь к pdf
 * @property string $name название файла
 * @property ?string $txt_path Путь к txt
 * @property string $status Состояние
 * @property ?string $status_text Статус текста
 * @property string $hash хэш файла
 * @property Carbon $created_at Дата создания
 * @property Carbon $updated_at Дата коследнего обновления
 */
class Resume extends Model
{
    use HasFactory, HasApiTokens;

    protected $keyType = 'string';

    public $incrementing = false;

    public const STATUS_LOADED = 'loaded';
    public const STATUS_ERROR = 'error';
    public const STATUS_IN_TEXT = 'in_text';
    public const STATUS_IN_DATABASE = 'in_database';
    public const STATUS_BASE_DATA_PARSED = 'base_data_parsed';
    public const STATUS_AI_DATA_PARSED = 'AI_data_parsed';
    public const STATUS_PARSED = 'parsed';

    public const STATUSES = [
        self::STATUS_LOADED,
        self::STATUS_ERROR,
        self::STATUS_IN_TEXT,
        self::STATUS_IN_DATABASE,
        self::STATUS_BASE_DATA_PARSED,
        self::STATUS_AI_DATA_PARSED,
        self::STATUS_PARSED,
    ];

    protected $fillable = [
        'pdf_path',
        'txt_path',
        'status',
        'status_text',
    ];
}
