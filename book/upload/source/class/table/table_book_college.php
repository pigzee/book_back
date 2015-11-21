<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_book_college extends discuz_table
{
    public function __construct() {

        $this->_table = 'book_college';

        parent::__construct();
    }

    public function query_all() {
        return DB::fetch_all("SELECT * FROM ".DB::table('book_college'));
    }

    public function insert($name, $code, $return_insert_id = false, $replace = false, $silent = false) {
        $query = DB::query('select * from '.DB::table('book_college').' where name=\''.$name.'\' or code=\''.$code.'\'');
        if(DB::num_rows($query) > 0) {
            return "学院添加失败，存在同名或者同代号的学院！";
        } else {
            $setarr = array(
                'name' => $name,
                'code' => $code
            );
            DB::insert($this->_table, $setarr, $return_insert_id, $replace, $silent);
            return "学院添加成功！";
        }
    }

    public function update($id, $name, $code) {
        $query = DB::query('select * from '.DB::table('book_college').' where (name=\''.$name.'\' or code=\''.$code.'\') and id<>'.$id);
        if(DB::num_rows($query) > 0) {
            return "学院修改失败，存在同名或者同代号的学院！";
        } else {
            DB::update($this->_table, array('name'=>$name,'code'=>$code), array('id'=> $id));
            return "学院修改成功！";
        }
    }

    public function delete($id) {
        if(!$id) {
            return "学院删除失败，id为空！";
        }
        DB::delete($this->_table, DB::field('id', $id));
        return "学院删除成功！";
    }
}

?>