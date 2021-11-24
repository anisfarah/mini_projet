<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit", indexes={@ORM\Index(name="fk_prodSous", columns={"sous_categorie"})})
 * @ORM\Entity
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_produit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProduit;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_prod", type="string", length=80, nullable=false)
     */
    private $nomProd;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=80, nullable=false)
     */
    private $image;

    /**
     * @var float
     *
     * @ORM\Column(name="pu", type="float", precision=10, scale=0, nullable=false)
     */
    private $pu;

    /**
     * @var float
     *
     * @ORM\Column(name="remise", type="float", precision=10, scale=0, nullable=false)
     */
    private $remise;

    /**
     * @var \SousCategorie
     *
     * @ORM\ManyToOne(targetEntity="SousCategorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sous_categorie", referencedColumnName="id_sousCat")
     * })
     */
    private $sousCategorie;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Commande", inversedBy="idProd")
     * @ORM\JoinTable(name="details_cmd",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_prod", referencedColumnName="id_produit")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_cmd", referencedColumnName="id_cmd")
     *   }
     * )
     */
    private $idCmd;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idCmd = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function getNomProd(): ?string
    {
        return $this->nomProd;
    }

    public function setNomProd(string $nomProd): self
    {
        $this->nomProd = $nomProd;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPu(): ?float
    {
        return $this->pu;
    }

    public function setPu(float $pu): self
    {
        $this->pu = $pu;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(float $remise): self
    {
        $this->remise = $remise;

        return $this;
    }

    public function getSousCategorie(): ?SousCategorie
    {
        return $this->sousCategorie;
    }

    public function setSousCategorie(?SousCategorie $sousCategorie): self
    {
        $this->sousCategorie = $sousCategorie;

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getIdCmd(): Collection
    {
        return $this->idCmd;
    }

    public function addIdCmd(Commande $idCmd): self
    {
        if (!$this->idCmd->contains($idCmd)) {
            $this->idCmd[] = $idCmd;
        }

        return $this;
    }

    public function removeIdCmd(Commande $idCmd): self
    {
        $this->idCmd->removeElement($idCmd);

        return $this;
    }

}
