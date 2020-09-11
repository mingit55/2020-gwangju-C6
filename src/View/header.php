<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>전북축제 On!</title>
    <script src="/js/jquery-3.5.0.min.js"></script>
    <link rel="stylesheet" href="/bootstrap-4.4.1-dist/css/bootstrap.min.css">
    <script src="/bootstrap-4.4.1-dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/common.js"></script>
</head>
<body>
    <div id="roadmap" class="modal fade">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="fx-4">찾아오시는길</div>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

    <input type="checkbox" id="open-aside" hidden>
    <!-- 헤더 영역 -->
    <header>
        <div class="header__top d-none d-lg-block">
            <div class="container h-100 d-between">
                <div class="search">
                    <div class="icon">
                        <i class="fa fa-search"></i>
                    </div>
                    <input type="text" placeholder='Search'>
                </div>
                <div class="other">
                    <select>
                        <option value="한국어">한국어</option>
                        <option value="English">English</option>
                        <option value="中文(简体)">中文(简体)</option>
                    </select>
                    <a href="#">전라북도청</a>
                    <?php if(user()):?>
                        <a href="/sign-out">로그아웃</a>
                    <?php else:?>
                        <a href="/sign-in">로그인</a>
                    <?php endif;?>
                    <a href="#">회원가입</a>
                </div>
            </div>
        </div>
        <div class="header__bottom">
            <div class="container h-100 d-between">
                <a href="#">
                    <img src="/images/logo.svg" alt="전북 축제 On!" title="전북 축제 On!" height="50">
                </a>
                <div class="nav d-none d-lg-flex">
                    <div class="nav__item"><a href="/">HOME</a></div>
                    <div class="nav__item"><a href="/main-festival">전북 대표 축제</a></div>
                    <div class="nav__item"><a href="/festivals">축제 정보</a></div>
                    <div class="nav__item"><a href="/schedules">축제 일정</a></div>
                    <div class="nav__item"><a href="/exchange-guide">환율안내</a></div>
                    <div class="nav__item">
                        <a href="/notice">종합지원센터</a>
                        <div class="nav__list">
                            <a href="/notice">공지사항</a>
                            <a href="#">센터 소개</a>
                            <a href="#">관광정보 문의</a>
                            <a href="/open-api">공공 데이터 개방</a>
                            <a href="#" data-toggle="modal" data-target="#roadmap">찾아오시는 길</a>
                        </div>
                    </div>
                </div>
                <label for="open-aside" class="icon-bars d-lg-none">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
            </div>
        </div>
    </header>
    <!-- /헤더 영역 -->
    
    <!-- 사이드 바 -->
    <aside class="d-lg-none">
        <label for="open-aside" class="aside__background"></label>
        <div class="aside__content">
            <div class="search search--aside">
                <div class="icon">
                    <i class="fa fa-search"></i>
                </div>
                <input type="text" placeholder='Search'>
            </div>
            <div class="other other--aside mt-3">
                <select>
                    <option value="한국어">한국어</option>
                    <option value="English">English</option>
                    <option value="中文(简体)">中文(简体)</option>
                </select>
                <a href="#">전라북도청</a>
                <?php if(user()):?>
                    <a href="/sign-out">로그아웃</a>
                <?php else:?>
                    <a href="/sign-in">로그인</a>
                <?php endif;?>
                <a href="#">회원가입</a>
            </div>
            <div class="nav nav--aside mt-3">
                <div class="nav__item"><a href="/">HOME</a></div>
                <div class="nav__item"><a href="/main-festival">전북 대표 축제</a></div>
                <div class="nav__item"><a href="/festivals">축제 정보</a></div>
                <div class="nav__item"><a href="/schedules">축제 일정</a></div>
                <div class="nav__item"><a href="/exchange-guide">환율안내</a></div>
                <div class="nav__item">
                    <a href="/notice">종합지원센터</a>
                    <div class="nav__list">
                        <a href="/notice">공지사항</a>
                        <a href="#">센터 소개</a>
                        <a href="#">관광정보 문의</a>
                        <a href="/open-api">공공 데이터 개방</a>
                        <a href="#" data-toggle="modal" data-target="#roadmap">찾아오시는 길</a>
                    </div>
                </div>
            </div>
        </div>
    </aside>
    <!-- /사이드 바 -->