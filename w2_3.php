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
} }  ?>
//問卷選項 

<form action="api/que.php" method="post">
    <div  style="display: flex;">
        <div class="clo">問卷名稱</div>
        <div>
        <input type="text" name="subject" >
        </div>
    </div>
    <div class="clo" id="opt"> 
        <div>
     <span>選項</span><input type="text" name="options[]" >
     <input type="button" onclick="more()" value="更多">
        </div>
    </div>
    <div>
        <input type="submit" value="新增"> | 
        <input type="reset" value="清空">
    </div>
</form>
</fieldset>
<script>
    function more(){
        let opt=`
        <div><span>選項</span><input type="text" name="options[]"></div>
        `
    $("#opt").prepend(opt);
    }
</script>
//新增 api que.php
<?php
$subject=$_POST['subject'];
$Que->save(['text'=>$subject,'parent'=> 0 , 'count'=> 0]);
$parent_id=$Que->math('max','id');

foreach($_POST['options'] as $opt){
    $Que->save(['text'=>$opt,'parent'=>$parent_id,'count'=> 0]);
}
to("../back.php?do=que");  ?>

