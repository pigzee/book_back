<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

global $_G;

$msg = '';
$operation = $operation ? $operation : 'list';
$colleges = C::t('book_college')->query_all();
if($operation == 'add') {
    $name = $_GET['name'];
    $petName = $_GET['petName'];
    $number = $_GET['number'];
    $email = $_GET['email'];
    $phone = $_GET['phone'];
    $collegeId = $_GET['collegeId'];
    $desc = $_GET['desc'];
    $msg = C::t('book_teacher')->insert($name, $petName, $number, $email, $phone, $collegeId, $desc);
} else if($operation == 'update') {
    $id = $_GET['id'];
    $name = $_GET['name'];
    $petName = $_GET['petName'];
    $number = $_GET['number'];
    $email = $_GET['email'];
    $phone = $_GET['phone'];
    $collegeId = $_GET['collegeId'];
    $desc = $_GET['desc'];
    $msg = C::t('book_teacher')->update($id, $name, $petName, $number, $email, $phone, $collegeId, $desc);
} else if($operation == 'delete') {
    $id = $_GET['id'];
    $msg = C::t('book_teacher')->delete($id);
}

$teachers = C::t('book_teacher')->query_all();
$num = 1;
?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
    <title>教师管理</title>
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
<table class="table table-hover">
    <thead>
    <tr>
        <th>#</th>
        <th>真实姓名</th>
        <th>昵称</th>
        <th>工号</th>
        <th>邮箱</th>
        <th>电话号码</th>
        <th>所属学院</th>
    </tr>
    </thead>
    <tbody id="tbody">
    <?php
    foreach($teachers as $teacher):
        ?>
        <tr>
            <td><?=$num++ ?><input type="hidden" name="teacherId" value="<?=$teacher['id']?>" ></td>
            <td><?=$teacher['name']?></td>
            <td><?=$teacher['petName']?></td>
            <td><?=$teacher['number']?></td>
            <td><?=$teacher['email']?></td>
            <td><?=$teacher['phone']?></td>
            <td>
                <?=$teacher['collegeName']?>
                <input type="hidden" name="collegeId" value="<?=$teacher['collegeId']?>">
                <input type="hidden" name="teacherDesc" value="<?=$teacher['desc']?>" >
            </td>
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
        <button type="button" class="btn btn-primary" id="addteacherBtn">添加教师</button>
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
                <form class="form-horizontal" id="form" role="form" method="POST" autocomplete="off"
                      action="" onsubmit="return check();">
                    <table>
                        <input name="id" id="id" value="" type="hidden">
                        <div class="form-group">
                            <label for="name" class="col-sm-3 control-label">姓名</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="name" id="name" placeholder="姓名不要超过30个字符"
                                       type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="petName" class="col-sm-3 control-label">昵称</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="petName" id="petName" placeholder="昵称不要超过30个字符" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="number" class="col-sm-3 control-label">工号</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="number" id="number" placeholder="工号不要超过12个字符" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">邮箱</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="email" id="email" placeholder="邮箱不要超过50个字符" type="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone" class="col-sm-3 control-label">电话号码</label>
                            <div class="col-sm-7">
                                <input class="form-control" name="phone" id="phone" placeholder="电话号码不要超过11个字符" type="tel">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="collegeId" class="col-sm-3 control-label">所属学院</label>
                            <div class="col-sm-7">
                                <select class="form-control" id="collegeSelect" name="collegeId">
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
                            <label for="desc" class="col-sm-3 control-label">内容简介</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name='desc' id="desc" rows="3"></textarea>
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

<script type="text/javascript">
    var SITEURL = '<?=$_G[siteurl]?>';
</script>
<script type="text/javascript" src="static/js/common.js"></script>
<script type="text/javascript" src="static/js/ajax.js"></script>
<script type="text/javascript" src="static/book/jquery/jquery-1.11.2.js"></script>
<script type="text/javascript" src="static/book/bootstrap/js/bootstrap.min.js"></script>

<script>
    $(function() {
        //编辑教师信息
        $('button[ name="updateBtn"]').click(function() {
            var $tr = $(this).parent().parent();
            $('#myModal').modal();
            $('#title').text("编辑教师信息");
            $('#form').attr('action','<?=ADMINSCRIPT?>?action=teachermanage&operation=update');
            var id = $tr.find("input[name='teacherId']").val();
            var name = $tr.find('td').eq(1).text();
            var petName = $tr.find('td').eq(2).text();
            var number = $tr.find('td').eq(3).text();
            var email = $tr.find('td').eq(4).text();
            var phone = $tr.find('td').eq(5).text();
            var collegeId = $tr.find("input[name='collegeId']").val();
            var desc = $tr.find("input[name='teacherDesc']").val();
            $('#id').val(id);
            $('#name').val(name);
            $('#petName').val(petName);
            $('#number').val(number);
            $('#email').val(email);
            $('#phone').val(phone);
            $('#desc').val(desc);
            $('#collegeSelect').val(collegeId);
        }) ;

        //添加教师信息
        $('#addteacherBtn').click(function() {
            $('#myModal').modal();
            $('#title').text("添加教师信息");
            $('#form').attr('action','<?=ADMINSCRIPT?>?action=teachermanage&operation=add');
            $('#name').val('');
            $('#petName').val('');
            $('#number').val('');
            $('#email').val('');
            $('#phone').val('');
            $('#desc').val('');
            $('#id').val('');
        });

        //删除教师信息
        $('button[name="deleteBtn"]').click(function() {
            if(confirm("确定要删除数据吗？")) {
                var $tr = $(this).parent().parent();
                var id = $tr.find('input').val();
                location.href='<?=ADMINSCRIPT?>?action=teachermanage&operation=delete&id=' + id;
            }
        });

        if('<?=$operation?>' != 'list' ) {
            alert('<?=$msg?>');
        }
    });

    function check() {
        var name = $('#name').val();
        if(!$.trim(name)) {
            alert("请输入教师姓名!");
            $('#name').focus();
            return false;
        } else if($.trim(name).length > 30) {
            alert('教师姓名请不要超过30个字符!');
            $('#name').focus();
            return false;
        }
        var petName = $('#petName').val();
        if($.trim(petName).length > 30) {
            alert('教师昵称请不要超过30个字符!');
            $('#petName').focus();
            return false;
        }
        var number = $('#number').val();
        if(!$.trim(number)) {
            alert("请输入教师工号!");
            $('#number').focus();
            return false;
        } else if($.trim(number).length > 12) {
            alert('教师工号请不要超过12个字符!');
            $('#number').focus();
            return false;
        }
        var email = $('#email').val();
        if($.trim(email).length > 50) {
            alert('email请不要超过50个字符!');
            $('#email').focus();
            return false;
        }
        var phone = $('#phone').val();
        var myreg = /^1\d{10}$/;
        if(!myreg.test(phone)){
            alert('请输入有效的手机号码！');
            $('#phone').focus();
            return false;
        }
        var desc = $('#desc').val();
        if($.trim(desc).length > 500) {
            alert('内容简介请不要超过500个字符!');
            $('#desc').focus();
            return false;
        }
    }
</script>
</html>


