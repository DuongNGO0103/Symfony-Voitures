<?php

namespace App\Entity;
class RechercheVoiture{
    private $minAnnee;
    private $maxAnnee;

    public function getMinAnnee(){
        return $this->minAnnee;
    }

    public function getMaxAnnee(){
        return $this->maxAnnee;
    }

    public function setMinAnnee(int $anne){
        $this->minAnnee=$anne;
        return $this;
    }
    public function setMaxAnnee(int $anne){
        $this->maxAnnee=$anne;
        return $this;
    }
}