<?php
/**
 * Created by PhpStorm.
 * User: user240
 * Date: 2019/12/13
 * Time: 11:43
 */
session_start();
function getExcelDate($category,$startLine,$getDataNums){
    $result=[];
    foreach($_SESSION['array_diff'][$category] as $k=>$v){
        $result[]=$v;
    }
    return array_slice($result,$startLine,$getDataNums);
}
$category=$_POST['category'];
$startLine=$_POST['startLine'];
$getDataNums=$_POST['endLine'];

if(empty($_SESSION['array_diff'][$category])){
    echo json_encode(['code'=>1,'data'=>'没有数据了']);die;
}
$excelDate=getExcelDate($category,$startLine,$getDataNums);
if(empty($excelDate)){
    echo json_encode(['code'=>1,'data'=>'没有数据了']);die;
}
echo json_encode(['code'=>0,'data'=>$excelDate]);
