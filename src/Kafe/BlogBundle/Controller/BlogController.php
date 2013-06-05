<?php

namespace Kafe\BlogBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Httpfoundation\Response;

use Kafe\BlogBundle\Entity\Article;
use Kafe\BlogBundle\Entity\Image;
use Kafe\BlogBundle\Entity\Commentaire;

/**
 * Description of BlogController
 *
 * @author johanny
 */
class BlogController extends Controller {
    
    public function indexAction($page)
    {
        if( $page < 1 )
        {
          // On déclenche une exception NotFoundHttpException
          throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        }
        
        // Récupération des 10 derniers articles
        $articles = $this->getDoctrine()
                   ->getManager()
                   ->getRepository('KafeBlogBundle:Article')
                   ->getArticles(3, $page);
 
        // Ici, on récupérera la liste des articles, puis on la passera au template
        return $this->render('KafeBlogBundle:Blog:index.html.twig', array(
            'articles' => $articles,
            'page'       => $page,
            'nombrePage' => ceil(count($articles)/3)
        ));
    }
   
   
    public function voirAction(Article $article)
    { 
        return $this->render('KafeBlogBundle:Blog:voir.html.twig', array(
            'article' => $article,
        ));
    }
   
    public function ajouterAction()
    {
        if( $this->get('request')->getMethod() == 'POST' )
        {
            // Ici, on s'occupera de la création et de la gestion du formulaire

            $this->get('session')->getFlashBag()->add('notice', 'Article bien enregistré');

            // Puis on redirige vers la page de visualisation de cet article
            return $this->redirect( $this->generateUrl('kafeblog_voir', array('id' => 5)) );
        }

        // Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('KafeBlogBundle:Blog:ajouter.html.twig');
    }
   
    public function modifierAction(Article $article)
    {
        // TODO : Gérer le formulaire
        
        return $this->render('SdzBlogBundle:Blog:modifier.html.twig', array(
            'article' => $article
        ));
    }
 
    public function supprimerAction($id)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()
                   ->getEntityManager();

        // On récupère l'entité correspondant à l'id $id
        $article = $em->getRepository('KafeBlogBundle:Article')
                      ->find($id);

        // Si l'article n'existe pas, on affiche une erreur 404
        if ($article == null) {
            throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
        }

        if ($this->get('request')->getMethod() == 'POST') {
            // Si la requête est en POST, on supprimera l'article
            $this->get('session')->getFlashBag()->add('info', 'Article bien supprimé');

            // Puis on redirige vers l'accueil
            return $this->redirect( $this->generateUrl('kafeblog_accueil') );
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('KafeBlogBundle:Blog:supprimer.html.twig', array(
            'article' => $article
        ));
    }
    
    public function menuAction($nombre)
    {
        // Récupération des 3 derniers articles
        $em = $this->getDoctrine()
                   ->getManager();
        
        $articleRepository = $em->getRepository('KafeBlogBundle:Article');
        
        $articles = $articleRepository->getDernier(3);

        return $this->render('KafeBlogBundle:Blog:menu.html.twig', array(
          'liste_articles' => $articles
        ));
    }
    
    public function testAction()
    {
        // Création de l'entité
        $article1 = new Article();
        $article1->setTitre("Symfony 2");
        $article1->setAuteur('Kafe');
        $article1->setContenu("C'est un bon framework php");
        
        $article2 = new Article();
        $article2->setTitre("Bootstrap Twitter");
        $article2->setAuteur('Kafe');
        $article2->setContenu("C'est un bon framework pour le css");
        
        $article3 = new Article();
        $article3->setTitre("L'article d'à coté");
        $article3->setAuteur('Kafe');
        $article3->setContenu("C'est un bon article");
        
        $article = new Article();
        $article->setTitre("Projet d'armurerie");
        $article->setAuteur('Kafe');
        $article->setContenu('Projet de nouveau site en cours. Sur symfony 2 on va faire un site sur MH');
        
        // Création de l'entité Image
        $image = new Image();
        $image->setUrl('http://uploads.siteduzero.com/icones/478001_479000/478657.png');
        $image->setAlt('Logo Symfony2');

        // On lie l'image à l'article
        $article->setImage($image);
        
        // Création d'un premier commentaire
        $commentaire1 = new Commentaire();
        $commentaire1->setAuteur('Dizzy');
        $commentaire1->setContenu('Hâte de voir ça!');

        // Création d'un deuxième commentaire, par exemple
        $commentaire2 = new Commentaire();
        $commentaire2->setAuteur('Kafe');
        $commentaire2->setContenu("T'inquiètes on y réfléchi!");

        // On lie les commentaires à l'article
        $commentaire1->setArticle($article);
        $commentaire2->setArticle($article);
        
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // Étape 1 : On « persiste » l'entité
        $em->persist($article);
        $em->persist($article1);
        $em->persist($article2);
        $em->persist($article3);
        $em->persist($commentaire1);
        $em->persist($commentaire2);

        // Étape 2 : On « flush » tout ce qui a été persisté avant
        $em->flush();
    }
}

?>