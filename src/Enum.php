<?php

namespace App;

class Enum  {
    public $sizes = [
        'XS',
        'S',
        'M',
        'L',
        'XL',
        'XXL',
        'XXXL'
    ];

    public $types = [
        'Homme',
        'Femmes',
        'Enfants'
    ];

    public $conditionnements = [
        'Neuf avec étiquette',
        'Neuf sans étiquette',
        'Très bon état',
        'Bon état',
        'Satisfaisant'
    ];

    public $statements = [
        'Disponible',
        'Réservé',
        'Vendu',
        'Supprimé'
    ];

    /**
     * @return string[]
     */
    public static function getSizes(): array
    {
        return self::$sizes;
    }

    /**
     * @return string[]
     */
    public static function getTypes(): array
    {
        return self::$types;
    }

    /**
     * @return string[]
     */
    public static function getConditionnements(): array
    {
        return self::$conditionnements;
    }

    /**
     * @return string[]
     */
    public static function getStatements(): array
    {
        return self::$statements;
    }

}