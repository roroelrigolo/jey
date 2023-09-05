<?php

namespace App;

class Enum {
    public static $sizes = [
        'XS',
        'S',
        'M',
        'L',
        'XL',
        'XXL',
        'XXXL'
    ];
    public static $types = [
        'Homme',
        'Femmes',
        'Enfants'
    ];
    public static $conditionnements = [
        'Neuf avec étiquette',
        'Neuf sans étiquette',
        'Très bon état',
        'Bon état',
        'Satisfaisant'
    ];
    public static $statements = [
        'Disponible',
        'Réservé',
        'Vendu',
        'Supprimé'
    ];
}