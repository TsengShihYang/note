//至 index 修改登入登出顯示狀態 (要用5個php來包住)
<?php
if (isset($_SESSION['login'])) {

    if ($_SESSION['login'] == 'admin') {
?>
        歡迎admin，<br><button onclick="location.href='back.php'">管理</button>|<button onclick='logout()'>登出</button>
    <?php
    } else {
    ?>
        歡迎<?= $_SESSION['login']; ?>，<button onclick='logout()'>登出</button>
    <?php
    }
    ?>
<?php
} else {
?>
    <a href="?do=login">會員登入</a>
<?php
}
?>

// 把 logout功能寫在 js.js 內被 link 進來
<script>
function logout(){
	$.post('api/logout.php',()=>{
		location.href="index.php";
	})
}
</script>

//至此 可複製 index.php 成 back.php 再進行相關頁面修正與路徑修正
並新增後台 home.php   admin news que 三個檔案


