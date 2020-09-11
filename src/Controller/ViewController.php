<?php
namespace Controller;
use App\DB;

class ViewController {
    function main(){
        view("main");
    }

    function notice(){
        view("notice");
    }

    function mainFestival(){
        view("main-festival");
    }

    function exchangeGuide(){
        view("exchange-guide");
    }

    function festivalImage($imagePath, $filename){
        $path = FIMAGE."/$imagePath/$filename";
        if(is_file($path)){
            header("Content-Type: image/jpeg");
            readfile(FIMAGE."/$imagePath/$filename");
        }
    }

    function festivals(){
        view("festivals", [
            "festivals" => pagination(
                array_map(function($festival){
                    $festival->images = json_decode($festival->images);
                    return $festival;
                }, DB::fetchAll("SELECT * FROM festivals ORDER BY id DESC"))
            )
        ]);
    }

    function signIn(){
        view("sign-in");
    }

    function festival($id){
        $festival = DB::find("festivals", $id);
        if(!$festival) back("대상을 찾을 수 없습니다.");
        $festival->images = json_decode($festival->images);

        $reviews = DB::fetchAll("SELECT * FROM reviews WHERE fid = ?", [$id]);

        view("festival", compact("festival", "reviews"));
    }

    function schedules(){
        $year = isset($_GET['year']) ? $_GET['year'] : date("Y");
        $month = isset($_GET['month']) ? $_GET['month'] : date("m");

        $t_first = strtotime("$year-$month-1");
        $t_last = strtotime("-1 Day", strtotime("+1 Month", $t_first));

        $t_next = strtotime("+1 Month", $t_first);
        $t_prev = strtotime("-1 Month", $t_first);

        $schedules = DB::fetchAll("SELECT 
                                        id, name, period,
                                        IF(start_date < ?, 1, DATE_FORMAT(start_date, '%d')) start,
                                        IF(end_date > ?, ?, DATE_FORMAT(end_date, '%d')) end
                                    FROM festivals
                                    WHERE (? BETWEEN start_date AND end_date)
                                    OR (? BETWEEN start_date AND end_date)
                                    OR (start_date BETWEEN ? AND ?)
                                    OR (end_date BETWEEN ? AND ?)", [
                                        date('Y-m-d', $t_first), date('Y-m-d', $t_last), date('d', $t_last),
                                        date('Y-m-d', $t_first), date('Y-m-d', $t_last),
                                        date('Y-m-d', $t_first), date('Y-m-d', $t_last),
                                        date('Y-m-d', $t_first), date('Y-m-d', $t_last),
                                    ]);
        view("schedules", compact("year", "month", "t_first", "t_last", "t_next", "t_prev", "schedules"));
    }
    
    function openApi(){
        view("open-api");
    }
}