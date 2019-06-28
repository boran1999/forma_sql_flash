<?php

include "flash.php";


class formclass{

    public $name;
    public $sname;
    public $email;
    public $phone;
    public $t;
    public $p;
    public $soglras = false;
    protected $_pdo;
    public $table='participants';

    public $topic = [
    1 => 'Бизнес_и_коммуникации',
    2 => 'Технологии',
    3 => 'Реклама',
  ];

   public $paym = [
    1 => 'WebMoney',
    2 => 'Яндекс.Деньги',
    3 => 'PayPal',
    4 => 'Кредитная_карта',
  ];

    protected $errors = [];

    public function has_errors(){
        return ! empty($this->errors);
    }



    public function data_insert(){
        if (!empty($_POST)) { 
            $this->name = isset($_POST['name']) ? trim($_POST['name']) : null; 
            $this->sname = isset($_POST['sname']) ? trim($_POST['sname']) : null; 
            $this->email = isset($_POST['email']) ? trim($_POST['email']) : null; 
            $this->phone = isset($_POST['phone']) ? trim($_POST['phone']) : null; 
            $this->t = isset($_POST['top']) ? trim($_POST['top']) : null; 
            $this->p = isset($_POST['paym']) ? trim($_POST['paym']) : null; 
            $this->soglras = isset($_POST['jel']) ? true : 0; 
        }
    }

    public function validate(){
        if (!empty($_POST)) { 
            if (empty($this->name))
            {
                $this->errors['name'] = 'Не введено имя';
                echo $this->errors['name']."<br>";
            }
            elseif (preg_match("/^[А-Я][а-я]+$/u", $this->name)==0) {
                $this->errors['reg_name']='Не корректно введено имя';
                echo $this->errors['reg_name'].'<br>';
            }
            if (empty($this->sname))
            {
                $this->errors['sname'] = 'Не введена фамилия';
                echo $this->errors['sname']."<br>";
            }
            elseif (preg_match("/^[А-Я][а-я]+$/u", $this->sname)==0) {
                $this->errors['reg_sname']='Не корректно введена фамилия';
                echo $this->errors['reg_sname'].'<br>';
            }

            if (empty($this->email))
            {
                $this->errors['email'] = 'Не введен email';
                echo $this->errors['email']."<br>";
            }
             elseif (preg_match("/^[a-zA-Zа-яА-Я0-9]+\@[a-z]+\.[a-zа-я]+$/u", $this->email)==0) {
                $this->errors['reg_email']='Не корректно введен email';
                echo $this->errors['reg_email'].'<br>';
            }

            if (empty($this->phone))
            {
                $this->errors['phone'] = 'Не введен телефон';
                echo $this->errors['phone']."<br>";
            }
             elseif (preg_match("/(^\+7\d{10}$|^\+7 \d{3} \d{3}-\d{2}-\d{2}$)/", $this->phone)==0) {
                $this->errors['reg_phone']='Не корректно введен телефон';
                echo $this->errors['reg_phone'].'<br>';
            }
            elseif(preg_match("/^\+7\d{10}$/",$this->phone)==1){
                $temp=substr($this->phone, 0,2)." ".substr($this->phone, 2,3)." ".substr($this->phone, 5,3)."-".substr($this->phone, 8,2)."-".substr($this->phone, 10,2);
                $this->phone=$temp;
            }


        }
        return ! $this->has_errors();
    }

    public function get_pdo(){
        if (empty($this->_pdo))
        {
            $this->_pdo = new PDO('mysql:host=localhost;dbname=myform','root',''); 

        }

        return $this->_pdo;
    }
     public function sqlsave()
    {
        if ($this->validate())
        {
            $sql = $this->get_pdo()->prepare('INSERT INTO `'.$this->table.'` (`name`,`sname`,`email`,`phone`,`topic`,`paym`,`soglras`, `created_at`) VALUES (?,?,?,?,?,?,?,?);');

            $sql->execute(array($this->name,$this->sname,$this->email,$this->phone,$this->t,$this->p,$this->soglras,date('Y-m-d-H-i-s')));
            $fl = new flash;
            $fl->set('registered');
            echo $fl->get();
            $fl->del();
            return $sql->rowCount() === 1;
        }

        return false;
    }

    public function sqlget(){
        $sql = $this->get_pdo()->prepare('SELECT * FROM `'.$this->table.'` WHERE `deleted_at` is ?;');
        $sql->execute(array(NULL));
        $objects = [];
        while ($object = $sql->fetchObject(static::class))
        {
            $str=$object->id."|".$object->name."|".$object->sname."|".$object->email."|".$object->phone."|".$this->topic[$object->topic]."|".$this->paym[$object->paym]."|".$object->soglras."|".$object->created_at;
            $res = preg_replace("/ /","", $str);
            echo "<input type='checkbox' name='f[]' value=".$res."><font color='blue'>".$str."</font><br>";
        }
    }

    public function sqldel(){
        if(empty($_POST['f'])){ 
                echo "<h2><font color='blue'>Вы ничего не выбрали!</font></h2>";
        } 
        else{
            $af=$_POST['f'];
            $arr=array();
            foreach ($af as $key) {
                $res=explode('|', $key);
                array_push($arr, $res[0]);
            }
            echo "<h2><font color='blue'>Данные файлы были успешно отмечены как удалённые</font></h2>";
            $n=count($af);
            for($i=0;$i<$n;$i++){
                echo $af[$i]."<br>"; 
            }
            foreach ($arr as $k ) { 
                $sql = $this->get_pdo()->prepare('UPDATE `'.$this->table.'` SET `deleted_at` = ? WHERE `id` = ?;');
                $sql->execute(array(date('Y-m-d-H-i-s'),$k)); 
            }
        }
    }
}
