<?php


namespace App\Entity\Providers;



interface ProductProviderInterface
{
    public function normalizeToJson() : string;
}