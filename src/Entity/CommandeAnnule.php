<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeAnnule
 *
 * @ORM\Table(name="commande_annule")
 * @ORM\Entity
 */
class CommandeAnnule
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_commande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCommande;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_annulation", type="datetime", nullable=false)
     */
    private $dateAnnulation;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_tot", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixTot;

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function getDateAnnulation(): ?\DateTimeInterface
    {
        return $this->dateAnnulation;
    }

    public function setDateAnnulation(\DateTimeInterface $dateAnnulation): self
    {
        $this->dateAnnulation = $dateAnnulation;

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


}
