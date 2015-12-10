<?php

require './source/class/class_core.php';
C::app()->init();


/*
------------------------------------------------------
参数：
$str_cut    需要截断的字符串
$length     允许字符串显示的最大长度

程序功能：截取全角和半角（汉字和英文）混合的字符串以避免乱码
------------------------------------------------------
*/
function substr_cut($str_cut,$length)
{
    if (strlen($str_cut) > $length) {
        $str_cut = mb_substr($str_cut,0,$length, 'utf-8')."..";
    }
    return $str_cut;
}

//选中的年份，默认是当年
$selectedYear = $_GET['selectedYear'];
//当年
$thisYear = date("Y");
if($selectedYear == null) {
    $selectedYear = $thisYear;
}

$colleges = C::t('book_college')->query_all();
$videos = C::t('book_video')->query_by_year($selectedYear);

$lastVideo = C::t('book_video')->queryLastVideo();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <title>书友会首页</title>
    <link href="static/book/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="static/book/css/font-awesome.min.css" rel="stylesheet">
    <link href="static/book/css/app.css" rel="stylesheet">
    <style>
        .header {
            padding: 0px;
            margin-bottom: 20px;
            text-align: left;
            min-height: 360px;
            position: relative;
        }
        .mySelect {
            background-color: #208cca;
        }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="static/book/js/html5shiv.js/3.7/html5shiv.min.js"></script>
    <script src="static/book/js/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>

<body class="home-template">
<div class="header">
	<img src="static/book/images/header.jpg" style="width:100%"/>
</div>
<div class="container projects">
    <!--
    <div class="row">
        <div class="col-sm-12 col-md-4 col-lg-4 ">
            <div class="thumbnail">
                <?php if ($lastVideo == null) { ?>
                    <a href="" title="视频" target="_blank" onclick="_hmt.push(['_trackEvent', 'tile', 'click', 'codeguide'])">
                        <img class="lazy" src="static/book/images/2.jpg" data-src="static/book/images/2.jpg" alt="Headroom.js" height="300" width="343">
                    </a>
                <?php } else {?>
                    <a href="videodetail.php?videoId=<?=$lastVideo['id']?>" title="视频" target="_blank" onclick="_hmt.push(['_trackEvent', 'tile', 'click', 'codeguide'])">
                        <img class="lazy" src="uploads/<?=$lastVideo['imgFileName']?>" data-src="uploads/<?=$lastVideo['imgFileName']?>" alt="Headroom.js" height="300" width="343">
                    </a>
                <?php }?>
            </div>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4 ">
            <div>
                <h3>本期话题为：</h3>
                <?php if ($lastVideo != null) { ?>
                    <?=substr_cut($lastVideo['content'],30)?>
                <?php }?>
            </div>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4" style="height: 262px;border-left: 1px #c0c0c0 dashed;padding-bottom: 30px">
            <div>
                加入书友会</br>
                书友会每周定期活动。更多详细，请进入<a href="forum.php">书友会论坛</a></br>
                </br>
                点击进入<a href="forum.php">书友会论坛</a></br>
                关于书友会</br>
                书友会活动为图书馆书友会论坛长期书评、试读、约稿活动。</br>
            </div>
        </div>
    </div>
    -->
    <div style="padding:1px 1px 1px 5px;margin-bottom: 5px;">
        <form class="form-horizontal" id="form" role="form"  method="POST" autocomplete="off" action="">
            <h3>
                <select class="mySelect" name="selectedYear" id="selectedYear">
                    <option>2014</option>
                    <option>2015</option>
                    <option>2016</option>
                    <option>2017</option>
                    <option>2018</option>
                </select>年度活动视频
            </h3>
        </form>

    </div>
    <div class="row">
        <?php
        foreach($videos as $video):
            ?>
            <div class="col-sm-6 col-md-4 col-lg-3 ">
                <div class="thumbnail">
                    <a href="videodetail.php?videoId=<?=$video['id']?>" title="" target="_blank" onclick="_hmt.push(['_trackEvent', 'tile', 'click', 'codeguide'])">
                        <img src="uploads/<?=$video['imgFileName']?>" data-src="uploads/<?=$video['imgFileName']?>"  style="height:200px;width:252px" />
                    </a>
                    <div class="caption">
                        <h5>
                            <a href="videodetail.php?videoId=<?=$video['id']?>" title="<?=$video['name']?>" target="_blank"
                               onclick="_hmt.push(['_trackEvent', 'tile', 'click', 'codeguide'])"><?=$video['name']?><br><small>by <?=$video['teacher_number']?></small></a>
                        </h5>
                        <p>
                            <?=substr_cut($video['content'],8)?>
                        </p>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
        ?>

    </div>
</div>

<footer class="footer ">
    <div class="container">
        <div class="row footer-top">
            <ul class="list-inline text-center">
                版权所有：哈尔滨商业大学图书馆
            </ul>
        </div>
    </div>
</footer>

</body>

<script src="static/book/jquery/jquery-1.11.2.js"></script>
<script src="static/book/bootstrap/js/bootstrap.min.js"></script>
<script>
    $(function() {
        //年份选择，展示当年视频列表
        $('#selectedYear').val('<?=$selectedYear?>');
        $('#selectedYear').change(function() {
            $('#form').attr('action','front.php?selectedYear=' + $('#selectedYear').val());
            $('#form').submit();
        });
    });
</script>
</html>