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

//移動後台中間部分後(70-93行) 置於 back 改名 title 寫後台include檔

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
to("../back.php?do=total"); //注意是路徑兩點

//完成頁尾版權管理 與相關api 注意input類型改text 
<?=$Bottom->find(1)['bottom'];?> 
<?php //api
$Total->save(['id'=>1,'total'=>$_POST['total']]);
to("../back.php?do=total");