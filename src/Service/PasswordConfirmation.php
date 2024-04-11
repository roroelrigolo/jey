<?php
namespace App\Service;

use App\Entity\Product;
use App\Entity\ProductView;
use App\Repository\ProductViewRepository;

class PasswordConfirmation
{

    public function isValid($password, $confirmPassword): string
    {
        if($password != $confirmPassword){
            return 'Les mots de passe ne sont pas identiques, veuillez essayer à nouveau';
        }
        elseif (strlen($password) <= 6){
            return 'Le mot de passe doit contenir au minimum 6 caractères';
        }
        elseif (!preg_match('/[0-9]/', $password)){
            return 'Le mot de passe doit contenir au minimum 1 chiffre [0-9]';
        }
        elseif (!preg_match('/[a-zA-Z]/', $password)){
            return 'Le mot de passe doit contenir au minimum 1 lettre [a-z] ou [A-Z]';
        }
        elseif (!preg_match('/[^a-zA-Z0-9]/', $password)){
            return 'Le mot de passe doit contenir au minimum 1 caractère spécial';
        }
        else{
            return "Valid";
        }
    }
}