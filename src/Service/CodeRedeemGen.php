<?php

namespace App\Service;

Class CodeRedeemGen {
    public function GenerateCodeRedeem(int $length): string
    {
        // generation des caractere automatiquement via code ascii
        //maj
        $uppercaseL = $this->GenerateCharacterCharCodeRange([65,90]);
        // nombre
        $numbers = $this->GenerateCharacterCharCodeRange([48,57]);

        $allCharacters = array_merge($uppercaseL, $numbers);

        $isArrayShuffled = shuffle($allCharacters);

        if(!$isArrayShuffled){
            throw new \LogicException("La génération d'un code aléatoire a échoué, veuillez réessayer.");
        }

        return implode('',$passwordArray = array_slice($allCharacters, 0, $length));

    }

    private function GenerateCharacterCharCodeRange(array $range): array
    {
        if (count($range) === 2) {
            return range(chr($range[0]), chr($range[1]));
        } else {
            // 0 == premier code ascii 1 == dernier code ascii
            return array_merge(...array_map(fn($range) => range(chr($range[0]), chr($range[1])), array_chunk($range, 2)));
        }
    }
}