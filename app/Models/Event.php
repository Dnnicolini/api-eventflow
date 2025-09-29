<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'location_id',
        'name',
        'description',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'price',
        'image_path',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'price' => 'decimal:2',
    ];

    /**
     * @return BelongsTo<User, Event>
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<Category, Event>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo<Location, Event>
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
