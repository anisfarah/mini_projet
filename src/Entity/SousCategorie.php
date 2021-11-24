<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SousCategorie
 *
 * @ORM\Table(name="sous_categorie", indexes={@ORM\Index(name="fk_sousCat", columns={"categorie"})})
 * @ORM\Entity
 */
class SousCategorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_sousCat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSouscat;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_sousCat", type="string", length=80, nullable=false)
     */
    private $nomSouscat;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie", referencedColumnName="id_categorie")
     * })
     */
    private $categorie;

    public function getIdSouscat(): ?int
    {
        return $this->idSouscat;
    }

    public function getNomSouscat(): ?string
    {
        return $this->nomSouscat;
    }

    public function setNomSouscat(string $nomSouscat): self
    {
        $this->nomSouscat = $nomSouscat;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }


}
