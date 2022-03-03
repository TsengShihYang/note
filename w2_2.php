//前台 po選單呈現  api getnews getlist
<script>
    getlist(1)
    $(".tag").on('click', function() {
        let navtag = $(this).text()
        $("#navTag").text(navtag)
        let type = $(this).data('type')
        getlist(type)
    })
    function getlist(type) {
        $.get("api/getlist.php", {
            type
        }, (list) => {
            $("#newslist").html(list)
            $("#newslist").show()
            $("#news").hide();
        })
    }
    function getnews(id) {
        $.get("api/getnews.php", {
            id
        }, (news) => {
            $("#news").html(news)
            $("#news").show()
            $("#newslist").hide()
        })
    }
</script>

//get list
<?php include_once "../base.php";

$type=$_GET['type'];
$news=$News->all(['type'=>$type]);

foreach ($news as $key => $value) {
    echo "<p><a href='#' onclick='getnews({$value['id']})'>";
    echo $value['title'];
    echo "</a></p>";
} ?>

//get news
<?php include_once "../base.php";

$id=$_GET['id'];
$news=$News->find($id);

//new line to br
echo nl2br($news['text']); ?>

