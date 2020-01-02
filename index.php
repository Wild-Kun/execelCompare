<?php
set_time_limit(0);
header("Content-Type: text/html;charset=utf-8");
ini_set('memory_limit', '-1');
session_start();
//获取excel数据
function getExcelData($file_name, $line_star = 1, $line_end = 1000)
{
    $encoding = detect_encoding($file_name);
    if (!$encoding) {
        echo "文件格式不正确";
        die;
    }
    $n = 0;
    $handle = fopen($file_name, "r");
    if ($handle) {
        while (!feof($handle)) {
            ++$n;
            $out = fgets($handle, 4096);
            if ($line_star <= $n) {
                $ling[] = $out;
            }
            if ($line_end == $n) break;
        }
        $rowData = eval('return ' . iconv($encoding, 'utf-8', var_export($ling, true)) . ';');
        $rowData_final = [];
        foreach ($rowData as $k => $v) {
            $rowData2[] = explode(',', $v);
        }
        $rowDataHeader = reset($rowData2);
        foreach ($rowData2 as $k => $v) {
            $id = $v[0];
            foreach ($rowDataHeader as $k2 => $v2) {
                $rowData_final[$id][$v2] = $v[$k2];
            }
        }
        fclose($handle);
        return $rowData_final;
    } else {
        return 'error';
    }
}

//比较excel数据差异
function array_diff_deep($excel_old, $excel_new)
{
    $result = array();
    foreach ($excel_old as $k => $v) {
        $id = reset($excel_old[$k]);
        if (isset($excel_new[$id])) {
            foreach ($excel_old[$k] as $k2 => $v2) {
                if (!isset($excel_new[$id][$k2])) {
                    continue;
                }
                if ($excel_new[$id][$k2] != $v2) {
                    $result['modify'][$id] = $excel_new[$k];
                    $array_diff_temp [$id][$k2] = "<span style='color:red;'>" . $excel_new[$id][$k2] . "</span>";
                }
            }


        } else {
            $result['delete'][$id] = $excel_old[$k];
        }

    }


    foreach ($excel_new as $k => $v) {
        $id = reset($excel_new[$k]);
        if (!isset($excel_old[$id])) {
            $result['add'][$id] = $excel_new[$id];
        }
        if (isset($array_diff_temp[$id])) {
            foreach ($array_diff_temp[$id] as $k => $v) {
                $result['modify'][$id][$k]=$v;
            }
        }
    }
//    echo "<pre>";
//    var_dump($result['modify']);
//    echo "</pre>";
    return $result;
}

//判断文件编码
function detect_encoding($file)
{
    $list = array('gbk', 'utf-8', 'utf-16le', 'utf-16be', 'ISO-8859-1');
    $str = file_get_contents($file);
    foreach ($list as $item) {
        $tmp = mb_convert_encoding($str, $item, $item);
        if (md5($tmp) == md5($str)) {
            return $item;
        }
    }
    return null;
}

$fileInfo_old = $_FILES["excel_old"];
$fileInfo_new = $_FILES["excel_new"];
$old_line_end = $_POST['excel_old_line_nums'];
$new_line_end = $_POST['excel_new_line_nums'];
if(empty($old_line_end)|| empty($new_line_end)){
    echo "请填写行数";die;
}
$filePath_old = $fileInfo_old["tmp_name"];
$filePath_new = $fileInfo_new["tmp_name"];
if(empty($filePath_old)|| empty($filePath_new)){
    echo "请上传文件";die;
}

$excel_old = getExcelData($filePath_old, 1, $old_line_end);
$excel_new = getExcelData($filePath_new, 1, $new_line_end);

$_SESSION['array_diff'] = array_diff_deep($excel_old, $excel_new);
$array_diff = $_SESSION['array_diff'] ;
//var_dump($array_diff);
//die;
$excel_old_header = reset($excel_old);
$excel_new_header = reset($excel_new);
include_once "compare.php";
?>
