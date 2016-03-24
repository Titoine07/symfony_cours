<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;


// N'oubliez pas ce use :
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;


class AdvertController extends Controller
{
public function indexAction()
  {
		
		 // On veut avoir l'URL de l'annonce d'id 5.
        $url = $this->generateUrl(
            'oc_platform_home', // 1er argument : le nom de la route
            array('id' => 5),
            true    // 2e argument : les valeurs des paramètres
        );
        // $url vaut « /platform/advert/5 »

        return new Response("L'URL de l'annonce d'id 5 est : ".$url);
					
				// exo précédent
//			 La convention pour le nom du template est la même que pour le nom du contrôleur,
//			  souvenez-vous : NomDuBundle:NomDuContrôleur:NomDeLAction.
//    $content = $this
//						->get('templating')
//						->render('OCPlatformBundle:Advert:index.html.twig', array (
//								'nom'	=>	'Antoine'
//						)
//		);
//    return new Response($content);
  }
	
  
  
  
  
	// La route fait appel à OCPlatformBundle:Advert:view,
  // on doit donc définir la méthode viewAction.
  // On donne à cette méthode l'argument $id, pour
  // correspondre au paramètre {id} de la route
  
  // On injecte la requête dans les arguments de la méthode, l'un des arguments est type avec Request
public function viewAction($id, Request $request)
  {
    // $id vaut 5 si l'on a appelé l'URL /platform/advert/5
    // Ici, on récupèrera depuis la base de données
    // l'annonce correspondant à l'id $id.
    // Puis on passera l'annonce à la vue pour
    // qu'elle puisse l'afficher
 
    // On récupère notre paramètre tag
    $tag = $request->query->get('tag'); //il existe d'autres types de paramètres
    //$request->request->get('tag') pour $_POST
    //$request->cookie->get('REQUEST_URI')  pour $_SERVER
    //$request->cookie->get('tag')  pour $_COOKIE
    
    return new Response("Affichage de l'annonce d'id : ".$id.", avec le tag: ".$tag);
    
    
     // POUR AFFICHER UNE PAGE 404
    // On crée la réponse sans lui donner de contenu pour le moment
   // $response = new Response();

    // On définit le contenu
   // $response->setContent("Ceci est une page d'erreur 404");

    // On définit le code HTTP à « Not Found » (erreur 404)
   // $response->setStatusCode(Response::HTTP_NOT_FOUND);

    // On retourne la réponse
   // return $response;
  }
  // ... et la méthode indexAction que nous avons déjà créée
	
  
  
	
	    // On récupère tous les paramètres en arguments de la méthode
public function viewSlugAction($slug, $year, $format)
    {
        return new Response(
            "On pourrait afficher l'annonce correspondant au
            slug '".$slug."', créée en ".$year." et au format ".$format."."
        );
    }
}