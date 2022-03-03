//後臺帳號管理 form+table
<?php
    $user=$User->all();
    foreach($users as $key=>$user){
    ?>
        <tr>
            <td><?=$user['acc'];?></td>
            <td><?=str_repeat("*",mb_strlen($user['pw']));?></td>
            <td><input type="checkbox" name="del[]" value="<?=$user['id'];?>"></td>
        </tr>
    <?php
    }
    ?>
//至api 寫 del.php
<?php include_once "../base.php";
if(isset($_POST['del'])){
    foreach($_POST['del'] as $id)
    $User->del($id);
} 
to("../back.php?do=admin"); ?>

//自front reg拷貝"會員註冊"(含script) 改成"新增會員"
改h1 刪多餘的</fieldset>
("註冊完成,歡迎加入") 後 
location.reload();

//匯入8篇文章入資料庫  寫分頁程式 並撈出全部文章 
<?php
$total=$News->math('count','*',['sh'=>1]);
$div=5;
$pages=ceil($total/$div);
$now=$_GET['p']??1;
$start=($now-1)*$div;

$news=$News->all(['sh'=>1],"limit $start,$div");
foreach($news as $key => $row){
?>
    <tr>
        <td class="clo"><?=$row['title'] ;?></td>
        <td class="switch">
        <div class="short"><?=mb_substr($row['text'],0,20) ;?>...</div>
        <div class="full" style="display: none;"><?=nl2br($row['text']) ;?></div>
        </td>
        <td></td>
    </tr>
<?php
}
?>
</table>
//在此寫分頁 並放大當前頁數字
<div> <?php
if(($now-1)>0){
    $prev=$now-1;
    echo "<a href='index.php?do=news&p=$prev'>";
    echo " < ";
    echo " </a>";
}
for($i=1;$i<=$pages;$i++){
    $font=($now==$i)?'24px':'16px';
    echo "<a href='index.php?do=news&p=$i' style='font-size:$font '>";
    echo $i;
    echo "</a>";
}
if(($now+1)<=$pages){
    $next=$now+1;
    echo "<a href='index.php?do=news&p=$next'>";
    echo " > ";
    echo " </a>";
}
?></div>
</fieldset>
//切換顯示文章 短版長版
<script>
$(".switch").on("click",function(){
    $(this).parent().find(".short,.full").toggle()
})
</script>
// news 第三行 td 放 按讚顯示
<?php
if(isset($_SESSION['login'])){
    echo "<a class='g' data-type='2'>讚</a>";
}
?>
//script 註冊一個按讚function 
<script>
$(".g").on("click",function(){
        let type=$(this).data('type')
        switch(type){
            case 1:
                $(this).text("讚")
                $(this).data('type',2)
                break;
                case 2:
                $(this).text("收回讚")
                $(this).data('type',1)
                break;
        }
})</script>
//後臺文章管理  將前台的 news檔案拷過來
//多設一個 $chk 與 hidden id 
<?php
$news=$News->all("limit $start,$div");
foreach($news as $key => $row){
    $chk=($row['sh']==1)?"checked":"";
?>
    <tr>
        <td class="clo"><?=$start+1+$key;?></td>
        <td class="clo"><?=$row['title'];?></td>
        <td>
    <input type="checkbox" name="sh[]" value="<?=$row['id'];?>" <?=$chk;?>>
        </td>        
        <td>
    <input type="checkbox" name="del[]" value="<?=$row['id'];?>">
    <input type="hidden" name="id[]" value="<?=$row['id'];?>">
        </td>
    </tr>
<?php
}
?>
//api 新增 news_admin.php
<?php include_once "../base.php";
foreach($_POST['id'] as $id){
    if(isset($_POST['del']) && in_array($id,$_POST['del'])){
    $News->del($id);
}else{
    $news=$News->find($id);
    $news['sh']=(isset($_POST['del']) && in_array($id,$_POST['del']))?1:0;
    $News->save($news);
} }
