<?php

class Field{
    public function is_empty():bool{
        return empty($this->value);
    }
    function is_valid(){}
}

class Nom extends Field{
    public ?string $value=null;
    public function is_valid (){
        return !$this->is_empty() && is_string($this->value);
    }
    function __construct()
    {
        $this->value=array_key_exists('NOM', $_POST)?$_POST['NOM']:null;
    }
}
class Cognom extends Field{
    public ?string $value=null;
    function __construct()
    {
        $this->value=array_key_exists('COGNOM', $_POST)?$_POST['COGNOM']:null;
    }

    public function is_valid (){
        return !$this->is_empty();
    }
}
class Email extends Field{
    public ?string $value=null;
    public function is_valid (){
        $email = @filter_var($this->value, FILTER_VALIDATE_EMAIL);
        if ($email && preg_match("/^[a-zA-Z0-9.!#$%&'*+=?^_`{|}~-]+@[a-zA-Z10-9-]+\.+[a-zA-Z0-9-]+$/", $email, $email)) {
            return true;
        }
    }
    function __construct()
    {
        $this->value=array_key_exists('EMAIL', $_POST)?$_POST['EMAIL']:null;
    }
}
class CodiCicle extends Field{
    public $value;
    function __construct()
    {
        $this->value=array_key_exists('CODICICLE', $_POST)?$_POST['CODICICLE']:null;
    }

    public function is_valid (){
//        echo "Aaaa";
//        echo $this->;
        return !$this->is_empty() && is_int(intval($this->value));
    }
}

class Image extends Field{
//https://www.w3schools.com/php/php_file_upload.asp
    public $value;
    function __construct()
    {
        $this->value=(array_key_exists('image', $_FILES)&& array_key_exists('error', $_FILES['image']))?$_FILES["image"]:null;
    }
    public function is_valid (){
         if ($this->is_empty()){
             echo "emptpy";
             return false;
         }
        $check = getimagesize($this->value["tmp_name"]);
        if($check !== false) {
            return true;
        } else {
            return false;
        }
    }
    public function is_empty():bool{
        return empty($this->value['name']);
    }
}

class DNINumber extends Field{
    public ?string $value=null;
    function __construct()
    {
        $this->value=isset($_POST['DNI']) ?$_POST['DNI']:null;
    }

    public function is_valid (): bool
    {
        return !$this->is_empty() && is_int($this->value);
    }
}
class DNILletra extends Field
{
    public ?string $value=null;

    function __construct()
    {
        $this->value = isset($_POST['LLETRA']) ? $_POST['LLETRA'] : null;
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
    public Email $Email;
    public CodiCicle $CodiCicle;
    public Image $Imatge;

    function __construct()
    {
        $this->DNI=new DNI();
        $this->Nom=new Nom();
        $this->Cognoms=new Cognom();
        $this->Email=new Email();
        $this->CodiCicle=new CodiCicle();
        $this->Imatge=new Image();
    }
    function its_empty(){
        return ($this->DNI->Number->is_empty() && $this->DNI->Lletra->is_empty() && $this->Cognoms->is_empty() && $this->CodiCicle->is_empty() && $this->Imatge->is_empty() && $this->Email->is_empty())?true:false;
    }
    function its_valid(){
        return ($this->DNI->is_valid() && $this->Cognoms->is_valid() && $this->CodiCicle->is_valid() && $this->Imatge->is_valid() && $this->Email->is_valid())?true:false;
    }
}
class Bodyguard {
    # Html manager
    public $dbconn;
    public string $status_code = '2'; # 0 Success , 1 Error fields, 2 Empty fields, 3 unknown error, -1 fields check and going to insert in database
    public Fields $fields;
    function __construct()
    {
        $this->fields=new Fields();
        $this->dbconn=pg_connect("host='db' port=5432 dbname='institut' user=test password=test");
        $this->check_fields();
        $img_dir='/tmp/img';
        if ($this->status_code=='-1') {
            # Move image

            $img_future_path="${img_dir}/".basename($this->fields->Imatge->value['tmp_name']).'.png';
            copy($this->fields->Imatge->value['tmp_name'],$img_future_path);
            ;$result = pg_prepare($this->dbconn, "add_alumne", 'insert into alumnes (DNI, LLETRA, NOM, COGNOMS, MAIL, CODICICLE, FOTO) values ($1,$2,$3,$4,$5,$6,$7)');
            ;$res=pg_get_result($this->dbconn);
            ;$result = pg_send_execute($this->dbconn, "add_alumne",array($this->fields->DNI->Number->value,$this->fields->DNI->Lletra->value,$this->fields->Nom->value,$this->fields->Cognoms->value,$this->fields->Email->value,$this->fields->CodiCicle->value,$img_future_path));
            ;$err=pg_last_notice($this->dbconn);
            ;$pg_result = pg_get_result($this->dbconn);
            if ($err) {$this->status_code='3';
            }
            else{
                $this->status_code='0';
            }

        }
    }
    public function check_fields(){
        if ($this->fields->its_empty()) {
            $this->status_code='2';
        } elseif ($this->fields->its_valid()) {
            $this->status_code='-1';
        } else {
            $this->status_code='1';
        }
    }
    public function return_body(){
        $cursos_array = [];
        $result = pg_prepare($this->dbconn, "sel_cursos_q", 'select codicicle, abreviatura from cursos;');
        ;$res=pg_get_result($this->dbconn);
        ;$result = pg_send_execute($this->dbconn, "sel_cursos_q",array());
        ;$err=pg_last_notice($this->dbconn);
        ;$res=pg_get_result($this->dbconn);
        $codicicle = pg_fetch_all_columns($res,0); //codicicle
        $abreviatura = pg_fetch_all_columns($res,1); //abreviatura
        ;$pg_result = pg_get_result($this->dbconn);
        foreach ($codicicle as $key=>$value) {
            $cursos_array[$codicicle[$key]] = $abreviatura[$key];
        }

        $codicicle_options = '';
        foreach ($cursos_array as $key=>$value) {
            $codicicle_options .= "<option value='${key}'>${cursos_array[$key]}</option>";
        }

        return ('
            <!DOCTYPE html>
            <html>
            <body>
            <h1>Form ex1</h1>
            <form action="/ofilterEx1.php" method="post" target="_blank" enctype="multipart/form-data">
              <label for="NOM">Nom</label>
              <input type="text" id="NOM" name="NOM"><br>'.
            ($this->status_code =='1' && $this->fields->Nom->is_empty()?'<p style="color: red">* This field needs to be filled</p>':'').
            ($this->status_code =='1' && !$this->fields->Nom->is_empty() && !$this->fields->Nom->is_valid()?'<p style="color: red">* The data is not valid</p>':'').
            '<br>
              <label for="COGNOM">Cognom</label>
              <input type="text" id="COGNOM" name="COGNOM"><br><br>'.
            ($this->status_code =='1' && $this->fields->Cognoms->is_empty()?'<p style="color: red">* This field needs to be filled</p>':'').
            ($this->status_code =='1' && !$this->fields->Cognoms->is_empty() && !$this->fields->Cognoms->is_valid()?'<p style="color: red">* The data is not valid</p>':'').
            '<br>
              <label for="EMAIL">Email</label>
              <input type="text" id="EMAIL" name="EMAIL"><br><br>'.
            ($this->status_code =='1' && $this->fields->Email->is_empty()?'<p style="color: red">* This field needs to be filled</p>':'').
            ($this->status_code =='1' && !$this->fields->Email->is_empty() && !$this->fields->Email->is_valid()?'<p style="color: red">* Email is not valid ( X@Y.Z )</p>':'').
            '<br>
              <label for="DNI">Nombres DNI</label>
              <input type="text" id="DNI" name="DNI"><br><br>'.
            ($this->status_code =='1' && $this->fields->DNI->Number->is_empty()?'<p style="color: red">* This field needs to be filled</p>':'').
            '<br>
              <label for="LLETRA">Lletra DNI</label>
              <input type="text" id="LLETRA" name="LLETRA"><br><br>'.
            ($this->status_code =='1' && $this->fields->DNI->Lletra->is_empty()?'<p style="color: red">* This field needs to be filled</p>':'').
            ($this->status_code =='1' && !$this->fields->DNI->Lletra->is_empty() && !$this->fields->DNI->Number->is_empty() && !$this->fields->DNI->is_valid()?'<p style="color: red">* The dni is not valid</p>':'').
            '<br>
              <label for="CODICICLE">Choose a cicle</label>
            <select id="CODICICLE" name="CODICICLE">'.
            "${codicicle_options}"
            .'</select><br><br>'.
            ($this->status_code =='1' && $this->fields->CodiCicle->is_empty()?'<p style="color: red">* This field needs to be filled</p>':'').
            ($this->status_code =='1' && !$this->fields->CodiCicle->is_empty() && !$this->fields->CodiCicle->is_valid()?'<p style="color: red">* The data is not valid</p>':'').
            '<br>
              <label for="image">Image:</label>
              <input type="file" name="image" id="image"><br><br>'.
            ($this->status_code =='1' && $this->fields->Imatge->is_empty()?'<p style="color: red">* This field needs to be filled</p>':'').
            ($this->status_code =='1' && !$this->fields->Imatge->is_empty() && !$this->fields->Imatge->is_valid()?'<p style="color: red">* The image is not a valid type</p>':'').
            '<br>
              <input type="submit" value="Submit">
            </form>'.
            ($this->status_code=='0'?'<p style="color: green">Success!</p>':'').
            ($this->status_code=='4'?'<p style="color: red">Unknown error!</p>':'').
            '</body>
            </html>');
    }
}

# Main
$page = new Bodyguard();
echo $page->return_body();
?>