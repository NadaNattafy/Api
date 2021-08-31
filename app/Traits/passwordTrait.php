<?php
namespace App\Traits;
use Illuminate\Support\Facades\Hash;

trait passwordTrait
{
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] =Hash::make($value);
        }
    }
}
