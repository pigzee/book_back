<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class table_book_video extends discuz_table
{
    public function __construct() {

        $this->_table = 'book_video';

        parent::__construct();
    }
    /*
         * 通过年份查询视频，并带有与视频相关的一些信息，比如作者等
         * */
    public function query_by_year($year) {
        return DB::fetch_all("  SELECT
                                    c.id as college_id,
                                    c.name as college_name,
                                    t.id as teacher_id,
                                    t.name as teacher_name,
                                    t.number as teacher_number,
                                    v.*
                                from ".DB::table('book_college')." c, ".DB::table('book_teacher')." t, ".
                                        DB::table('book_video')." v
                                where c.id = t.collegeId and t.id = v.teacherId and v.year=".$year."
                                order by v.month desc"
                            );
    }

    /*
     * 通过视频id查询视频，并带有与视频相关的一些信息，比如作者等
     * */
    public function query_by_videoId($videoId) {
        return DB::fetch_first("  SELECT
                                    c.id as college_id,
                                    c.name as college_name,
                                    t.id as teacher_id,
                                    t.name as teacher_name,
                                    t.number as teacher_number,
                                    t.desc as teacher_desc,
                                    v.*,
									v.content as video_content
                                from ".DB::table('book_college')." c, ".DB::table('book_teacher')." t, ".
            DB::table('book_video')." v
                                where c.id = t.collegeId and t.id = v.teacherId and v.id=".$videoId."
                                order by v.month desc"
        );
    }

    public function insert($name, $teacherId, $year, $month, $imgFileName, $videoFileName, $content,
                           $return_insert_id = false, $replace = false, $silent = false) {
        $query = DB::query('select * from '.DB::table('book_video').' where year='.$year.' and month='.$month);
        //if(DB::num_rows($query) > 0) {
        //    return "您选择的年份和月份已经上传过视频，只能修改！";
        //} else {
            //当前时间
            $time = date("Y-m-d H:i:s");
            $setarr = array(
                'name' => $name,
                'teacherId' => $teacherId,
                'year' => $year,
                'month' => $month,
                'imgFileName' => $imgFileName,
                'videoFileName' => $videoFileName,
                'content' => $content,
                'uploadTime' => $time,
                'updateTime' => $time
            //);
            DB::insert($this->_table, $setarr, $return_insert_id, $replace, $silent);
            return "上传视频成功！";
        }
    }

    public function update($id, $name, $teacherId, $year, $month, $imgFileName, $videoFileName, $content, $uploadTime) {
        $query = DB::query('select * from '.DB::table('book_video').' where year='.$year.' and month='.$month.' and id <> '.$id);
        if(DB::num_rows($query) > 0) {
            return "视频信息修改失败，存在".$year."年".$month."月的视频！";
        } else {
            //当前时间
            $time = date("Y-m-d H:i:s");
            DB::update( $this->_table,
                array(
                    'name' => $name,
                    'teacherId' => $teacherId,
                    'year' => $year,
                    'month' => $month,
                    'imgFileName' => $imgFileName,
                    'videoFileName' => $videoFileName,
                    'content' => $content,
                    'uploadTime' => $uploadTime,
                    'updateTime' => $time

                ),
                array('id'=> $id)
            );
            return "视频信息修改成功！";
        }
    }
    public function delete($id) {
        if(!$id) {
            return "视频删除失败，id为空！";
        }
        DB::delete($this->_table, DB::field('id', $id));
        return "视频删除成功！";
    }
	
	public function queryLastVideo() {
        return DB::fetch_first("  SELECT
                                    c.id as college_id,
                                    c.name as college_name,
                                    t.id as teacher_id,
                                    t.name as teacher_name,
                                    t.number as teacher_number,
                                    v.*
                                from ".DB::table('book_college')." c, ".DB::table('book_teacher')." t, ".
            DB::table('book_video')." v
                                where c.id = t.collegeId and t.id = v.teacherId
                                order by v.year desc,v.month desc LIMIT 1"
        );
	}
}



?>