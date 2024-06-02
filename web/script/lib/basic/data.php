<?php
// 定义一个nulla数组，用于存放空值
define("nulla",array());
// 定义一个notdata数组，用于存放需要忽略的文件
$notdata=array(".","..","Thumbs.db","0.hid");
// 定义一个DB类
class DB{
    // 定义一个静态方法，用于获取数据
    public static function getdata($path){
        // 定义一个变量，用于存放获取数据的路径
        $path="../data/class19/$path.php";
        // 判断路径是否存在，如果不存在，则返回nulla数组
        if(!file_exists($path)) return array();
        // 如果路径存在，则返回该路径下的数据
        return require $path;
    }
    // 定义一个静态方法，用于存放数据
    public static function putdata($dataname,$data){
        // 定义一个变量，用于存放存放数据的路径
        $datapath="../data/class19/$dataname.php";
        // 将数据写入到路径下，并返回写入是否成功
        return file_put_contents($datapath,"<?php return ".var_export($data,1).";?>");
    }
    // 定义一个静态方法，用于扫描数据
    public static function scanData($path,$page=0,$limit=100,$raw=1){
        // 定义一个变量，用于存放扫描数据的路径
        $path="../data/class19/$path/";
        // 判断路径是否存在，如果不存在，或者不是一个目录，则返回nulla数组
        if(!file_exists($path)||!is_dir($path)){
            return nulla;
        }
        // 获取路径下的文件列表
        $r=scandir($path);
        // 定义一个数组，用于存放扫描到的数据
        $datalist=[];
        $datas=[];
        // 遍历文件列表
        foreach($r as $k=>$v){
            // 定义一个全局变量，用于存放需要忽略的文件
            global $notdata;
            // 判断文件是否需要忽略
            if(in_array($v,$notdata)){
                continue;
            }
            // 将文件名中的.php替换为空
            $v=str_replace(".php","",$v);
            // 将文件名作为key，文件内容作为value，存入到datas数组中
            $datalist[$k]=$v;
        }
        $maxlimit=max(count($datalist)-1,0);
        $allpage=ceil(count($datalist)/$limit);
        if($page!=0){
            $start = min($limit*($page-1),$maxlimit);
            $end = min($limit*$page-1,$maxlimit);
        }else{
            $start = 0;
            $end = $maxlimit;
        }

        $datalist=array_reverse($datalist);
        for($i=$start;$i<=$end;$i++){
            if($raw){
                $datas[$datalist[$i]]=DB::getdata($path.$datalist[$i]);
                continue;
            }
            $datas[$i]=DB::getdata($path.$datalist[$i]);
        }
        if(count($datalist)==0) $datas=[];
        $rt['start']=$start;
        $rt['end']=$end;
        $rt['data']=$datas;
        $rt['allpage']=$allpage;
        // 返回datas数组
        if($raw===1) return $datas;
        else return $rt;
    }
    public static function rmdata($dataname){
        // 定义一个变量，用于存放存放数据的路径
        $datapath="../data/class19/$dataname.php";
        // 判断路径是否存在，如果不存在，或者不是一个目录，则返回nulla数组
        if (file_exists($datapath)&&!is_dir($datapath)){
            return unlink($datapath);
        }
        // 删除文件
        // 返回删除是否成功
        return 0;
    }
    public static function scanName($base){
        $dir="../data/class19/$base/";
        $data=[];
        //判断路径合法性
        if(!file_exists($dir)||!is_dir($dir)){
            return nulla;
        }
        $r=scandir($dir);
        foreach($r as $k=>$v){
            //去除.. . 系统文件 *.db *.hid(正则表达式)
            if($v==="."||$v===".."||$v==="0.hid"||preg_match("/^.*\.db$/",$v)||preg_match("/^.*\.hid$/",$v)){
                //删除$r的该元素
                continue;
            }else{
                $v=str_replace(".php","",$v);
                $data[]=$v;
            }
        }
        //返回$r
        return $data;
    }
    public static function createbase($path,$basename){
        return mkdir("../data/class19/$path/$basename");
    }
    public static function countName($base){
        return count(DB::scanName($base));
    }
}