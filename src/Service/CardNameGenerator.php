<?php

namespace App\Service;

class CardNameGenerator
{
    public $adjectives = ['Féroce', 'Majestueux', 'Sauvage', 'Éthéré', 'Mystique'];
    public $animals = ['Dragon', 'Griffon', 'Chimère', 'Hippogriffe', 'Manticore', 'Hydre', 'Sphinx'];

    public function generateRandomName(): string
    {
        $adjective = $this->adjectives[array_rand($this->adjectives)];
        $animal = $this->animals[array_rand($this->animals)];
        return $animal . ' ' . $adjective;
    }
}