<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;


class AdvertController extends Controller {

	public function indexAction($page) {
		// On ne sait pas combien de pages il y a
		// Mais on sait qu'une page doit être supérieure ou égale à 1
		if ($page < 1) {
			// On déclenche une exception NotFoundHttpException, cela va afficher
			// une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
			throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
		}

		// Ici, on récupérera la liste des annonces, puis on la passera au template
		// Mais pour l'instant, on ne fait qu'appeler le template
		// Notre liste d'annonce en dur
		$listAdverts = array(
			array(
				'title' => 'Recherche développpeur Symfony2',
				'id' => 1,
				'author' => 'Alexandre',
				'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
				'date' => new \Datetime()),
			array(
				'title' => 'Mission de webmaster',
				'id' => 2,
				'author' => 'Hugo',
				'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
				'date' => new \Datetime()),
			array(
				'title' => 'Offre de stage webdesigner',
				'id' => 3,
				'author' => 'Mathieu',
				'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
				'date' => new \Datetime())
		);


		// Et modifiez le 2nd argument pour injecter notre liste
		return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
					'listAdverts' => $listAdverts
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
		  ->findBy(array('advert' => $advert))
		;
		
		// On récupère maintenant la liste des AdvertSkill
		$listAdvertSkills = $em
		  ->getRepository('OCPlatformBundle:AdvertSkill')
		  ->findBy(array('advert' => $advert))
		;

		// Le render ne change pas, on passait avant un tableau, maintenant un objet
		return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
				'advert' => $advert,
				'listApplications' => $listApplications,
				'listAdvertSkills' => $listAdvertSkills
		));
	}

	public function addAction(Request $request) {

		// On récupère l'EntityManager
		$em = $this->getDoctrine()->getManager();

		// Création de l'entité Advert
		$advert = new Advert();
		$advert->setTitle('Recherche développeur Symfony2.');
		$advert->setAuthor('Alexandre');
		$advert->setContent("Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…");

		// On récupère toutes les compétences possibles
		$listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();

		// Pour chaque compétence
		foreach ($listSkills as $skill) {
		  // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
		  $advertSkill = new AdvertSkill();

		  // On la lie à l'annonce, qui est ici toujours la même
		  $advertSkill->setAdvert($advert);
		  // On la lie à la compétence, qui change ici dans la boucle foreach
		  $advertSkill->setSkill($skill);

		  // Arbitrairement, on dit que chaque compétence est requise au niveau 'Expert'
		  $advertSkill->setLevel('Expert');

		  // Et bien sûr, on persiste cette entité de relation, propriétaire des deux autres relations
		  $em->persist($advertSkill);
		}

		// Doctrine ne connait pas encore l'entité $advert. Si vous n'avez pas définit la relation AdvertSkill
		// avec un cascade persist (ce qui est le cas si vous avez utilisé mon code), alors on doit persister $advert
		$em->persist($advert);

		// On déclenche l'enregistrement
		$em->flush();

		// … reste de la méthode

		// La gestion d'un formulaire est particulière, mais l'idée est la suivante :
		// Si la requête est en POST, c'est que le visiteur a soumis le formulaire
		if ($request->isMethod('POST')) {
			// Ici, on s'occupera de la création et de la gestion du formulaire
			$request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
			// Puis on redirige vers la page de visualisation de cettte annonce
			return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
		}

		// Si on n'est pas en POST, alors on affiche le formulaire
		return $this->render('OCPlatformBundle:Advert:add.html.twig', array(
			'advert' => $advert
		));
		
	}

	public function editAction($id, Request $request) {
		// Ici, on récupérera l'annonce correspondante à $id
		// Même mécanisme que pour l'ajout
		if ($request->isMethod('POST')) {
			$request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

			return $this->redirectToRoute('oc_platform_view', array('id' => 5));
		}

		$em = $this->getDoctrine()->getManager();

		// On récupère l'annonce $id
		$advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

		if (null === $advert) {
			throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}

		// La méthode findAll retourne toutes les catégories de la base de données
		$listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();

		// On boucle sur les catégories pour les lier à l'annonce
		foreach ($listCategories as $category) {
			$advert->addCategory($category);
		}

		// Pour persister le changement dans la relation, il faut persister l'entité propriétaire
		// Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

		// Étape 2 : On déclenche l'enregistrement
		$em->flush();

		// … reste de la méthode

		return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
					'advert' => $advert
		));
	}

	public function deleteAction($id) {
		
		$em = $this->getDoctrine()->getManager();

		// On récupère l'annonce $id
		$advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

		if (null === $advert) {
		  throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
		}

		// On boucle sur les catégories de l'annonce pour les supprimer
		foreach ($advert->getCategories() as $category) {
			$advert->removeCategory($category);
		}

		// Pour persister le changement dans la relation, il faut persister l'entité propriétaire
		// Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

		// On déclenche la modification
		$em->flush();
    
		// ...
		
		// Ici, on récupérera l'annonce correspondant à $id
		// Ici, on gérera la suppression de l'annonce en question

		return $this->render('OCPlatformBundle:Advert:delete.html.twig');
	}

	public function menuAction() {
		// On fixe en dur une liste ici, bien entendu par la suite
		// on la récupérera depuis la BDD !
		$listAdverts = array(
			array('id' => 2, 'title' => 'Recherche développeur Symfony2'),
			array('id' => 5, 'title' => 'Mission de webmaster'),
			array('id' => 9, 'title' => 'Offre de stage webdesigner')
		);

		return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
			// Tout l'intérêt est ici : le contrôleur passe
			// les variables nécessaires au template !
			'listAdverts' => $listAdverts
		));
	}
	
	public function listAction() {
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

}