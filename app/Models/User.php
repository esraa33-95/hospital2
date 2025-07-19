<?php

namespace App\Models;

 use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements MustVerifyEmail,HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;
    use SoftDeletes;
    use HasRoles;
    use InteractsWithMedia;

   
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'image',
        'password',
       'is_verified',
       'department_id',
       'user_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    
    public function otps()
    {
        return $this->hasMany(Otp::class);
    }

    public function department()
    {
      return $this->belongsTo(Department::class);
    }
  
    public function rates()
    {
      return $this->hasMany(Rate::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
    
   
     public function certificate()
    {
        return $this->hasMany(Certificate::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

   public function surgeries()
   {
    return $this->belongsToMany(Surgery::class,'surgery_user')->withTimestamps();
   }

   public function allergies()
   {
    return $this->belongsToMany(Allergy::class,'allergy_user')->withTimestamps();
   }

  public function diseases()
   {
    return $this->belongsToMany(Disease::class,'disease_user')->withTimestamps();
   }

    public function blood()
   {
    return $this->belongsToMany(Blood::class, 'blood_user')->withTimestamps();
   }

    public function banner()
   {
    return $this->hasMany(Banner::class);
   }


   public function address()
{
    return $this->hasMany(Address::class);
}

   public function visits()
{
    return $this->belongsToMany(Visit::class,'visit_doctors')
                 ->withPivot('active','price')->withTimestamps();
}

 public function orders()
    {
        return $this->hasMany(Order::class);
    }

     public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }




protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        $model->uuid = (string) Str::uuid();
    });
}


}
