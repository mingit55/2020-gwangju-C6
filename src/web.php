<?php
use App\Router;

Router::get("/init/festivals", "ActionController@initFestival");

Router::get("/", "ViewController@main");
Router::get("/notice", "ViewController@notice");
Router::get("/exchange-guide", "ViewController@exchangeGuide");

/**
 * 전북대표축제
 */

Router::get("/main-festival", "ViewController@mainFestival");

Router::get("/festivalImages/{imagePath}/{filename}", "ViewController@festivalImage");
Router::get("/api/festivals", "ApiController@getFestivals");

/**
 * 사용자 인증
 */
Router::get("/sign-in", "ViewController@signIn", "guest");

Router::post("/sign-in", "ActionController@signIn", "guest");
Router::get("/sign-out", "ActionController@signOut", "user");


/**
 * 축제정보
 */

Router::get("/festivals", "ViewController@festivals");

Router::get("/download/{type}/{id}", "ActionController@download");
Router::post("/insert/festivals", "ActionController@insertFestival");
Router::post("/update/festivals/{id}", "ActionController@updateFestival");
Router::get("/delete/festivals/{id}", "ActionController@deleteFestival");

/**
 * 축제 정보 상세보기
 */

Router::get("/festivals/{id}", "ViewController@festival");

Router::post("/insert/reviews", "ActionController@insertReview");
Router::get("/delete/reviews/{id}", "ActionController@deleteReview");

/**
 * 축제 일정
 */

Router::get("/schedules", "ViewController@schedules");

/**
 * 데이터 공개
 */
Router::get("/open-api", "ViewController@openApi");

Router::get("/openApi/festivalList.php", "ApiController@openApi");

Router::start();