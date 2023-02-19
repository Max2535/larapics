<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'file', 'dimension', 'user_id','slug'];

    public static function makeDirectory()
    {
        $subFolder = date('Y/m/d');
        Storage::makeDirectory('images/' . $subFolder);

        return $subFolder;
    }

    public static function getDimension($image)
    {
        [$width, $height] = getImagesize(Storage::path($image));

        return $width . "x" . $height;
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function fileUrl()
    {
        return Storage::url($this->file);
    }

    public function permalink()
    {
        return $this->slug ? route('images.show', $this->slug) : '#';
    }

    public function route($method,$key = 'id'){
        return route("images.{$method}",$this->$key);
    }

    public function getSlug()
    {
        $slug = str($this->title)->slug();
        $numSlugeFound = static::where('slug','regexp' ,"^".$slug."(-[0-9])?")->count();

        if ($numSlugeFound > 0) {
            return $slug."-".$numSlugeFound+1;
        }

        return $slug;
    }

    protected static function booted(){
        static::creating(function($image){
            if($image->title){
                $image->slug = $image->getSlug();
                $image->is_published = true;
            }
        });

        static::updating(function($image){
            if($image->title && ! $image->slug){
                $image->slug = $image->getSlug();
                $image->is_published = true;
            }
        });

        static::deleted(function($image){
            Storage::delete($image->file);
        });
    }
}