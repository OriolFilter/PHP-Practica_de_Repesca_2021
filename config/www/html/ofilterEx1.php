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
        return !$this->is_empty() and is_int($this->value);
    }
}

class Image extends Field{
//https://www.w3schools.com/php/php_file_upload.asp
    /**
     * @var mixed|null
     */

    function __construct()
    {
        $this->value=isset($_FILES["IMAGE"])?$_FILES["IMAGE"]:null;
    }

    public function is_valid (){
         if ($this->is_empty()){
             echo "emptpy";
             return false;
         }
        $check = getimagesize($this->value);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

    }
}

class DNINumber extends Field{
    public ?string $value=null;
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
    public Image $Imatge;

    function __construct()
    {
        $this->DNI=new DNI();
        $this->Nom=new Nom();
        $this->Cognoms=new Cognom();
        $this->CodiCicle=new CodiCicle();
        $this->Imatge=new Image();
    }
    function its_empty(){
        return ($this->DNI->Number->is_empty() && $this->DNI->Lletra->is_empty() && $this->Cognoms->is_empty() && $this->CodiCicle->is_empty() && $this->Imatge->is_empty())?true:false;
    }
}

class Bodyguard {
    # Html manager
    public $dbconn;
    public bool $success = False;
    public Fields $fields;
    function __construct()
    {
        $this->dbconn=pg_connect("host='db' port=5432 dbname='institut' user=test password=test");

    }
    public function return_body(){
        $this->fields=new Fields();
//        echo $this->fields->CodiCicle->is_valid();
        return ('
            <!DOCTYPE html>
            <html>
            <body>
            <h1>Form ex1</h1>

            <form action="/ofilterEx1.php" method="get" target="_blank">
              <label for="NOM">Nom</label>
              <input type="text" id="NOM" name="NOM"><br><br>
              <label for="COGNOM">Cognom</label>
              <input type="text" id="COGNOM" name="COGNOM"><br><br>
              <label for="DNI">DNI</label>
              <input type="text" id="DNI" name="DNI"><br><br>
              <label for="LLETRA">Cognom</label>
              <input type="text" id="LLETRA" name="LLETRA"><br><br>
              <label for="CODICICLE">Image:</label>
              <input type="file" name="CODICICLE" id="CODICICLE"><br><br>
              <input type="submit" value="Submit">
            </form>'.
            ($this->success?'<p style="color: lawngreen">Succeed!</p>':'').
            ($this->fields->its_empty()==false and $this->success==true?'<p style="color: red">Error!</p>':'').
            '</body>
            </html>');
    }
}

# Main
//$f=new Fields();
//echo $f->its_empty();
$page = new Bodyguard();
echo $page->return_body();
echo $page->fields->Imatge->is_valid();
//echo $page->fields->Nom->is_empty()==true?112:023
;
//echo 2;


?>