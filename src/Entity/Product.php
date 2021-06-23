<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $uid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageId;

    /**
     * @ORM\Column(type="decimal")
     */
    private $price;

    /**
     * @ORM\Column(type="decimal")
     */
    private $finalPrice;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getImageId(): ?string
    {
        return $this->imageId;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getFinalPrice(): ?float
    {
        return $this->finalPrice;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setUid($in)
    {
        $this->uid=$in;
    }

    public function setCode($in)
    {
        $this->code=$in;
    }

    public function setTitle($in)
    {
        $this->title=$in;
    }

    public function setImageId($in)
    {
        $this->imageId=$in;
    }

    public function setPrice($in)
    {
        $this->price=$in;
    }

    public function setFinalPrice($in)
    {
        $this->finalPrice=$in;
    }

    public function setStatus($in)
    {
        $this->status=$in;
    }
}