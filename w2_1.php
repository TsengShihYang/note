//切換頁面
<div class="">
    <?php
    $do = $_GET['do'] ?? 'home';
    $file = 'front/' . $do . ".php";
    if (file_exists($file)) {
        include $file;
    } else {
        include "front/home.php";
    }    ?>
</div>

// 今日的瀏灠人數紀錄
有->瀏灠人次加1
沒有->增加今日的新紀錄,瀏灠人次為1
<?php
if (!isset($_SESSION['view'])) {
    if ($View->math('count', '*', ['date' => date("Y-m-d")]) > 0) {
        $view = $View->find(['date' => date("Y-m-d")]);
        $view['total']++;
        $View->save($view);
        $_SESSION['view'] = $view['total'];
    } else {
        $View->save(['date' => date("Y-m-d"), 'total' => 1]);
        $_SESSION['view'] = 1;
    }
} ?>

//瀏覽人次相關
<?= date("m月 d號 l"); ?> |
今日瀏覽: <?= $View->find(['date' => date("Y-m-d")])['total']; ?> |
累積瀏覽: <?= $View->math('sum', 'total'); ?>

//DW 頁簽
開啟 home.php -> 插入 -> spry -> spry 標籤面板 ->分割
善用複製貼上 注意頁籤眼睛開關 spry資料夾拖至外層

//開資料夾
front (reg login forget ) api(chk_acc chk_pw forget_pw logout reg) 8個檔案
建立前台註冊 登入 忘記密碼 3個版型 (forget 多一個div)

//reg script  3個 if else 巢狀
<script>
    function reset() {
        $("#acc,#pw,#pw2,#email").val("")
    }

    function reg() {
        let form = {
            acc: $("#acc").val(),
            pw: $("#pw").val(),
            pw2: $("#pw2").val(),
            email: $("#email").val()
        }

        //if(form.acc=='' || form.pw=='' || form.pw2=='' || form.email==''){
        if (Object.values(form).indexOf('') >= 0) {
            alert("不可空白")
        } else {
            if (form.pw != form.pw2) {
                alert("密碼錯誤")
            } else {
                $.post("api/chk_acc.php", {acc: form.acc}, (chk) => {
                    if (parseInt(chk) == 1) {
                        alert('帳號重複')
                    } else {
                        delete form.pw2
                        $.post("api/reg.php", form, (res) => {
                            alert("註冊完成，歡迎加入")
                            location.href = 'index.php?do=login'
                        })
                    }
                })
            }
        }
    }
</script>

//至api 完成 reg, chk_acc
//chk_acc
<?php include_once "../base.php";
$acc=$_POST['acc'];
$chk=$User->math('count','*',['acc'=>$acc]);
if($chk>0){  echo 1;} else{  echo 0; }  ?>

//reg
<?php include_once "../base.php";
$User->save($_POST);?>

//完成忘記密碼 api find_pw 與前台 forget
//api  find_pw
<?php include_once "../base.php";

$email=$_POST['email'];
$user=$User->find(['email'=>$email]);

if(empty($user)){
    echo "查無此資料";
}else{
    echo "您的密碼為:".$user['pw'];
} ?>

//前台 forget
<fieldset>
    <legend>忘記密碼</legend>
    <div>請輸入信箱以查詢密碼</div>
    <div><input type="text" name="email" id="email"></div>
    <div id="result"></div>
    <div><button onclick="find()">尋找</button></div>
</fieldset>
<script>

function find(){
    $.post("api/find_pw.php",{email:$("#email").val()},(result)=>{
        $("#result").text(result)
    })
}
</script>

//前台登入  reset 可至 reg 複製相同程式碼
<script>
function reset(){
    $("#acc,#pw").val("")
}

function login(){
    let user={ acc:$("#acc").val(),
               pw:$("#pw").val()}
    $.post("api/chk_acc.php",{acc:user.acc},(chk)=>{
        if(parseInt(chk)==0){
            alert("查無帳號")
        }else{
            $.post("api/chk_pw.php",user,(chk)=>{
                if(parseInt(chk)==0){
                    alert("密碼錯誤")
                }else{
                    if(user.acc=="admin"){
                        location.href='back.php';
                    }else{
                        location.href='index.php';
                    }
                }
            })
        }
    })
}
</script>

//api 的 chk_pw (可用 chk_acc 複製貼上後修改)
<?php include_once "../base.php";

$acc=$_POST['acc'];
$pw=$_POST['pw'];

$chk=$User->math('count','*',['acc'=>$acc,'pw'=>$pw]);
//echo ($chk>0)?1:0;
if($chk>0){
    echo 1;
    $_SESSION['login']=$acc;
}else{
    echo 0;
}