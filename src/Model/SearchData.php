<?php
namespace App\Model;

use App\Entity\Category;

class SearchData
{
   
   private $q;

   
   private $categories;

  
   private $max;

   
   private $min;

   public function getId(): ?int
   {
       return $this->id;
   }

   public function getQ(): ?string
   {
       return $this->q;
   }

   public function setQ(?string $q): self
   {
       $this->q = $q;

       return $this;
   }

   public function getCategories(): ?Category
   {
       return $this->categories;
   }

   public function setCategories(?Category $categories): self
   {
       $this->categories = $categories;

       return $this;
   }

   public function getMax(): ?int
   {
       return $this->max;
   }

   public function setMax(?int $max): self
   {
       $this->max = $max;

       return $this;
   }

   public function getMin(): ?int
   {
       return $this->min;
   }

   public function setMin(?int $min): self
   {
       $this->min = $min;

       return $this;
   }
    

}