<style>
    .calender {
        display: flex;
        flex-wrap: wrap;
        border-left: 1px solid #aaa;
        border-top: 1px solid #aaa;
        overflow: hidden;
        counter-set: day;
    }

    .calender .head {
        height: 40px;
        line-height: 40px;
        font-size: 0.9em;
        color: #555;
        border-right: 1px solid #aaa;
        border-bottom: 1px solid #aaa;
        width: calc(100% / 7);
        text-align: center;
    }

    .calender .body {
        position: relative;
        height: 150px;
        border-right: 1px solid #aaa;
        border-bottom: 1px solid #aaa;
        width: calc(100% / 7);
    }
    
    .calender .body:not(.disabled)::before {
        content: counter(day);
        counter-increment: day;
        position: absolute;
        left: 0.5em;
        top: 0.5em;
        color: #555;
    }
    .calender .body.disabled { background-color: #f7f7f7; position: relative; z-index: 10; }

    .schedule {
        position: absolute;
        left: 0; top: 3em;
        width: 100%;
    }
    .schedule__item {
        position: relative;
        padding: 0 0.5em;
        width: 100vw;
        height: 30px;
        line-height: 30px;
        font-size: 0.9em;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        background-color: #e02146;
        color: #fff;
        margin-bottom: 0.2em;
        z-index: 2;
    }
    .schedule__item:empty { z-index: 1; }
    .schedule__item:nth-child(n+4) { display: none; }
    .schedule__item.empty { opacity: 0; }

</style>

<div class="position-relative hx-400">
    <div class="background background--black">
        <img src="/images/visual/1.jpg" alt="비주얼 이미지" title="비주얼 이미지">
    </div>
    <div class="position-center text-center mt-5">
        <div class="fx-8 text-white font-weight-lighter">축제 일정</div>
        <div class="fx-n2 text-gray mt-2">
            전북 축제 On!
            <i class="fa fa-angle-right mx-1"></i>
            축제 일정
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="d-between">
        <a href="/schedules?year=<?=date('Y')?>&month=<?=date('m')?>" class="btn-bordered">이번달</a>
        <div>
            <a href="/schedules?year=<?=date('Y', $t_prev)?>&month=<?=date('m', $t_prev)?>" class="btn-bordered">이전달</a>
            <span class="fx-5 mx-3"><?=$year?>년 <?=$month?>월</span>
            <a href="/schedules?year=<?=date('Y', $t_next)?>&month=<?=date('m', $t_next)?>" class="btn-bordered">다음달</a>
        </div>
        <a href="/festivals" class="btn-filled">축제관리</a>
    </div>
    <div class="calender mt-3">
        <div class="head">SUN</div>
        <div class="head">MON</div>
        <div class="head">TUE</div>
        <div class="head">WED</div>
        <div class="head">THU</div>
        <div class="head">FRI</div>
        <div class="head">SAT</div>
        <?php for($i = 0; $i < date("w", $t_first); $i++):?>
            <div class="body disabled"></div>
        <?php endfor;?>

        <?php
            $list = [null, null, null];
            for($day = 1; $day <= date("d", $t_last); $day++):
                foreach($schedules as $item) {
                    if($item->start == $day){
                        $idx = array_search(null, $list);
                        if($idx !== false){
                            $list[$idx] = $item;
                        }
                    }
                }

                foreach($list as $idx => $item){
                    if($item && $item->end < $day) {
                        $list[$idx] = null;
                    }
                }
        ?>

        <div class="body">
            <div class="schedule">
                <?php foreach($list as $item):?>
                    <?php if($item !== null):?>
                        <div class="schedule__item" onclick="location.href='/festivals/<?=$item->id?>'" title="<?= $item->name ?>(<?= $item->period ?>)"><?php if($item->start == $day):?><?= $item->name ?>(<?= $item->period ?>)<?php endif;?></div>
                    <?php else:?>
                        <div class="schedule__item empty"></div>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
        </div>

        <?php endfor;?>

        <?php for($i = 0; $i < 6 - date("w", $t_last); $i++):?>
            <div class="body disabled"></div>
        <?php endfor;?>
    </div>
</div>