<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Kategoria;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduktRepository")
 */
class Produkt
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=255)
     */
    private $nazwa;

    /**
     * @ORM\Column(type="integer")
     */
    private $cena;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Kategoria", inversedBy="produkty")
     */
    private $kategoria;

    // ------------------------------------

    public function getId(): ?int {
        return $this->id;
    }

    public function getNazwa(): ?string {
        return $this->nazwa;
    }

    public function getCena(): ?int {
        return $this->cena;
    }

    public function getKategoria(): Kategoria {
        return $this->kategoria;
    }

    public function setNazwa(string $nazwa): void {
        $this->nazwa = $nazwa;
    }

    public function setCena(int $cena): void {
        $this->cena = $cena;
    }

    public function setKategoria(Kategoria $kategoria): void {
        $this->kategoria = $kategoria;
    }
}
