<?php

namespace App\Models;

use App\Models\LoginToken;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Intervention\Image\Facades\Image as ImageIntervention;

class Moderator extends Authenticatable
{
   	use Notifiable, HasRoles;

    protected $guarded=['id'];

    public function token()
    {
        return $this->morphOne(LoginToken::class, 'tokenable');
    }

    public function setProfilePictureAttribute($originImageFile)
    {
        if ($originImageFile) {
            
            $imageObject = ImageIntervention::make($originImageFile);
            $imageObject->resize(200, 200)->save('assets/admin/images/profile/'.$originImageFile->hashname());

            $this->attributes['picture'] = $originImageFile->hashname();
        }
    }

    public function getFullNameAttribute()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function getProfilePictureAttribute()
    {
        if ($this->picture) {

            return 'assets/admin/images/profile/'.$this->picture;
        }
    }

    /*
    public function setProfilePictureAttribute($originImageFile)
    {
    	if ($originImageFile) {
    		
    		// $originImageFile = $request->file('picture');
            $imageObject = ImageIntervention::make($originImageFile)->encode('jpg');
            $imageObject->resize(200, 200)->save('assets/moderator/images/profile/'.$originImageFile->hashname());
            $this->attributes['picture'] = $originImageFile->hashname();
    	}
    }
    */
}
