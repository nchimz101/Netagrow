<?php

namespace Modules\Fields\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;



class Field extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function getAreaHaAttribute($value)
    {
        return round($this->area/10000,2)." ha";
    }


    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    public function notes()
    {
        return $this->hasMany(Fieldnote::class)->orderBy('id','desc')->with(['notetype','notestatus','by','for']);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Fields\Database\Factories\Field::new();
    }
}
