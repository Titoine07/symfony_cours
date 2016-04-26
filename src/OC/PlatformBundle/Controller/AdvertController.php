<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Form\AdvertType;
use OC\PlatformBundle\Form\AdvertEditType;
use AB\BigbrotherBundle\Bigbrother\BigbrotherEvents;
use AB\BigbrotherBundle\Bigbrother\MessagePostEvent;


class AdvertController extends Controller 
{

	public function indexAction($page)
	{
		
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

	
	public function viewAction($id)
	{
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

	
	public function addAction(Request $request) 
	{
		    // On vérifie que l'utilisateur dispose bien du rôle ROLE_AUTEUR
		if (!$this->get('security.authorization_checker')->isGranted('ROLE_AUTEUR')) {
			// Sinon on déclenche une exception « Accès interdit »
			throw new AccessDeniedException('Accès limité aux auteurs.');
		}

		// Ici l'utilisateur a les droits suffisant,
    // on peut ajouter une annonce
		
		
		// On crée un objet Advert
		$advert = new Advert();

		// On crée le Form grâce au service form factory.
		// Cette méthode utilise le composant Form pour construire un formulaire à partir du AdvertType passé en argument
		//$form = $this->get('form.factory')->create(new AdvertType, $advert);   Version Longue
		$form = $this->createForm(new AdvertType(), $advert);
		
		// On fait le lien Requête <-> Formulaire
		// À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
		//$form->handleRequest($request);

		// On vérifie que les valeurs entrées sont correctes
		// (Nous verrons la validation des objets en détail dans le prochain chapitre)
		if ($form->handleRequest($request)->isValid()) 
		{
			// On crée l'évènement avec ses 2 arguments
			$event = new MessagePostEvent($advert->getContent(), $advert->getUser());

			// On déclenche l'évènement
			$this
				->get('event_dispatcher')
				->dispatch(BigbrotherEvents::onMessagePost, $event)
			;

			// On récupère ce qui a été modifié par le ou les listeners, ici le message
			$advert->setContent($event->getMessage());
			
			
			// On enregistre notre objet $advert dans la base de données, par exemple
			$em = $this->getDoctrine()->getManager();
			$em->persist($advert);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

			// On redirige vers la page de visualisation de l'annonce nouvellement créée
			return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
		}

		// À ce stade, le formulaire n'est pas valide car :
		// - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
		// - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
		return $this->render('OCPlatformBundle:Advert:add.html.twig', array(
			'form' => $form->createView()
		));
	}

	
	public function editAction($id, Request $request) 
	{
		
		$em = $this->getDoctrine()->getManager();

		// On récupère l'annonce $id
		$advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

		if (null === $advert) 
		{
		throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
		}

		
		$form = $this->createForm(new AdvertEditType(), $advert);

		if ($form->handleRequest($request)->isValid()) 
		{
			// Inutile de persister ici, Doctrine connait déjà notre annonce
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

			// On redirige vers la page de visualisation de l'annonce nouvellement créée
			return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
		}

		
		return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
					'form' => $form->createView(),
					'advert' => $advert // Je passe également l'annonce à la vue si jamais elle veut l'afficher
		));
	}

	
	public function deleteAction($id, Request $request ) 
	{
		
		$em = $this->getDoctrine()->getManager();

		// On récupère l'annonce $id
		$advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

		// Si l'annonce n'existe pas, on affiche une erreur 404
		if (null === $advert) 
		{
		  throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}
		
		// On crée un formulaire vide, qui ne contiendra que le champ CSRF
		// Cela permet de protéger la suppression d'annonce contre cette faille
		$form = $this->createFormBuilder()->getForm();

		if ($form->handleRequest($request)->isValid()) 
		{
			$em->remove($advert);
			$em->flush();

			$request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

			return $this->redirect($this->generateUrl('oc_platform_home'));
		}
	

		// Si la requête est en GET, on affiche une page de confirmation avant de supprimer
		return $this->render('OCPlatformBundle:Advert:delete.html.twig', array(
					'advert' => $advert,
					'form' => $form->createView()
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
		$advert = new Advert;

		$advert->setDate(new \Datetime());  // Champ « date » OK
		$advert->setTitle('abc');		   // Champ « title » incorrect : moins de 10 caractères
		//$advert->setContent('blabla');    // Champ « content » incorrect : on ne le définit pas
		$advert->setAuthor('A');			// Champ « author » incorrect : moins de 2 caractères
		// On récupère le service validator
		$validator = $this->get('validator');

		// On déclenche la validation sur notre object
		$listErrors = $validator->validate($advert);

		// Si le tableau n'est pas vide, on affiche les erreurs
		if (count($listErrors) > 0) {
			return new Response(print_r($listErrors, true));
		} else {
			return new Response("L'annonce est valide !");
		}
	}
	
	public function purgeAction($days)
	{
		$purgator = $this->container->get('oc_platform.advert_purger');
	
		
		$purgator->removeOldUpdate($days);
	}
}