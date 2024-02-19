<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Adoption extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'cpf', 'contact', 'observations', 'status', 'pet_id'];
    public function pet(){
        return $this->HasOne(Pet::class, 'id', 'pet_id');
    }
}
