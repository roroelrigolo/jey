<?php

namespace App\Twig;

use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('custom_time', [$this, 'formatTime'])
        ];
    }

    /*Fonction qui permet d'afficher le temps de dernière action d'une entité. Ex : Il y a 30 min. */
    public function formatTime($date)
    {
        $now = new DateTime();
        $difference = $date->diff($now);
        $time = "Il y a ";
        if ($difference->y > 0) {
            $time .= $difference->y . "an";
        } else {
            if ($difference->m > 0) {
                $time .= $difference->m . "moi";
            } else {
                if ($difference->d > 0) {
                    $time .= $difference->d . "j";
                } else {
                    if ($difference->h > 0) {
                        $time .= $difference->h . "h";
                    } else {
                        if ($difference->i > 0) {
                            $time .= $difference->i . "min";
                        } else {
                            if ($difference->s > 0) {
                                $time .= $difference->s . "s";
                            } else {
                                $time = "maintenant";
                            }
                        }
                    }
                }
            }
        }
        return $time;
    }
}