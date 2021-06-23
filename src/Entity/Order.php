<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 */
/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=20)
     */
    private $orderID;

    /**
     * @ORM\Column(type="decimal")
     */
    private $total;

    /**
     * @ORM\Column(type="string", length=24)
     */
    private $orderDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function getorderID(): ?string
    {
        return $this->orderID;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function getOrderDate(): ?string
    {
        return $this->orderDate;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setorderID($in)
    {
        $this->orderID=$in;
    }

    public function setTotal($in)
    {
        $this->total=$in;
    }

    public function setOrderDate($in)
    {
        $this->orderDate=strtotime($in);
    }

    public function setStatus($in)
    {
        $this->status=$in;
    }
}