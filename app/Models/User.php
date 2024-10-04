<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        
        'identificacion',
        'sexo',
        'fecha_nacimiento',
        'celular',
        
        'cargo',
        'tipo_user',
        'firma',
    ];
    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getCreatedAtAttribute()
    {
        return date('d-m-Y H:i', strtotime($this->attributes['created_at']));
    }

    public function getUpdatedAtAttribute()
    {
        return date('d-m-Y H:i', strtotime($this->attributes['updated_at']));
    }

    public function getEmailVerifiedAtAttribute()
    {
        return $this->attributes['email_verified_at'] == null ? null : date('d-m-Y H:i', strtotime($this->attributes['email_verified_at']));
    }

    public function getPermissionArray()
    {
        return $this->getAllPermissions()->mapWithKeys(function ($pr) {
            return [$pr['name'] => true];
        });
    }
    public function aall(){
        return $this->Where('id','>',1)->get();
    }

    
    // relationships
    public function inspeccionUsers(){
        return $this->hasMany(inspeccion_user::class);
    }
    
    public function inspeccions(){return $this->belongsToMany(Inspeccion::class,'inspeccion_user');}


    public function TieneFirmaGuardada(){
        return $this->firma != null;
    }

    

//    public function formularios(): HasMany {
//        return $this->HasMany(formulario::class,'operario_id');
//    }
//    public function empresa(): BelongsTo {
//        return $this->Belongsto(empresa::class);
//    }

    // //# belongs to many

   
}


