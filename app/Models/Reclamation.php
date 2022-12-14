<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'telephone',
        'email',
        'reference',
        'type',
        'description',
    ];

    public static $types = [
        '' => "choisir un type",
        'annonce_frauduleuse' => "Annonce frauduleuse",
        'photo_description' => "Photo / description non conforme",
        'professionel_immobilier' => "Professionel de l'immobilier",
        'discrimnation' => "Discrimination",
        'bien_insalubre' => "Bien insalubre",
        'appreciation' => "Appréciation",
        'autre' => "Autre",
    ];
}
