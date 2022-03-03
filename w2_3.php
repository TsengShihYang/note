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
$total=$News->math('count','*');
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

