<?php
namespace Controller;
use App\DB;
use ZipArchive;
use PharData;

class ActionController {
    function initFestival(){
        DB::query("DELETE FROM festivals");

        $xml = simplexml_load_file(SRC."/init");
        foreach($xml->items->item as $item){
            $id = (int)$item->sn;
            $no = (int)$item->no;
            $name = (string)$item->nm;
            $area = (string)$item->area;
            $location = (string)$item->location;
            $period = (string)$item->dt;
            $content = (string)$item->cn;
            $images = (array)$item->images->image;

            
            $temp = explode("~", str_replace(".", "-", $period));
            $t_start = strtotime($temp[0]);
            $t_end = strtotime(date("Y", $t_start)."-{$temp[1]}");
            if($t_start > $t_end) {
                $t_end = strtotime("+1 Year", $t_end);
            }


            $imagePath = "/". str_pad($id, 3, "0", STR_PAD_LEFT) . "_" . "$no";
        
            
            DB::query("INSERT INTO festivals(id, no, name, area, location, period, content, images, start_date, end_date, imagePath) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                    $id, $no, $name, $area, $location, $period, $content, json_encode($images), date("Y-m-d", $t_start), date("Y-m-d", $t_end), $imagePath
                ]);
        }
    }

    function signIn(){
        checkEmpty();
        extract($_POST);

        if($user_id !== "admin" || $password !== "admin") back("아이디 혹은 비밀번호가 일치하지 않습니다.");
        
        $_SESSION['user'] = true;

        go("/", "로그인 되었습니다.");
    }

    function signOut(){
        unset($_SESSION['user']);
        go("/", "로그아웃 되었습니다.");
    }

    function insertFestival(){
        checkEmpty();
        extract($_POST);

        if(!preg_match("/^([0-9]{4}-[0-9]{2}-[0-9]{2}) ~ ([0-9]{4}-[0-9]{2}-[0-9]{2})$/", $period))
            back("올바른 축제 기간을 입력해 주세요(ex: 2020-07-01 ~ 2020-07-13)");

        $temp = explode(" ~ ", $period);
        $t_start = strtotime($temp[0]);
        $t_end = strtotime($temp[1]);
        $period = date("Y.m.d~", $t_start) . date("m.d", $t_end);
        
        
        $images = $_FILES['images'];
        $imageLength = count($images['name']);
        $first = $images['name'][0];
        $filenames = [];
        $imagePath = "/uploads";

        if($first){
            for($i = 0; $i < $imageLength; $i++){
                $fname = $images['name'][$i];
                $tmp_name = $images['tmp_name'][$i];
                $filename = time() . "-" . $fname;
                
                if(!isImage($fname)) back("이미지 파일만 업로드할 수 있습니다.");

                $filenames[] = $filename;
                move_uploaded_file($tmp_name, FIMAGE."/uploads/$filename");
            }
        }

        DB::query("INSERT INTO festivals (name, period, start_date, end_date, area, location, images, imagePath) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [
            $name, $period, date("Y-m-d", $t_start), date("Y-m-d", $t_end), $area, $location, json_encode($filenames), $imagePath
        ]);

        go("/festivals", "축제를 추가했습니다.");
    }

    function updateFestival($id){
        $festival = DB::find("festivals", $id);
        if(!$festival) back("대상을 찾을 수 없습니다.");
        checkEmpty();
        extract($_POST);


        if(!preg_match("/^([0-9]{4}-[0-9]{2}-[0-9]{2}) ~ ([0-9]{4}-[0-9]{2}-[0-9]{2})$/", $period))
            back("올바른 축제 기간을 입력해 주세요(ex: 2020-07-01 ~ 2020-07-13)");

        $temp = explode(" ~ ", $period);
        $t_start = strtotime($temp[0]);
        $t_end = strtotime($temp[1]);
        $period = date("Y.m.d~", $t_start) . date("m.d", $t_end);
        
        
        $images = $_FILES['images'];
        $imageLength = count($images['name']);
        $first = $images['name'][0];
        $filenames = [];
        $imagePath = "/uploads";

        if($first){
            for($i = 0; $i < $imageLength; $i++){
                $fname = $images['name'][$i];
                $tmp_name = $images['tmp_name'][$i];
                $filename = time() . "-" . $fname;
                
                if(!isImage($fname)) back("이미지 파일만 업로드할 수 있습니다.");

                $filenames[] = $filename;
                move_uploaded_file($tmp_name, FIMAGE."/uploads/$filename");
            }
        }

        $rm_images = !isset($rm_images) ? [] : $rm_images;
        
        foreach(json_decode($festival->images) as $image){
            if(array_search($image, $rm_images) === false){
                $filenames[] = $image;
            }
        }

        DB::query("UPDATE festivals SET name = ?, period = ?, start_date = ?, end_date = ?, area = ?, location = ?, images = ? WHERE id = ?", [
            $name, $period, date("Y-m-d", $t_start), date("Y-m-d", $t_end), $area, $location, json_encode($filenames), $id
        ]);

        go("/festivals", "축제를 수정했습니다.");
    }

    function deleteFestival($id){
        $festival = DB::find("festivals", $id);
        if(!$festival) back("대상을 찾을 수 없습니다.");

        DB::query("DELETE FROM festivals WHERE id = ?", [$id]);
        go("/festivals", "축제를 삭제했습니다.");
    }

    function download($type, $id){
        $festival = DB::find("festivals", $id);
        if(!$festival || array_search($type, ["zip", "tar"]) === false) back("대상을 찾을 수 없습니다.");
 
        $compact = SRC."/download.$type";
        $dirname = FIMAGE . $festival->imagePath;
        $cnt = 0;

        if(is_file($compact)) unlink($compact);
        if($type === "zip"){
            $zip = new ZipArchive();
            $zip->open($compact, ZipArchive::CREATE);
            foreach(json_decode($festival->images) as $image){
                if(is_file($dirname."/$image")){
                    $zip->addFile($dirname."/$image", $image);
                    $cnt++;
                }
            }
            $zip->close();
        }
        else {
            $tar = new PharData($compact);
            foreach(json_decode($festival->images) as $image){
                if(is_file($dirname."/$image")){
                    $tar->addFile($dirname."/$image", $image);
                    $cnt++;
                }
            }
        }
        if($cnt == 0) back("압축할 이미지를 찾을 수 없습니다.");

        header("Content-Disposition: attachement;filename=download.$type");
        readfile($compact);
    }

    function insertReview(){
        checkEmpty();
        extract($_POST);
        $festival = DB::find("festivals", $fid);
        if(!$festival) back("대상을 찾을 수 없습니다.");

        DB::query("INSERT INTO reviews (fid, user_name, score, content) VALUES (?, ?, ?, ?)", [$fid, $user_name, $score, $content]);

        go("/festivals/$fid", "작성했습니다.");
    }

    function deleteReview($id){
        $review = DB::find("reviews", $id);
        if(!$review) back("대상을 찾을 수 없습니다.");

        DB::query("DELETE FROM reviews WHERE id = ?", [$id]);

        go("/festivals/$id", "삭제했습니다.");
    }
}