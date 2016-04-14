<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\AdvertSkill;


class AdvertController extends Controller {

	public function indexAction($page) {
		// On ne sait pas combien de pages il y a
		// Mais on sait qu'une page doit être supérieure ou égale à 1
		if ($page < 1) {
			// On déclenche une exception NotFoundHttpException, cela va afficher
			// une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
			throw $this->createNotFoundException("La page ".$page." n'existe pas.");
		}

		// Ici je fixe le nombre d'annonces par page à 3
		// Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
		$nbPerPage = 3;

		// Ici, on récupérera la liste des annonces, puis on la passera au template
		$listAdverts = $this
				->getDoctrine()
				->getManager()
				->getRepository('OCPlatformBundle:Advert')
				->getAdverts($page, $nbPerPage)
		;

		// On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
		$nbPages = ceil(count($listAdverts) / $nbPerPage);

		// Si la page n'existe pas, on retourne une 404
		if ($page > $nbPages) {
			throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
		}

		// Et modifiez le 2nd argument pour injecter notre liste
		return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
			'listAdverts'	=> $listAdverts,
			'nbPages'		=> $nbPages,
			'page'		=> $page
		));
	}

	
	public function viewAction($id) {
		// Ici, on récupérera l'annonce correspondante à l'id $id
		
		$em = $this->getDoctrine()->getManager();
		
		// autre syntaxe pour faire la même chose, dans l'exo one to one, directement depuis l'EntityManager
		// $advert = $this->getDoctrine()
		//  ->getManager()
		// ->find('OCPlatformBundle:Advert', $id)

		// On récupère l'annonce $id
		$advert = $em
		  ->getRepository('OCPlatformBundle:Advert')
		  ->find($id)
		;

		// $advert est donc une instance de OC\PlatformBundle\Entity\Advert
		// ou null si l'id $id  n'existe pas, d'où ce if :
		if (null === $advert) {
			throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
		}

		// On récupère la liste des candidatures de cette annonce
		$listApplications = $em
		  ->getRepository('OCPlatformBundle:Application')
		  ->findBy(array('advert' => $advert))		// autre possibilité plus bas
		;
		
		// On récupère maintenant la liste des AdvertSkill
		$listAdvertSkills = $em
		  ->getRepository('OCPlatformBundle:AdvertSkill')
		  ->findByAdvert($advert)
		;

		// Le render ne change pas, on passait avant un tableau, maintenant un objet
		return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
				'advert' => $advert,
				'listApplications' => $listApplications,
				'listAdvertSkills' => $listAdvertSkills
		));
	}

	
	public function addAction(Request $request) {
		
		// La gestion d'un formulaire est particulière, mais l'idée est la suivante :
		// Si la requête est en POST, c'est que le visiteur a soumis le formulaire
		if ($request->isMethod('POST')) {
			// Ici, on s'occupera de la création et de la gestion du formulaire
			
			$request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
			
			// Puis on redirige vers la page de visualisation de cettte annonce
			return $this->redirect($this->generateUrl('oc_platform_view', array('id' => 1)));
		}

		// Si on n'est pas en POST, alors on affiche le formulaire
		return $this->render('OCPlatformBundle:Advert:add.html.twig');
	}

	
	public function editAction($id) 
	{
		// Ici, on récupérera l'annonce correspondante à $id
		// Même mécanisme que pour l'ajout
		if ($request->isMethod('POST')) {
			$request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

			return $this->redirectToRoute('oc_platform_view', array('id' => 5));
		}

		$em = $this->getDoctrine()->getManager();

		// On récupère l'annonce $id
		$advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

		// Si l'annonce n'existe pas, on affiche une erreur 404
		if ($advert == null) {
			throw $this->createNotFoundException("L'annonce d'id " . $id . " n'existe pas.");
		}

		// Ici, on s'occupera de la création et de la gestion du formulaire

		
		return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
			'advert' => $advert
		));
	}

	
	public function deleteAction($id, Request $request ) 
	{
		
		$em = $this->getDoctrine()->getManager();

		// On récupère l'annonce $id
		$advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

		// Si l'annonce n'existe pas, on affiche une erreur 404
		if (null === $advert) {
		  throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}

		if ($request->isMethod('POST')) {
			// Si la requête est en POST, on deletea l'article

			$request->getSession()->getFlashBag()->add('info', 'Annonce bien supprimée.');

			// Puis on redirige vers l'accueil
			return $this->redirect($this->generateUrl('oc_platform_home'));
		}
		
	

		// Si la requête est en GET, on affiche une page de confirmation avant de delete
		return $this->render('OCPlatformBundle:Advert:delete.html.twig', array(
			'advert' => $advert
		));
	}

	
	public function menuAction($limit = 3) 
	{

		$listAdverts = $this->getDoctrine()
				->getManager()
				->getRepository('OCPlatformBundle:Advert')
				->findBy(
				array(),				// Pas de critère
				array('date' => 'desc'),	// On trie par date décroissante
				$limit,				// On sélectionne $limit annonces
				0					// À partir du premier
		);


		return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
			// Tout l'intérêt est ici : le contrôleur passe
			// les variables nécessaires au template !
			'listAdverts' => $listAdverts
		));
	}
	
	
	public function listAction() 
	{
		$listAdverts = $this
			->getDoctrine()
			->getManager()
			->getRepository('OCPlatformBundle:Advert')
			->getAdvertWithApplications()
		;

		foreach ($listAdverts as $advert) {
			// Ne déclenche pas de requête : les candidatures sont déjà chargées !
			// Vous pourriez faire une boucle dessus pour les afficher toutes
			$advert->getApplications();
		}
	}
	
	
	public function testAction() 
	{
		$em = $this->getDoctrine()->getManager();

		// On récupère l'annonce $id
		$advert = $em->getRepository('OCPlatformBundle:Advert')->find(1);
		//$advert = new Advert();
		//$advert->setTitle("Recherche développeur !");

		//$em = $this->getDoctrine()->getManager();
		//$em->persist($advert);
		//$em->flush(); // C'est à ce moment qu'est généré le slug

		return new Response('Slug généré : ' . $advert->getSlug());
		// Affiche « Slug généré : recherche-developpeur »
	}
	
	public function purge($days)
	{
		$purgator = $this->container->get('oc_platform.advert_purger');
	
		$purgator->removeOldUpdate($days);
		

	}
}