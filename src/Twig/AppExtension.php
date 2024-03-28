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
        $time = "il y a ";
        if ($difference->y > 0) {
            $time .= $difference->y . " an";
        } else {
            if ($difference->m > 0) {
                $time .= $difference->m . " mois";
            } else {
                if ($difference->d > 0) {
                    $time .= ($difference->h > 12) ? ($difference->d + 1) : $difference->d;
                    $time .= "j";
                } else {
                    if ($difference->h > 0) {
                        $time .= ($difference->i > 30) ? ($difference->h + 1) : $difference->h;
                        $time .= "h";
                    } else {
                        if ($difference->i > 0) {
                            $time .= ($difference->s > 30) ? ($difference->i + 1) : $difference->i;
                            $time .= "min";
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