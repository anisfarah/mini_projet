<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tables
 *
 * @ORM\Table(name="tables")
 * @ORM\Entity
 */
class Tables
{
    /**
     * @var int
     *
     * @ORM\Column(name="num_table", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numTable;

    /**
     * @var string
     *
     * @ORM\Column(name="disponibilite", type="string", length=80, nullable=false)
     */
    private $disponibilite;

    public function getNumTable(): ?int
    {
        return $this->numTable;
    }

    public function getDisponibilite(): ?string
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(string $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }


}
