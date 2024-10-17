<?php

namespace App\Service;

class CardNameGenerator
{
    private $adjectives = ['Féroce', 'Majestueux', 'Sauvage', 'Éthéré', 'Mystique'];
    private $nouns = ['Dragon', 'Griffon', 'Chimère', 'Hippogriffe', 'Manticore', 'Hydre', 'Sphinx'];

    public function generateRandomName(): string
    {
        $adjective = $this->adjectives[array_rand($this->adjectives)];
        $noun = $this->nouns[array_rand($this->nouns)];
        return $adjective . ' ' . $noun;
    }
}
