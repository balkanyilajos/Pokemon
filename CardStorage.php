<?php

require_once 'Storage.php';

class CardStorage extends Storage {
    public function __construct() {
        parent::__construct(new JsonIO('data/json/pokemonCard.json'));
    }
}