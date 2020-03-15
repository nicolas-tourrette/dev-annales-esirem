<?php
// src/Beta/BetaHTMLAdder.php

namespace App\Beta;

use Symfony\Component\HttpFoundation\Response;

class BetaHTMLAdder
{
    // Méthode pour ajouter le « bêta » à une réponse
    public function addBeta(Response $response, $remainingDays)
    {
        $content = $response->getContent();

        // Code à rajouter
        // (Je mets ici du CSS en ligne, mais il faudrait utiliser un fichier CSS bien sûr !)
        $html = '<div style="background: #37a2db; color: #fff; width: 100%; text-align: center; padding: 0.5em;">Beta - Release J-'.(int) $remainingDays.'<br>Version finale le 1er avril !</div>';

        // Insertion du code dans la page, au début du <body>
        $content = str_replace(
            '<div class="page-wrapper">',
            '<div class="page-wrapper"> '.$html,
            $content
        );

        // Modification du contenu dans la réponse
        $response->setContent($content);

        return $response;
    }
}