<?php

namespace App;

class Enum  {
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
        'Homme/Femmes',
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
        'A valider',
    ];

    public static $numbers = [
        0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,
        11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
        21, 22, 23, 24, 25, 26, 27, 28, 29, 30,
        31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
        41, 42, 43, 44, 45, 46, 47, 48, 49, 50,
        51, 52, 53, 54, 55, 56, 57, 58, 59, 60,
        61, 62, 63, 64, 65, 66, 67, 68, 69, 70,
        71, 72, 73, 74, 75, 76, 77, 78, 79, 80,
        81, 82, 83, 84, 85, 86, 87, 88, 89, 90,
        91, 92, 93, 94, 95, 96, 97, 98, 99, 00,
    ];

    public static $alert_statuts = [
        'A vérifier',
        'Validé'
    ];

    public static $alert_types = [
        'Annonce',
        'Utilisateur'
    ];

    public static $notification_categorys = [
        'Messages',
        'Abonnements',
        'Annonces',
        'Signalements'
    ];

    public static $message_step_type = [
        'Book',
        'ConfirmBook',
        'CancelBook',
        'Sell',
        'End'
    ];

    public static $statuts_contact = [
        'A traité',
        'Traité',
    ];

    /**
     * @return string[]
     */
    public function getSizes(): array
    {
        return self::$sizes;
    }

    /**
     * @return string[]
     */
    public function getTypes(): array
    {
        return self::$types;
    }

    /**
     * @return string[]
     */
    public function getConditionnements(): array
    {
        return self::$conditionnements;
    }

    /**
     * @return string[]
     */
    public function getStatements(): array
    {
        return self::$statements;
    }

    /**
     * @return string[]
     */
    public function getNumbers(): array
    {
        return self::$numbers;
    }

    /**
     * @return string[]
     */
    public function getAlertTypes(): array
    {
        return self::$alert_types;
    }

    /**
     * @return string[]
     */
    public function getAlertStatuts(): array
    {
        return self::$alert_statuts;
    }

    /**
     * @return string[]
     */
    public function getNotificationCategorys(): array
    {
        return self::$notification_categorys;
    }

    /**
     * @return string[]
     */
    public function getStatutsContact(): array
    {
        return self::$statuts_contact;
    }

}