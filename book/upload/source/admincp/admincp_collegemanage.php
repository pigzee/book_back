<?php
    if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
        exit('Access Denied');
    }

    global $_G;

    $operation = $operation ? $operation : 'list';
    $msg = '';
    if($operation == 'add') {
        $name = $_GET['name'];
        $code = $_GET['code'];
        $msg = C::t('book_college')->insert($name, $code);
    } else if($operation == 'update') {
        $id = $_GET['id'];
        $name = $_GET['name'];
        $code = $_GET['code'];
        $msg = C::t('book_college')->update($id, $name, $code);
    } else if($operation == 'delete') {
        $id = $_GET['id'];
        $msg = C::t('book_college')->delete($id);
    }

    $colleges = C::t('book_college')->query_all();
    $num = 1;
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=9" />	
    <title>学院管理</title>
    <link href="static/book/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="static/book/css/font-awesome.min.css" rel="stylesheet">
    <link href="static/book/css/app.css" rel="stylesheet">
	
</head>

<body>
<table class="table table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>学院代号</th>
        <th>学院名称</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="tbody">
    <?php
        foreach($colleges as $college):
    ?>
            <tr>
                <td><?=$num++ ?><input type="hidden" name="collegeId" value="<?=$college['id']?>" </td>
                <td><?=$college['code']?></td>
                <td><?=$college['name']?></td>
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

<div>
    <p>
        <button type="button" class="btn btn-primary" id="addCollegeBtn">添加学院</button>
    </p>
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
                <form class="form-horizontal" id="form" role="form" method="POST" name="collegeForm" autocomplete="off"
                      action="" onsubmit="return check();">
                    <table>
                        <input name="id" id="id" value="" type="hidden">
                        <div class="form-group">
                            <label for="code" class="col-sm-3 control-label">学院代号</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="code" id="code" placeholder="学院代号不要超过30个字符"
                                       type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">学院名称</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="name" id="name" placeholder="学院名称不要超过50个字符" type="text">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn btn-primary">提交</button>
                        </div>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
<script type="text/javascript">var SITEURL = '$_G[siteurl]'</script>
<script src="static/book/jquery/jquery-1.11.2.js"></script>
<script src="static/book/bootstrap/js/bootstrap.min.js"></script>
<script src="static/book/js/ajax.js"></script>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="static/book/js/html5shiv.js/3.7/html5shiv.min.js"></script>
	  <script src="static/book/js/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
<script>
    $(function() {
        //编辑学院信息
        $('button[name="updateBtn"]').click(function() {
            var $tr = $(this).parent().parent();
            $('#myModal').modal();
            $('#title').text("编辑学院信息");
            $('#form').attr('action','<?=ADMINSCRIPT?>?action=collegemanage&operation=update');
            var id = $tr.find('input').val();
            var code = $tr.find('td').eq(1).text();
            var name = $tr.find('td').eq(2).text();
            $('#name').val(name);
            $('#code').val(code);
            $('#id').val(id);
        }) ;

        //添加学院信息
        $('#addCollegeBtn').click(function() {
            $('#myModal').modal();
            $('#title').text("添加学院信息");
            $('#form').attr('action','<?=ADMINSCRIPT?>?action=collegemanage&operation=add');
            $('#name').val('');
            $('#code').val('');
            $('#id').val('');
        });

        //删除学院信息
        $('button[name="deleteBtn"]').click(function() {
            if(confirm("确定要删除数据吗？")) {
                var $tr = $(this).parent().parent();
                var id = $tr.find('input').val();
                location.href='<?=ADMINSCRIPT?>?action=collegemanage&operation=delete&id=' + id;
            }
        });

        //没有使用ajax，通过刷新页面显示action后的操作结果
        if('<?=$operation?>' != 'list' ) {
            alert('<?=$msg?>');
        }
    });
    //提交前检测字段
    function check() {
        var name =$('#name').val();
        if(!$.trim(name)) {
            alert("请输入学院名称!");
            $('#name').focus();
            return false;
        } else if($.trim(name).length > 50) {
            alert('学院名称请不要超过50个字符!');
            $('#name').focus();
            return false;
        }
        var code =$('#code').val();
        if(!$.trim(code)) {
            alert("请输入学院代号!");
            $('#code').focus();
            return false;
        } else if($.trim(code).length > 30) {
            alert('学院代号请不要超过30个字符!');
            $('#code').focus();
            return false;
        }
    }
</script>
</html>


