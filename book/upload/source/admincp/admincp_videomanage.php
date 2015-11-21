<?php
    if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
        exit('Access Denied');
    }

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
    $msg = '';
    $operation = $operation ? $operation : 'list';
    $colleges = C::t('book_college')->query_all();
    if($operation == 'add') {
        $name = $_GET['name'];
        $collegeId = $_GET['collegeId'];
        $teacherId = $_GET['teacherId'];
        $year = $_GET['year'];
        $month = $_GET['month'];
        $imgFileName = $_GET['imgFileName'];
        $videoFileName = $_GET['videoFileName'];
        $content = $_GET['content'];
        $msg = C::t('book_video')->insert($name, $teacherId, $year, $month, $imgFileName, $videoFileName, $content);
    } else if($operation == 'update') {
        $id = $_GET['id'];
        $name = $_GET['name'];
        $collegeId = $_GET['collegeId'];
        $teacherId = $_GET['teacherId'];
        $year = $_GET['year'];
        $month = $_GET['month'];
        $imgFileName = $_GET['imgFileName'];
        $videoFileName = $_GET['videoFileName'];
        $content = $_GET['content'];
        $uploadTime = $_GET['uploadTime'];
        $msg = C::t('book_video')->update($id, $name, $teacherId, $year, $month, $imgFileName, $videoFileName, $content, $uploadTime);
    } else if($operation == 'delete') {
        $id = $_GET['id'];
        $msg = C::t('book_video')->delete($id);
    }

    $videos = C::t('book_video')->query_by_year($selectedYear);

?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
    <title>视频管理</title>
    <link href="static/book/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="static/book/css/font-awesome.min.css" rel="stylesheet">
    <link href="static/book/css/app.css" rel="stylesheet">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="static/book/js/html5shiv.js/3.7/html5shiv.min.js"></script>
	  <script src="static/book/js/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>
<form class="form-horizontal" role="form"  id="selectYearForm" method="POST" autocomplete="off" action="">
    <div class="form-group">
        <label for="selectedYear" class="col-sm-1 control-label">请选择年份</label>
        <div class="col-sm-2">
            <select class="form-control" id="selectedYear" name="selectedYear">
                <?php
                for($i = 2013; $i <= $thisYear; $i++) {
                    ?>
                    <option value="<?=$i?>"><?=$i?></option>
                <?php
                }
                ?>
            </select>
        </div>
        <div class="col-sm-2">
            <button type="button" class="btn btn-primary" id="addBtn" >添加视频</button>
        </div>
    </div>
</form>

<div class="col-sm-12">
    <table class="table table-hover">
        <thead>
        <tr>
            <th>月份</th>
            <th>视频名称</th>
            <th>作者姓名</th>
            <th>作者所在学院</th>
            <th>视频文件</th>
            <th>上传时间</th>
            <th>内容简介</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody id="videoListTbody">
        <?php
        foreach($videos as $video):
            ?>
            <tr>
                <td><?=$video['month'] ?></td>
                <td><?=$video['name']?><input type="hidden" name="id" value="<?=$video['id']?>"</td>
                <td><?=$video['teacher_name']?><input type="hidden" name="teacher_id" value="<?=$video['teacher_id']?>"></td>
                <td><?=$video['college_name']?><input type="hidden" name="college_id" value="<?=$video['college_id']?>"></td>
                <td><?=$video['videoFileName']?><input type="hidden" name="imgFileName" value="<?=$video['imgFileName']?>"></td>
                <td><?=$video['uploadTime']?></td>
                <td><?=substr_cut($video['content'],8);?><input type="hidden" name="content" value="<?=$video['content']?>"></td>
                <td>
                    <button type="button" class="btn btn-default btn-xs" title="编辑" name="updateBtn">
                        <span class="glyphicon glyphicon-edit"></span>
                    </button>
                    <button type="button" class="btn btn-default btn-xs" title="删除" name="deleteBtn">
                        <span class="glyphicon glyphicon-remove-circle"></span>
                    </button>
                </td>
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>
    </table>
</div>



<!-- 添加&编辑 Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
     data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span
                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><span id="title"></span></h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" role="form" id="form" method="POST" autocomplete="off" action="" onsubmit="return check();">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="uploadTime" id="uploadTime">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">视频名称</label>
                        <div class="col-sm-7">
                            <input class="form-control" id="name" name="name" placeholder="视频名称不要超过50个字符" type="text">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="collegeId" class="col-sm-3 control-label">作者所在学院</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="collegeSelect" name="collegeId">
                                <option value="-1">请选择</option>
                                <?php
                                foreach($colleges as $college):
                                    ?>
                                    <option value="<?=$college['id']?>"><?=$college['name']?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="teacherId" class="col-sm-3 control-label">作者姓名</label>
                        <div class="col-sm-7">
                            <!-- id="teacher_id" 为点击编辑时，存取teacherId的值-->
                            <input type="hidden" value="" id="teacher_id" />
                            <!-- 点击编辑按钮，获取到collegeId,同时需要触发其change事件，不过new Ajax无法设置sync同步，
                                 所以设置了状态位，只有当点击编辑按钮时，$('#updateBtnClickingStatus').val() == 1  -->
                            <input type="hidden" value="" id="updateBtnClickingStatus" />
                            <select class="form-control" id="teacherSelect" name="teacherId">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="year" class="col-sm-3 control-label">年份</label>
                        <div class="col-sm-3">
                            <select class="form-control" id="year" name="year">
                                <?php
                                for($i = 2013; $i <= $thisYear; $i++) {
                                ?>
                                    <option value="<?=$i?>"><?=$i?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <label for="month" class="col-sm-2 control-label">月份</label>
                        <div class="col-sm-2">
                            <select class="form-control" id="month" name="month">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="picture" class="col-sm-3 control-label">上传缩略图</label>
                        <div class="col-sm-7" id="imgContainer">
                            <div id="imgPanel">
                                <span style="color: red">您的浏览器不支持 Flash, Silverlight or .HTML5</span>
                            </div>
                            <div>
                                <span id="imgConsole"></span>
                            </div>
                            <a id="pickImgBtn" href="javascript:;">[选择缩略图文件(最大500K)]</a>
                            <a id="uploadImgBtn" href="javascript:;">[上传缩略图文件]</a>
                            <input type="hidden" name="imgFileName"  value="" id="imgFileName" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="video" class="col-sm-3 control-label">上传视频</label>
                        <div class="col-sm-7" id="videoContainer">
                            <div id="videoPanel">
                                <span style="color: red">您的浏览器不支持 Flash, Silverlight or .HTML5</span>
                            </div>
                            <div>
                                <span id="videoConsole"></span>
                            </div>
                            <a id="pickVideoBtn" href="javascript:;">[选择视频文件(最大1000M)]</a>
                            <a id="uploadVideoBtn" href="javascript:;">[上传视频文件]</a>
                            <input type="hidden" name="videoFileName"  value="" id="videoFileName" >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="content" class="col-sm-3 control-label">内容简介</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="content" name="content" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-primary">提交</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    var SITEURL = '<?=$_G[siteurl]?>';
</script>
<script type="text/javascript" src="static/js/common.js"></script>
<script type="text/javascript" src="static/js/ajax.js"></script>
<script type="text/javascript" src="static/book/jquery/jquery-1.11.2.js"></script>
<script type="text/javascript" src="static/book/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="static/book/js/plupload/plupload.full.min.js"></script>
<script>
    var imgUploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'pickImgBtn', // you can pass in id...
        container: document.getElementById('imgContainer'), // ... or DOM Element itself
        url : '<?=ADMINSCRIPT?>?action=upload',
        flash_swf_url : 'static/book/js/plupload/Moxie.swf',
        silverlight_xap_url : 'static/book/js/plupload/Moxie.xap',
        filters : {
            max_file_size : '500kb',
            mime_types: [
                {title : "Image files", extensions : "jpg,gif,png"}
            ]
        },

        init: {
            PostInit: function() {
                document.getElementById('imgPanel').innerHTML = '';
                document.getElementById('uploadImgBtn').onclick = function() {
                    imgUploader.start();
                    return false;
                };
            },

            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                    document.getElementById('imgConsole').innerHTML = '';
                    document.getElementById('imgPanel').innerHTML = '<div id="' + file.id + '"><span id="imgFileSpan">' + file.name + ' </span>(' + plupload.formatSize(file.size) + ') <b></b></div>';
                });
            },
            UploadProgress: function(up, file) {
                document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
            },
            FileUploaded : function(up, file, res) {
                var fileName = eval('(' + res.response + ')').fileName;
                $('#imgFileName').val(fileName);
                if($('#imgFileSpan')) {
                    $('#imgFileSpan').text(fileName);
                }
            },
            Error: function(up, err) {
                document.getElementById('imgConsole').innerHTML = "\nError #" + err.code + ": " + err.message;
            }
        }
    });
    var videoUploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'pickVideoBtn', // you can pass in id...
        container: document.getElementById('videoContainer'), // ... or DOM Element itself
        url : '<?=ADMINSCRIPT?>?action=upload',
        flash_swf_url : 'static/book/js/plupload/Moxie.swf',
        silverlight_xap_url : 'static/book/js/plupload/Moxie.xap',
        filters : {
            max_file_size : '1000mb',
            mime_types: [
                {title : "video", extensions : "flv"}
            ]
        },

        init: {
            PostInit: function() {
                document.getElementById('videoPanel').innerHTML = '';
                document.getElementById('uploadVideoBtn').onclick = function() {
                    videoUploader.start();
                    return false;
                };
            },

            FilesAdded: function(up, files) {
                plupload.each(files, function(file) {
                    document.getElementById('imgConsole').innerHTML = '';
                    document.getElementById('videoPanel').innerHTML = '<div id="' + file.id + '"><span id="videoFileSpan">' + file.name + ' </span>(' + plupload.formatSize(file.size) + ') <b></b></div>';
                });
            },

            UploadProgress: function(up, file) {
                document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
            },
            FileUploaded : function(up, file, res) {
                var fileName = eval('(' + res.response + ')').fileName;
                $('#videoFileName').val(fileName);
                if($('#videoFileSpan')) {
                    $('#videoFileSpan').text(fileName);
                }

            },
            Error: function(up, err) {
                document.getElementById('videoConsole').innerHTML = "\nError #" + err.code + ": " + err.message;
            }
        }
    });

    function setTeacher() {
        if($('#form').attr('action').indexOf('update') != -1 && $('#updateBtnClickingStatus').val() == 1) {
            $('#teacherSelect').val($('#teacher_id').val());
            $('#updateBtnClickingStatus').val(0);
        }
    }

    $(function() {
        //年份选择，展示当年视频列表
        $('#selectedYear').val('<?=$selectedYear?>');
        $('#selectedYear').change(function() {
            $('#selectYearForm').attr('action','<?=ADMINSCRIPT?>?action=videomanage&operation=list');
            $('#selectYearForm').submit();
        });
        $('#year').val('<?=$selectedYear?>');
        //点击添加视频
        $('#addBtn').click(function() {
            $('#myModal').modal();
            $('#title').text("添加视频信息");
            $('#form').attr('action','<?=ADMINSCRIPT?>?action=videomanage&operation=add&selectedYear=' + $('#selectedYear').val());
            $('#name').val('');
            $('#id').val('');

            $('#year').val(<?=$selectedYear?>);
            $('#month').val(1);
            $('#collegeSelect').val(-1);
            $('#teacherSelect').empty().append('<option value="-1">请选择</option>');
            $('#videoFileName').val('');
            $('#imgFileName').val('');
            $('#imgPanel')[0].innerHTML = '';
            $('#videoPanel')[0].innerHTML = '';
            $('#content').val('');
        });

        $('button[name="updateBtn"]').click(function(e) {
            var $tr = $(this).parent().parent();
            if ( e && e.stopPropagation ) {
                e.stopPropagation();
            } else {
                window.event.cancelBubble = true;
            }
            $('#myModal').modal();
            $('#title').text("编辑视频信息");
            $('#form').attr('action','<?=ADMINSCRIPT?>?action=videomanage&operation=update&selectedYear=' + $('#selectedYear').val());
            var id = $tr.find("input[name='id']").val();
            var name = $tr.find('td').eq(1).text();
            var teacherId = $tr.find("input[name='teacher_id']").val();
            var collegeId = $tr.find("input[name='college_id']").val();
            var videoFileName = $tr.find('td').eq(4).text();
            var imgFileName = $tr.find("input[name='imgFileName']").val();
            var year = $('#selectedYear').val();
            var month = $tr.find('td').eq(0).text();
            var content =  $tr.find("input[name='content']").val();
            var uploadTime = $tr.find('td').eq(5).text();
            /*
            alert('id=' + id + "..name=" + name + '..teacherId=' + teacherId + "..collegeId=" + collegeId +
                    "..videoFileName=" + videoFileName + "..imgFileName=" + imgFileName + "..year=" + year +
                    "..month=" + month + "..content=" + content);
            */
            $('#id').val(id);
            $('#name').val(name);
            $('#teacher_id').val(teacherId);
            $('#updateBtnClickingStatus').val(1);
            $('#collegeSelect').val(collegeId);
            $('#collegeSelect').change();
            $('#year').val(year);
            $('#month').val(month);
            $('#content').val(content);
            $('#collegeSelect').val(collegeId);
            $('#imgPanel').find('span').text(imgFileName);
            $('#videoPanel').find('span').text(videoFileName);
            $('#videoFileName').val(videoFileName);
            $('#imgFileName').val(imgFileName);
            $('#imgPanel')[0].innerHTML = '<div>' + imgFileName +' <b></b></div>';
            $('#videoPanel')[0].innerHTML = '<div>' + videoFileName +' <b></b></div>';
            $('#uploadTime').val(uploadTime);
            return false;
        }) ;

        //删除教师信息
        $('button[name="deleteBtn"]').click(function(e) {
            if ( e && e.stopPropagation ) {
                e.stopPropagation();
            } else {
                window.event.cancelBubble = true;
            }
            if(confirm("确定要清空数据吗？")) {
                var $tr = $(this).parent().parent();
                var id = $tr.find("input[name='id']").val();
                location.href='<?=ADMINSCRIPT?>?action=videomanage&operation=delete&selectedYear=' + $('#selectedYear').val() + '&id=' + id;
            }

        });

        $("#videoListTbody tr").css("cursor","pointer").bind("click",function(){
            var a = document.createElement("a");
            a.setAttribute("href", "source/admincp/videodetail.html");
            a.setAttribute("target", "_blank");
            document.body.appendChild(a);
            a.click();
        });

        $('#collegeSelect').change(function() {
            var cid = $('#collegeSelect').val();
            if(cid == -1) {
                $('#teacherSelect').empty().append('<option value=-1>请选择</option>');
            } else {
                var x = new Ajax();
                x.get('forum.php?mod=ajax&inajax=1&action=test&cid=' + cid, function(s) {
                    $('#teacherSelect').empty().append('<option value="-1">请选择</option>').append(s.replace(/<script(.*)<\/script>/,''));
                    setTeacher();
                });
            }
        });
        $('#collegeSelect').change();
        videoUploader.init();
        imgUploader.init();

        if('<?=$operation?>' != 'list' ) {
            alert('<?=$msg?>');
        }

    });

    function check() {
        var name = $('#name').val();
        var imgFileName = $('#imgFileName').val();
        var videoFileName = $('#videoFileName').val();
        var collegeSelect = $('#collegeSelect').val();
        var teacherSelect = $('#teacherSelect').val();

        if(!$.trim(name)) {
            alert("请输入视频名称!");
            $('#name').focus();
            return false;
        } else if($.trim(name).length > 50) {
            alert('视频名称请不要超过50个字符!');
            $('#name').focus();
            return false;
        }
        if(collegeSelect == -1 || collegeSelect == '-1') {
            alert("请选择作者所在学院!");
            return false;
        }
        if(teacherSelect == -1 || teacherSelect == '-1') {
            alert("请选择作者!");
            return false;
        }
        if(!$.trim(imgFileName)) {
            alert("请上传缩略图!");
            return false;
        }
        if(!$.trim(videoFileName)) {
            alert("请上传视频文件，支持格式flv!");
            return false;
        }
        var content = $('#content').val();
        if($.trim(content).length > 500) {
            alert('简介内容请不要超过500个字符!');
            $('#content').focus();
            return false;
        }
    }


</script>
</html>