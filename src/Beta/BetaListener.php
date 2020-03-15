<?php
// src/Beta/BetaListener.php

namespace App\Beta;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class BetaListener
{
    // Notre processeur
    protected $betaHTML;

    // La date de fin de la version bêta :
    // - Avant cette date, on affichera un compte à rebours (J-3 par exemple)
    // - Après cette date, on n'affichera plus le « bêta »
    protected $endDate;

    public function __construct(BetaHTMLAdder $betaHTML, $endDate)
    {
        $this->betaHTML = $betaHTML;
        $this->endDate  = new \Datetime($endDate);
    }

    // L'argument de la méthode est un FilterResponseEvent
    public function processBeta(ResponseEvent $event)
    {
        // On teste si la requête est bien la requête principale (et non une sous-requête)
        if (!$event->isMasterRequest()) {
            return;
        }

        $dateNow = new \Datetime();
        $remainingDays = date_diff($dateNow,$this->endDate)->format('%R%a');

        // Si la date est dépassée, on ne fait rien
        if ($remainingDays <= 0) {
            return;
        }

        // Ici on modifie comme on veut la réponse…// On utilise notre BetaHRML
        $response = $this->betaHTML->addBeta($event->getResponse(), $remainingDays);

        // Puis on insère la réponse modifiée dans l'évènement
        $event->setResponse($response);
    }
}