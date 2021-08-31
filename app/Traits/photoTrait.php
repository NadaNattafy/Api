<?php
namespace App\Traits;
trait photoTrait
{
    public function setPathAttribute($value)
{
    if (is_file($value))
        {
        $this->attributes['path'] = uploadImage($value);
        }
        else
        {
            $this->attributes['path'] = $value;
        }
 }
}
