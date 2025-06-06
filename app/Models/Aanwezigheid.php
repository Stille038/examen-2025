<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aanwezigheid extends Model
{
    protected $table = 'aanwezigheden';

    protected $fillable = [
        'studentnummer',
        'aanwezigheid',
        'rooster',
        'week',
        'jaar',
        'percentage',
        'categorie',
        'kleur',
    ];

   
    public static function boot()
    {
        parent::boot();

        static::saving(function ($aanwezigheid) {
            // Vermijd deling door 0
            if ($aanwezigheid->rooster > 0) {
                $percentage = ($aanwezigheid->aanwezigheid / $aanwezigheid->rooster) * 100;
            } else {
                $percentage = 0;
            }

            $aanwezigheid->percentage = round($percentage, 2);

            // categorie & kleur
            if ($percentage >= 100) {
                $aanwezigheid->categorie = 'Perfect';
                $aanwezigheid->kleur = 'paars';
            } elseif ($percentage >= 95) {
                $aanwezigheid->categorie = 'Excellent';
                $aanwezigheid->kleur = 'blauw';
            } elseif ($percentage >= 80) {
                $aanwezigheid->categorie = 'Goed';
                $aanwezigheid->kleur = 'groen';
            } elseif ($percentage >= 65) {
                $aanwezigheid->categorie = 'Redelijk';
                $aanwezigheid->kleur = 'geel';
            } elseif ($percentage >= 50) {
                $aanwezigheid->categorie = 'Onvoldoende';
                $aanwezigheid->kleur = 'oranje';
            } elseif ($percentage >= 0) {
                $aanwezigheid->categorie = 'Kritiek';
                $aanwezigheid->kleur = 'rood';
            } else {
                $aanwezigheid->categorie = 'fail';
                $aanwezigheid->kleur = 'donkerrood';
            }
        });
    }
}