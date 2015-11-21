<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_book_teacher extends discuz_table
{
    public function __construct() {

        $this->_table = 'book_teacher';

        parent::__construct();
    }

    public function query_all() {
        return DB::fetch_all("SELECT t.id,c.name AS collegeName,c.id AS collegeId,t.name,t.petName,t.number,t.phone,t.email,t.desc FROM "
            .DB::table('book_teacher').' t,'.DB::table('book_college').' c where t.collegeId = c.id');
    }

    public function query_by_collegeId($cid) {
        return DB::fetch_all("SELECT * FROM ".DB::table('book_teacher').' where collegeId = '.$cid);
    }

    public function insert($name, $petName, $number, $email, $phone, $collegeId, $desc, $return_insert_id = false, $replace = false, $silent = false) {
        $query = DB::query('select * from '.DB::table('book_teacher').' where number=\''.$number.'\'');
        if(DB::num_rows($query) > 0) {
            return "教师添加失败，存在相同的工号！";
        } else {
            $setarr = array(
                'name' => $name,
                'petName' => $petName,
                'number' => $number,
                'email' => $email,
                'phone' => $phone,
                'collegeId' => $collegeId,
                'desc' => $desc
            );
            DB::insert($this->_table, $setarr, $return_insert_id, $replace, $silent);
            return "教师添加成功！";
        }
    }

    public function update($id, $name, $petName, $number, $email, $phone, $collegeId, $desc) {
        $query = DB::query('select * from '.DB::table('book_teacher').' where number=\''.$number.'\' and id <> '.$id);
        if(DB::num_rows($query) > 0) {
            return "教师信息修改失败，存在同工号的教师！";
        } else {
            DB::update( $this->_table,
                        array(  'name'=>$name,
                                'petName'=>$petName,
                                'number'=>$number,
                                'email'=>$email,
                                'phone'=>$phone,
                                'collegeId'=>$collegeId,
                                'desc'=>$desc),
                        array('id'=> $id)
                      );
            return "教师信息修改成功！";
        }
    }

    public function delete($id) {
        if(!$id) {
            return "教师删除失败，id为空！";
        }
        DB::delete($this->_table, DB::field('id', $id));
        return "教师删除成功！";
    }
}
?>