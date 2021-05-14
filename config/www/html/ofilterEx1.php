<?php

class Field{
    protected function is_empty()
    {return empty($this->value);}
    public function is_valid(){}
}
class DNINumbers extends Field{
    public ?int $value=null;
    function __construct()
    {
        $this->value=$_POST['DNI']?$_POST['DNI']:null;
    }

    public function is_valid (): bool
    {
        return !$this->is_empty() and is_int($this->value);
    }
}

class DNILletra extends Field{
    function __construct()
    {
        $this->value=$_POST['DNI']?$_POST['DNI']:null;
    }

    public function is_valid (){
        return !$this->is_empty();
    }
}


class Nom extends Field{
    public ?string $value=null;
    public function is_valid (){
        return !$this->is_empty() && is_string($this->value);
    }
    function __construct()
    {
        $this->value=$_POST['DNI']?$_POST['DNI']:null;
    }
}
class Cognom extends Field{
    function __construct()
    {
        $this->value=$_POST['DNI']?$_POST['DNI']:null;
    }

    public function is_valid (){
        return !$this->is_empty();
    }
}

class CodiCicle extends Field{
    function __construct()
    {
        $this->value=$_POST['DNI']?$_POST['DNI']:null;
    }

    public function is_valid (){
        return !$this->is_empty();
    }
}


class Fields{
    public Field $DNI;
    public Field $Nom;
    public Field $Lletra;
    public Field $Cognoms;
    public Field $CodiCicle;

    function __construct()
    {
        $this->DNI=new DNI();
        $this->Nom=new Nom();
        $this->Lletra=new Lletra();
        $this->Cognoms=new Cognom();
        $this->CodiCicle=new CodiCicle();
    }
}

$t=new Fields();
echo $t->Nom->is_valid();






?>