<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>excel表格对比结果</title>
    <style>
        body {
            font-family: "Lato", sans-serif;
        }

        div.tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
            width: <?php echo (count($excel_new_header)*150).'px';?>;
        }

        div.tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
        }

        div.tab button:hover {
            background-color: #ddd;
        }

        div.tab button.active {
            background-color: #ccc;
        }

        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
            width: <?php echo (count($excel_new_header)*150).'px';?>;
        }
    </style>
</head>

<body>
<div class="tab">
    <button class="tablinks" onclick="openCity(event, 'modifyTab')">修改行</button>
    <button class="tablinks" onclick="openCity(event, 'addTab')">添加行</button>
    <button class="tablinks" onclick="openCity(event, 'deleteTab')">删除行</button>
    <button class="tablinks" onclick="openCity(event, 'addLine')">增加列</button>
    <button class="tablinks" onclick="openCity(event, 'deleteLine')">删除列</button>
    <div>
        <input type="search" id="search" name="search" style="float: left;margin-top: 14px;">
        <button onclick="getDataById()">搜索</button>
    </div>
</div>

<div id="hiddenTab" class="tabcontent">
    <h3 id="title"></h3>
    <div style="display: inline-flex;width: <?php echo (count($excel_new_header) * 150) . 'px'; ?>;">
        <?php foreach ($excel_new_header as $k => $v) { ?>
            <div style="float: left;width:150px;"><?php echo $v; ?></div>
        <?php } ?>
    </div>
    <div id="search_content" style="height: 50px;">

    </div>
</div>
<div id="modifyTab" class="tabcontent">
    <h3>修改数据数量：<?php if (!empty($array_diff['modify'])) echo count($array_diff['modify']); else echo 0; ?>（条）</h3>
    <div style="display: inline-flex;width: <?php echo (count($excel_new_header) * 150) . 'px'; ?>;">
        <?php foreach ($excel_new_header as $k => $v) { ?>
            <div style="float: left;width:150px;"><?php echo $v; ?></div>
        <?php } ?>
    </div>
    <div id="modify">
        <?php if (empty($array_diff['modify'])) {
            echo "空";
        } else {
            foreach ($array_diff['modify'] as $k => $v) { ?>
                <div style="display: inline-flex;">
                    <?php
                    $i = 1;
                    foreach ($array_diff['modify'][$k] as $k2 => $v2) {
                        if ($i > 500) break; else $i++;
                        ?>
                        <div style="float: left;width:150px;"><?php echo $v2 . "<br>"; ?></div>
                    <?php } ?>
                </div>
            <?php }
        } ?>
    </div>
    <button id="modify_p" onclick="getData('modify','prev','modify_p')">上一页</button>
    <button id="modify_n" onclick="getData('modify','next','modify_n')">下一页</button>
</div>
<div id="addTab" class="tabcontent">
    <h3>添加数据数量：<?php if (!empty($array_diff['add'])) echo count($array_diff['add']); else echo 0; ?>（条）</h3>
    <div style="display: inline-flex;width: <?php echo (count($excel_new_header) * 150) . 'px'; ?>;">
        <?php foreach ($excel_new_header as $k => $v) { ?>
            <div style="float: left;width:150px;"><?php echo $v; ?></div>
        <?php } ?>
    </div>
    <div id="add_content">
        <?php if (empty($array_diff['add'])) {
            echo "空";
        } else {
            foreach ($array_diff['add'] as $k => $v) { ?>
                <div style="display: inline-flex;">
                    <?php
                    $i = 1;
                    foreach ($array_diff['add'][$k] as $k2 => $v2) {
                        if ($i > 500) break; else $i++;
                        ?>
                        <div style="float: left;width:150px;"><?php echo $v2 . "<br>"; ?></div>
                    <?php } ?>
                </div>
            <?php }
        } ?>
    </div>
    <button id="add_p" onclick="getData('add','prev','add_p')">上一页</button>
    <button id="add_n" onclick="getData('add','next','add_n')">下一页</button>
</div>
<div id="deleteTab" class="tabcontent">
    <h3>删除数据数量：<?php if (!empty($array_diff['delete'])) echo count($array_diff['delete']); else echo 0; ?>（条）</h3>
    <div style="display: inline-flex;width: <?php echo (count($excel_new_header) * 150) . 'px'; ?>;">
        <?php foreach ($excel_new_header as $k => $v) { ?>
            <div style="float: left;width:150px;"><?php echo $v; ?></div>
        <?php } ?>
    </div>
    <div id="delete_content">
        <?php if (empty($array_diff['delete'])) {
            echo "空";
        } else {
            $i = 1;
            foreach ($array_diff['delete'] as $k => $v) {
                if ($i > 500) break; else $i++;
                ?>
                <div style="display: inline-flex;">
                    <?php
                    foreach ($array_diff['delete'][$k] as $k2 => $v2) {
                        ?>
                        <div style="float: left;width:150px;"><?php echo $v2 . "<br>"; ?></div>
                    <?php } ?>
                </div>
            <?php }
        } ?>
    </div>
    <button id="delete_p" onclick="getData('delete','prev','delete_p')">上一页</button>
    <button id="delete_n" onclick="getData('delete','next','delete_n')">下一页</button>
</div>

<div id="addLine" class="tabcontent">
    <?php
    $add_lines = array_diff_assoc($excel_new_header, $excel_old_header);
    if (empty($add_lines)) {
        echo "空";
    } else {
        foreach ($add_lines as $k => $v) {
            echo $v . "<br>";
        };
    } ?>
</div>
<div id="deleteLine" class="tabcontent">
    <?php
    $delete_lines = array_diff_assoc($excel_old_header, $excel_new_header);
    if (empty($delete_lines)) {
        echo "空";
    } else {
        foreach ($delete_lines as $k => $v) {
            echo $v . "<br>";
        }
    }; ?>
</div>
<script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
<script>
    function openCity(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    var startLine = 1;
    var now_cate='';
    function getData(category, action, id) {
        if (action == "next") {
            ajaxGetData(category, startLine, id);
            startLine++;
        } else if (action == "prev") {
            startLine = startLine - 1;
            if (startLine < 0) {
                startLine = 1;
                $("#"+id).attr('disabled', true);
                alert("没有数据了");
                return false;
            }
            ajaxGetData(category, startLine);

        }
        console.log(startLine);
    }

    function ajaxGetData(category, startLine, id) {
        $.post("ajaxGetData.php", {
                'category': category,
                'startLine': startLine * 500,
                'endLine': 500
            },
            function (result) {
                if (result.code == 1) {
                    alert(result.data);
                    $("#"+id).attr('disabled', true);
                    return false;
                }
                var data = result.data;
                var contentAll = "";
                for (var keys in data) {
                    var content = ""
                    for (var keys2 in data[keys]) {
                        content += "<div style=\"float: left;width:150px;\">" + data[keys][keys2] + "</div>";
                    }
                    contentAll += "<div style=\"display: inline-flex;\">" + content + "</div>";
                }
                $("#" + category + "_content").html(contentAll);
            }, "json");
    }

    function getDataById() {
        $.post("ajaxGetDataById.php", {
                'id': $("#search").val(),
            },
            function (result) {
                if (result.code == 1) {
                    alert(result.data);
                    return false;
                }
                var data = result.data.row;
                var category = result.data.category;
                var content = "";
                for (var keys in data) {
                    content += "<div style=\"float: left;width:150px;\">" + data[keys] + "</div>";
                }
                $("#search_content").html(content);
                $("#hiddenTab").show()
                if (category == "modify") {
                    $("#title").html("该数据已被修改");
                } else if (category == "add") {
                    $("#title").html("该数据是新增数据");
                } else if (category == "delete") {
                    $("#title").html("该数据已被删除");
                }
            }, "json");
    }
</script>
</body>

</html>