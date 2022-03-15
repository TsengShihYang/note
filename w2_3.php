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
//後台 que 問卷選項 

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

//front 新增 que vote result 3檔案  
//首先 que部分 須注意'結果'與'參與投票'都要給 $row['id']
<table class="tab">
    <tr>
        <td width="10%">編號</td>
        <td width="50%">問卷題目</td>
        <td width="15%">投票總數</td>
        <td width="10%">結果</td>
        <td>狀態</td>
    </tr>
    <?php   
    $ques=$Que->all(['parent'=>0]);
    foreach($ques as $key =>$row){      ?>
    <tr>
        <td><?=$key+1;?></td>
        <td><?=$row['text'] ;?></td>
        <td><?=$row['count'] ;?></td>
        <td> <a href="?do=result&id=<?=$row['id'];?>
        ">結果</a></td>
        <td>
<?php     if(isset($_SESSION['login'])){
            echo "<a href='?do=vote&id={$row['id']}'>";
            echo "參與投票";
            echo "</a>";
            }else{    
            echo "請先登入";  }     ?> 
        </td>
    </tr>
<?php  }  ?>
</table>
//front 的 vote.php
<?php 
$subject=$Que->find($_GET['id']); ?>

<fieldset>
    <legend>目前位置:首頁 > 問卷調查 > <?=$subject['text'];?></legend>
    <h3><?=$subject['text'];?></h3>
<form action="api/vote.php" method="post">
<?php
$options=$Que->all(['parent'=>$_GET['id']]);
foreach($options as $key => $opt){
?>
 <p><input type="radio" name="opt" value="<?=$opt['id'];?>">
 <?=$opt['text'];?> </p>
<?php    
}
?>
<div class="ct"> <input type="submit" value="我要投票"></div>
</form>
</fieldset>

//api 的 vote
<?php include_once "../base.php";

$opt=$Que->find($_POST['opt']);
$opt['count']++;
$subject=$Que->find($opt['parent']);
$subject['count']++;
$Que->save($opt);
$Que->save($subject);

to("../index.php?do=result");  ?>

//front 的 result ,把 vote 拷貝過來改, 注意百分比外面加括號
<?php 
$subject=$Que->find($_GET['id']); ?>

<fieldset>
    <legend>目前位置:首頁 > 問卷調查 > <?=$subject['text'];?></legend>
    <h3><?=$subject['text'];?></h3>
<?php
$options=$Que->all(['parent'=>$_GET['id']]);
foreach($options as $key => $opt){
    $div=($subject['count'] == 0)?1:$subject['count'];
    $rate=round($opt['count']/$div,2);
?>
 <div style="display:flex">
 <div style="width:40%"><?=$opt['text'];?> </div> 
 <div style="height:25px;background:#ccc;width:<?=40*$rate;?>%"></div>
 <div><?=$opt['count'];?>票(<?=$rate*100;?>%)</div>
</div>
<?php    
}
?>
<div class="ct"> <button onclick="location.href='?do=que'">返回</button> </div>
</fieldset>

