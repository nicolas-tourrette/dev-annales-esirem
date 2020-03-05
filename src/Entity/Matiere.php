<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatiereRepository")
 * @UniqueEntity(fields="id", message="Une matière possédant cet identifiant existe déjà.")
 */
class Matiere
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice({"GEIPI", "IE", "MDD", "ROBOTIQUE"})
     */
    private $departement;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\Choice({"1A", "2A", "3A"})
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $specialite;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cours", mappedBy="matiere", cascade={"persist", "remove"})
     */
    private $cours;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Annale", mappedBy="matiere")
     */
    private $annales;

    public function __construct()
    {
        $this->annales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(Cours $cours): self
    {
        $this->cours = $cours;

        // set the owning side of the relation if necessary
        if ($cours->getMatiere() !== $this) {
            $cours->setMatiere($this);
        }

        return $this;
    }

    /**
     * @return Collection|Annale[]
     */
    public function getAnnales(): Collection
    {
        return $this->annales;
    }

    public function addAnnale(Annale $annale): self
    {
        if (!$this->annales->contains($annale)) {
            $this->annales[] = $annale;
            $annale->setMatiere($this);
        }

        return $this;
    }

    public function removeAnnale(Annale $annale): self
    {
        if ($this->annales->contains($annale)) {
            $this->annales->removeElement($annale);
            // set the owning side to null (unless already changed)
            if ($annale->getMatiere() === $this) {
                $annale->setMatiere(null);
            }
        }

        return $this;
    }
}
