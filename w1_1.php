<?php
//切版 改路徑 改檔名  改css 圖片路徑 加(../)
//將 login news 移至front  並掐頭去尾
//login(留下約32起-42行附近) news(留下約32起-42行附近)
//index 剪下中間移去 front(約32起-到81行) 改名 home
//寫前台include檔

$do=$_GET['do']??'home';
$file="./front/".$do.".php";
if(file_exists($file)){
    include $file;
}else{ include "./front/home.php";}

//移動後台中間部分後(77-92行) 置於 back 改名 title 寫後台include檔

$do=$_GET['do']??'title';
$file="./back/".$do.".php";
if(file_exists($file)){
    include $file;
}else{ include "./back/title.php";}

//建資料表 建立title後可直接複製4份(ad,image,mvim,news)在total bottom admin 各塞一筆資料

//建立進站總人數 寫total session
if(!isset($_SESSION['total'])){
    $total=$Total->find(1);
    $total['total']++;
    $Total->save($total);
    $_SESSION['total']=$total['total'];
}  ?>

//完成進站總人數後台管理 與相關api
//把title拷貝成total修改 注意排版 還有form的action路徑

<?=$Total->find(1)['total'];?> 
<?php //api
$Total->save(['id'=>1,'total'=>$_POST['total']]);
to("../back.php?do=total");   ?>//注意是路徑兩點

//完成頁尾版權管理 與相關api 注意input類型改text 
<?=$Bottom->find(1)['bottom'];?> 
<?php //api
$Total->save(['id'=>1,'total'=>$_POST['total']]);
to("../back.php?do=total");    ?>

//建立後台 title.php 為基礎 之後再複製至其他

//api 程式 add.php
<?php include  "../base.php";

if(!empty($_FILES['img']['tmp_name'])){
    move_uploaded_file($_FILES['img']['tmp_name'],"../img/".$_FILES['img']['name']);
    $data['img']=$_FILES['img']['name'];
}else{
    if($DB->table!='admin' && $DB->table!='menu'){
        $data['img']='';
    }
}
switch($DB->table){
    case "title":
        $data['text']=$_POST['text'];
        $data['sh']=0;
    break;
    case "admin":
        $data['acc']=$_POST['acc'];
        $data['pw']=$_POST['pw'];
    break;
    case "menu":
        $data['name']=$_POST['name'];
        $data['href']=$_POST['href'];
        $data['sh']=1;
        $data['parent']=0;
    break;
    default:
        $data['text']=$_POST['text']??'';
        $data['sh']=1;
    break;
}
$DB->save($data);
to("../back.php?do=".$DB->table)
?>
// api edit.php
<?php include_once "../base.php";
foreach($_POST['id'] as $key => $id){
    if(isset($_POST['del']) && in_array($id,$_POST['del'])){
        $DB->del($id);
    }else{
        $data=$DB->find($id);
        switch($DB->table){
            case "title":
                $data['text']=$_POST['text'][$key];
                $data['sh']=($_POST['sh']==$id)?1:0;
            break;
            case "admin":
                $data['acc']=$_POST['acc'][$key];
                $data['pw']=$_POST['pw'][$key];
            break;
            case "menu":
                $data['name']=$_POST['name'][$key];
                $data['href']=$_POST['href'][$key];
                $data['sh']=(isset($_POST['sh']) && in_array($id,$_POST['sh']))?1:0;
            break;
            default:   //ad,,news,image,mvim
                $data['text']=isset($_POST['text'])?$_POST['text'][$key]:'';
                $data['sh']=(isset($_POST['sh']) && in_array($id,$_POST['sh']))?1:0;
            break;
        }
        $DB->save($data);
    }
}
to("../back.php?do=".$DB->table);   ?>

// api upload.php  add拷貝過來改
<?php include_once "../base.php";

if(!empty($_FILES['img']['tmp_name'])){
    move_uploaded_file($_FILES['img']['tmp_name'],"../img/".$_FILES['img']['name']);
    $data=$DB->find($_POST['id']);
    $data['img']=$_FILES['img']['name'];
    $DB->save($data);
}

to("../back.php?do=".$DB->table);   ?>

//api submenu.php
<?php  include_once "../base.php";

if(isset($_POST['id'])){
    foreach($_POST['id'] as $key=>$id){
        if(isset($_POST['del']) && in_array($id,$_POST['del'])){
            $Menu->del($id);
        }else{
            $sub=$Menu->find($id);
            $sub['name']=$_POST['name'][$key];
            $sub['href']=$_POST['href'][$key];
            $Menu->save($sub);
        }     }     }

if(isset($_POST['name2'])){
    foreach($_POST['name2'] as $key=>$name){
        if($name!=''){
            $Menu->save(['name'=>$name,
                         'href'=>$_POST['href2'][$key],
                         'sh'=>1,
                         'parent'=>$_GET['main']]);
        }      }     }

to("../back.php?do=".$Menu->table); ?>