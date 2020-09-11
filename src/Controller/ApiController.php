<?php
namespace Controller;
use App\DB;

class ApiController {
 
    function getFestivals(){
        json_response(
            array_map(function($festivals){
                $festivals->images = json_decode($festivals->images);
                $festivals->imagePath = "/festivalImages". $festivals->imagePath;
                return $festivals;
            }, DB::fetchAll("SELECT * FROM festivals"))
        );
    }

    function openApi(){
        $year = isset($_GET['year']) ? $_GET['year'] : null;
        $month = isset($_GET['month']) ? $_GET['month'] : null;
        $searchType = isset($_GET['searchType']) ? $_GET['searchType'] : null;

        if(!$searchType || !$year || array_search($searchType, ['Y', 'M']) === false){
            json_response("조회 구분 혹은 연도가 입력하지 않았습니다.");
        }
        else if($searchType == "M" && !$month){
            json_response("월별 조회는 월을 입력해야 합니다.");
        }

        $temp = "$year";
        if($searchType === "M") $temp .= "-$month";
        else $temp .= "-01";
        $temp .= "-01";

        $input = $searchType == 'Y' ? date("Y-%", strtotime($temp)) :  date("Y-m%", strtotime($temp));

        $items = array_map(function($festival){
                    $festival->images = json_decode($festival->images);
                    return $festival;
                }, DB::fetchAll("SELECT
                                    id sn,
                                    no,
                                    name nm,
                                    location,
                                    period dt,
                                    content cn,
                                    images
                                FROM festivals
                                WHERE start_date LIKE ?", [$input]));

        $totalCount = count($items);
        json_response(compact("searchType", "year", "month", "totalCount", "items"));
    }
}