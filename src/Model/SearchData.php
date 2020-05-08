<?php

namespace App\Model;

use App\Entity\Category;
use Doctrine\Common\Collections\Collection;


class SearchData
{
   
    /**
     * 
     */
    private $q;

    /**
     * 
     */
    private $categories = [];

    
    private $min;

    
    private $max;

    public function getQ(): ?string
    {
        return $this->q;
    }

    public function setQ(?string $q): self
    {
        $this->q = $q;

        return $this;
    }

    /**
     * @return Array|Category[]
     */
    public function getCategories(): ?Array
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!in_array($category, $this->categories)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

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

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function setMax(?int $max): self
    {
        $this->max = $max;

        return $this;
    }
}
