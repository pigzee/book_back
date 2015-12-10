<?php

require './source/class/class_core.php';
C::app()->init();

$videoId= $_GET['videoId'];
$video = C::t('book_video')->query_by_videoId($videoId);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
    <title>视频详情</title>
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
    </style>
	
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="http://apps.bdimg.com/libs/html5shiv/3.7/html5shiv.min.js"></script>
	  <script src="http://apps.bdimg.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	

</head>

<body class="home-template">
    <div class="header">
        <img src="static/book/images/header.jpg" style="width:100%"/>
    </div>
    <div class="container projects">
        <div class="row">
            <div class="col-sm-12 col-md-9 col-lg-9">

                <div id="videoWindow">
                    <!--
                        <img class="lazy" src="static/book/images/2.jpg" data-src="static/book/images/2.jpg" alt="Headroom.js" height="400" width="343">
                    -->
                </div>

            </div>
            <div class="col-sm-12 col-md-3 col-lg-3 ">
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <li role="presentation" class="active"><a href="#author">作者信息</a></li>
                    <li role="presentation"><a href="#content">内容</a></li>
                </ul>
                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="author" aria-labelledby="author-tab">
                        <p>作者姓名：<?=$video['teacher_name']?></p>
                        <p>所在学院：<?=$video['college_name']?></p>
                        <p>上传时间：<?=$video['uploadTime']?></p>
                        <p>作者简介：<?=$video['teacher_desc']?></p>
                        <p>本期视频：<?=$video['name']?></p>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="content" aria-labelledby="content-tab">
                        <p><?=$video['content']?></p>
                    </div>
                </div>
            </div>
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
<script type="text/javascript" src="static/book/jwplayer/jwplayer.js"></script>
<script>
    $(function() {
        $('#myTab a').click(function (e) {
            e.preventDefault()
            $(this).tab('show')
        });

        jwplayer("videoWindow").setup({
            file: "uploads/" + '<?=$video["videoFileName"]?>',
			width:"100%",
			height:500,
            rtmp: {
                bufferlength: 0.1
            }
        });
    });
</script>
</html>