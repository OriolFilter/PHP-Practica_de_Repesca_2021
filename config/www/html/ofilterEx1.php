<?php

class Field{
    public function is_empty():bool{return empty($this->value);}
    function is_valid(){}
}

class Nom extends Field{
    public ?string $value=null;
    public function is_valid (){
        return !$this->is_empty() && is_string($this->value);
    }
    function __construct()
    {
        $this->value=isset($_REQUEST['NOM'])?$_REQUEST['NOM']:null;
    }
}
class Cognom extends Field{
    function __construct()
    {
        $this->value=isset($_REQUEST['COGNOM'])?$_REQUEST['COGNOM']:null;
    }

    public function is_valid (){
        return !$this->is_empty();
    }
}
class CodiCicle extends Field{
    function __construct()
    {
        $this->value=isset($_REQUEST['CODICICLE'])?$_REQUEST['CODICICLE']:null;
    }

    public function is_valid (){
        return !$this->is_empty();
    }
}
class DNINumber extends Field{
    public ?int $value=null;
    function __construct()
    {
        $this->value=isset($_REQUEST['DNI']) ?$_REQUEST['DNI']:null;
    }

    public function is_valid (): bool
    {
        return !$this->is_empty() and is_int($this->value);
    }
}

class DNILletra extends Field
{
    public ?string $value=null;

    function __construct()
    {
        $this->value = isset($_REQUEST['LLETRA']) ? $_REQUEST['LLETRA'] : null;
    }

    public function is_valid():bool
    {
        return !$this->is_empty();
    }
}
class DNI
{
    public DNILletra $Lletra;
    public DNINumber $Number;
    function __construct()
    {
        $this->Lletra=new DNILletra();
        $this->Number=new DNINumber();
    }
    public function is_valid():bool{
        $valid='';
        if (substr("TRWAGMYFPDXBNJZSQVHLCKE", intval($this->Number->value)%23, 1) == $this->Lletra->value && strlen($this->Lletra->value) == 1 && strlen ($this->Number->value) == 8 ){
            $valid=true;
        }else{
            $valid=false;
        }
        return $valid;
    }
}

class Fields{
    public DNI $DNI;
    public Nom $Nom;
    public Cognom $Cognoms;
    public CodiCicle $CodiCicle;

    function __construct()
    {
        $this->DNI=new DNI();
        $this->Nom=new Nom();
        $this->Cognoms=new Cognom();
        $this->CodiCicle=new CodiCicle();
    }
    function its_empty(){
        if ($this->DNI->Number->is_empty() && $this->DNI->Lletra->is_empty() && $this->Cognoms->is_empty() && $this->CodiCicle->is_empty()){
            return true;
        } return false;

    }
}

class Bodyguard {
    public $dbconn = @pg_connect("host='db' port=5432 dbname='institut' user=test password=test");
}

# Main
$f=new Fields();
echo $f->its_empty();



?>