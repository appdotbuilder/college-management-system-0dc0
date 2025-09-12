<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\FeeType
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float|null $default_amount
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InvoiceItem> $invoiceItems
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|FeeType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeeType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeeType query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeeType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeeType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeeType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeeType whereDefaultAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeeType whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeeType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeeType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeeType active()
 * @method static \Database\Factories\FeeTypeFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class FeeType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'default_amount',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'default_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the invoice items for this fee type.
     */
    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Scope a query to only include active fee types.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}