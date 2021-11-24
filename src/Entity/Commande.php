<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="fk_cmdempl", columns={"employe"}), @ORM\Index(name="fk_cmdtable", columns={"num_table"})})
 * @ORM\Entity
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cmd", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCmd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_cmd", type="datetime", nullable=false)
     */
    private $dateCmd;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_tot", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixTot;

    /**
     * @var \Employe
     *
     * @ORM\ManyToOne(targetEntity="Employe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employe", referencedColumnName="id")
     * })
     */
    private $employe;

    /**
     * @var \Tables
     *
     * @ORM\ManyToOne(targetEntity="Tables")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="num_table", referencedColumnName="num_table")
     * })
     */
    private $numTable;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Produit", mappedBy="idCmd")
     */
    private $idProd;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idProd = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdCmd(): ?int
    {
        return $this->idCmd;
    }

    public function getDateCmd(): ?\DateTimeInterface
    {
        return $this->dateCmd;
    }

    public function setDateCmd(\DateTimeInterface $dateCmd): self
    {
        $this->dateCmd = $dateCmd;

        return $this;
    }

    public function getPrixTot(): ?float
    {
        return $this->prixTot;
    }

    public function setPrixTot(float $prixTot): self
    {
        $this->prixTot = $prixTot;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

        return $this;
    }

    public function getNumTable(): ?Tables
    {
        return $this->numTable;
    }

    public function setNumTable(?Tables $numTable): self
    {
        $this->numTable = $numTable;

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getIdProd(): Collection
    {
        return $this->idProd;
    }

    public function addIdProd(Produit $idProd): self
    {
        if (!$this->idProd->contains($idProd)) {
            $this->idProd[] = $idProd;
            $idProd->addIdCmd($this);
        }

        return $this;
    }

    public function removeIdProd(Produit $idProd): self
    {
        if ($this->idProd->removeElement($idProd)) {
            $idProd->removeIdCmd($this);
        }

        return $this;
    }

}
