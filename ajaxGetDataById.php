<?php
/**
 * Created by PhpStorm.
 * User: user240
 * Date: 2019/12/13
 * Time: 11:43
 */
session_start();
function getExcelDateById($id){
    $result=[];
    foreach($_SESSION['array_diff'] as $k=>$v){
        foreach ($v as $k2=>$v2){
            if($id==$k2){
                $result['row']=$v2;
                $result['category']=$k;
                break;
            }
        }
    }
    return $result;
}
$id=$_POST['id'];
if(empty($id) || !ctype_digit( $id )){
    echo json_encode(['code'=>1,'data'=>'请输入产品系统ID']);die;
}
$excelDate=getExcelDateById($id);
if(empty($excelDate)){
    echo json_encode(['code'=>1,'data'=>'没有相关的数据']);die;
}
echo json_encode(['code'=>0,'data'=>$excelDate]);
//var_dump(getExcelDate('delete',10,5));die;
