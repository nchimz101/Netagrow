<?php

namespace Modules\Fields\Models;

use App\Models\Posts;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Fieldnote extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $table="fieldnotes";

    public function notetype()
    {
        return $this->belongsTo(Posts::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class)->withTrashed();
    }

    public function by()
    {
        return $this->belongsTo(User::class,'created_by')->withTrashed();
    }

    public function for()
    {
        return $this->belongsTo(User::class,'assigned_to')->withTrashed();
    }

    public function notestatus()
    {
        return $this->belongsTo(Posts::class);
    }

    public function getImageLinkAttribute(){
        if(str_contains($this->image, "http")){
            return $this->image;
        }else {
            if(substr( $this->image, 0, 8) === "/images/"){
                return $this->image;
            }else{
                return config('app.company_images').$this->image."_large.jpg";
            }
            
        }
        
    }
}
