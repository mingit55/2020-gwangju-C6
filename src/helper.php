<?php
function dump(){
    foreach(func_get_args() as $arg) {
        echo "<pre>";
        var_dump($arg);
        echo "</pre>";
    }
}
function dd(){
    foreach(func_get_args() as $arg) {
        echo "<pre>";
        var_dump($arg);
        echo "</pre>";
    }
    exit;
}

function user(){
    return isset($_SESSION['user']) ? $_SESSION['user'] : false;
}

function go($url, $message = ""){
    echo "<script>";
    if($message !== "") echo "alert('$message');";
    echo "location.href='$url';";
    echo "</script>";
    exit;
}

function back($message = ""){
    echo "<script>";
    if($message !== "") echo "alert('$message');";
    echo "history.back();";
    echo "</script>";
    exit;
}

function json_response($data){
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function view($viewname, $data = []){
    extract($data);

    require VIEW."/header.php";
    require VIEW."/$viewname.php";
    require VIEW."/footer.php";
    exit;
}

function checkEmpty(){
    foreach($_POST as $input){
        if(!is_array($input) && trim($input) === "")
            back("모든 정보를 기입해 주세요!");
    }
}

function extname($filename){
    return strtolower(substr($filename, strrpos($filename, ".")));
}

function isImage($filename){
    return array_search(extname($filename), [".jpg", ".png", ".gif"]) !== false;
}

function enc($output){
    return nl2br(str_replace(" ", "&nbsp;", htmlentities($output)));
}

function dt($time){
    return date("Y-m-d", strtotime($time));
}

function pagination($data){
    $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] >= 1 ? $_GET['page'] : 1;

    define("COUNT", 10);
    define("BCOUNT", 5);
    
    $totalPage = ceil(count($data) / COUNT);
    $c_block = ceil($page / BCOUNT);

    $start = ($c_block - 1) * BCOUNT + 1;
    $end = $start + BCOUNT - 1;
    $end = $end > $totalPage ? $totalPage : $end;

    $prevPage = $start - 1;
    $prev = $prevPage >= 1;
    
    $nextPage = $end + 1;
    $next = $nextPage <= $totalPage;

    $data = array_slice($data, ($page - 1) * COUNT, COUNT);

    return (object)compact("start" ,"end", "next", "nextPage", "prev", "prevPage", "data", "page");
}