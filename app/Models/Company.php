<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * class Company
 *
 * @package App\Model
 *
 * @property string $title
 * @property string $phone
 * @property string $description
 * @property int $user_id
 *
 * @property Collection<User> $userRelation
 */
class Company extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'phone',
        'description',
        'user_id'
    ];

    /**
     * @var string
     */
    protected $table = 'companies';

    /**
     * @return BelongsTo
     */
    public function userRelation(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return CompanyFactory::new();
    }
}
