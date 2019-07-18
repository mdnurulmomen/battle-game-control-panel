<?php

namespace App\Models;

use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function treasureType()
    {
    	return $this->belongsTo(TreasureType::class, 'treasure_type_id', 'id');
    }

    public function getLogoPictureAttribute()
	{
	    return 'assets/admin/images/vendor/'.$this->logo;
	}

	public function setLogoPictureAttribute($originalImage)
	{
	    if ($originalImage) {
	    	
	    	$intervationObject = Image::make($originalImage);
	    	$imgResizing = $intervationObject->resize(50, 50);
	    	$imgSaving = $imgResizing->save('assets/admin/images/vendor/'.$this->name.'.png');

	    	$this->attributes['logo'] = $this->name.'.png';
	    }
	}
}