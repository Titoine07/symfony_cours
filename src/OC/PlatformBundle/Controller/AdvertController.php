<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller {

   public function viewAction($id) {
      // On utilise le raccourci : il crée un objet Response
      // Et lui donne comme contenu le contenu du template
      // return $this->get('templating')->renderResponse(
      //            'OCPlatformBundle:Advert:view.html.twig',
      //            array('id'  => $id)
      //            );

      // OU 
      // le contrôleur lui-même dispose d'un raccourci pour utiliser cette méthode renderResponse
      return $this->render(
         'OCPlatformBundle:Advert:view.html.twig',
         array('id' => $id)
      );
   }

}
