<?php

namespace Kafe\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Article
 *
 * @ORM\Table(name="kafe_article")
 * @ORM\Entity(repositoryClass="Kafe\BlogBundle\Entity\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_edition", type="datetime")
     */
    private $dateEdition;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;
    
    /**
     * @ORM\Column(length=128, unique=true)
     * @Gedmo\Slug(fields={"titre"})
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255)
     */
    private $auteur;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(name="publication", type="boolean")
     */
    private $publication;
    
    /**
     * @ORM\OneToOne(targetEntity="Kafe\BlogBundle\Entity\Image", cascade={"persist"})
     */
    private $image;
    
    /**
     * @ORM\Column(name="nb_commentaires", type="integer")
     */
    private $nbCommentaires;
    
    /**
     * @ORM\OneToMany(targetEntity="Kafe\BlogBundle\Entity\Commentaire", mappedBy="article")
     */
    private $commentaires;
    
    /**
     * @ORM\ManyToMany(targetEntity="Kafe\BlogBundle\Entity\Categorie", cascade={"persist"})
     */
    private $categories;

    public function __construct()
    {
        $this->date = new \Datetime();
        $this->dateEdition = $this->date;
        
        $this->nbCommentaires = 0;
        $this->publication = true;
         
        $this->categories   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function updateDate()
    {
        $this->setDateEdition(new \Datetime());
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     * @return Article
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    
        return $this;
    }

    /**
     * Get auteur
     *
     * @return string 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Article
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    
        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set publication
     *
     * @param boolean $publication
     * @return Article
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;
    
        return $this;
    }

    /**
     * Get publication
     *
     * @return boolean 
     */
    public function getPublication()
    {
        return $this->publication;
    }
    
    /**
     * @param Sdz\BlogBundle\Entity\Image $image
     */
    public function setImage(\Kafe\BlogBundle\Entity\Image $image = null)
    {
        $this->image = $image;
    }

   /**
    * @return Sdz\BlogBundle\Entity\Image 
    */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add categories
     *
     * @param \Kafe\BlogBundle\Entity\Categorie $categories
     * @return Article
     */
    public function addCategorie(\Kafe\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Kafe\BlogBundle\Entity\Categorie $categories
     */
    public function removeCategorie(\Kafe\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add commentaires
     *
     * @param \Kafe\BlogBundle\Entity\Commentaire $commentaires
     * @return Article
     */
    public function addCommentaire(\Kafe\BlogBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires[] = $commentaires;
        $commentaires->setArticle($this);
        return $this;
    }

    /**
     * Remove commentaires
     *
     * @param \Kafe\BlogBundle\Entity\Commentaire $commentaires
     */
    public function removeCommentaire(\Kafe\BlogBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires->removeElement($commentaires);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Set dateEdition
     *
     * @param \DateTime $dateEdition
     * @return Article
     */
    public function setDateEdition($dateEdition)
    {
        $this->dateEdition = $dateEdition;
    
        return $this;
    }

    /**
     * Get dateEdition
     *
     * @return \DateTime 
     */
    public function getDateEdition()
    {
        return $this->dateEdition;
    }

    /**
     * Set nbCommentaires
     *
     * @param integer $nbCommentaires
     * @return Article
     */
    public function setNbCommentaires($nbCommentaires)
    {
        $this->nbCommentaires = $nbCommentaires;
    
        return $this;
    }

    /**
     * Get nbCommentaires
     *
     * @return integer 
     */
    public function getNbCommentaires()
    {
        return $this->nbCommentaires;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
}