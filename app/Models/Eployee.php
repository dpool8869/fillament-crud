<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eployee extends Model
{
    use HasFactory;

    protected $fillable = [
        "first_name",
        "middle_name",
        "last_name",
        "address",
        "zip_code",
        "birth_date",
        "date_hired",
        "country_id",
        "state_id",
        "city_id",
        "department_id"
    ];

    // Relationships
    /**
     * Get the country that owns the Eployee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the team that owns the Eployee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
