<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Produkt;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KategoriaRepository")
 */
class Kategoria
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nazwa;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Produkt", mappedBy="kategoria")
     */
    private $produkty;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNazwa(): ?string
    {
        return $this->nazwa;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProdukty(): Collection
    {
        return $this->produkty;
    }

    public function setNazwa(string $nazwa): void
    {
        $this->nazwa = $nazwa;
    }
}
