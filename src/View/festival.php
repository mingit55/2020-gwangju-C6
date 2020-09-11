<div class="position-relative hx-400">
    <div class="background background--black">
        <img src="/images/visual/1.jpg" alt="비주얼 이미지" title="비주얼 이미지">
    </div>
    <div class="position-center text-center mt-5">
        <div class="fx-8 text-white font-weight-lighter">축제 정보</div>
        <div class="fx-n2 text-gray mt-2">
            전북 축제 On!
            <i class="fa fa-angle-right mx-1"></i>
            축제 정보
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="mt-4 pb-3 mb-3 border-bottom">
        <div class="title bar-left">축제 상세 정보</div>
    </div>
    <div class="row">
        <div class="col-lg-5">
            <?php if($festival->images[0]):?>
                <img src="/festivalImages<?=$festival->imagePath?>/<?=$festival->images[0]?>" alt="이미지" class="fit-cover hx-300">
            <?php endif;?>
        </div>
        <div class="col-lg-6">
            <div class="fx-4"><?=enc($festival->name)?></div>
            <div class="mt-2">
                <span class="fx-n2 text-muted">지역</span>
                <span class="fx-n1 ml-1"><?=enc($festival->area)?></span>
            </div>
            <div class="mt-2">
                <span class="fx-n2 text-muted">장소</span>
                <span class="fx-n1 ml-1"><?=enc($festival->location)?></span>
            </div>
            <div class="mt-2">
                <span class="fx-n2 text-muted">기간</span>
                <span class="fx-n1 ml-1"><?=enc($festival->period)?></span>
            </div>
            <div class="mt-4 textarea"><?=enc($festival->content)?></div>
        </div>
    </div>
    <div class="mt-4 pb-3 mb-3 border-bottom">
        <div class="title bar-left">축제사진</div>
    </div>
    <div class="row">
        <?php foreach($festival->images as $image):?>
            <div class="col-lg-3 mb-4">
                <img class="fit-cover hx-200" src="/festivalImages<?=$festival->imagePath?>/<?=$image?>" alt="이미지">
            </div>
        <?php endforeach;?>
    </div>
    <div class="mt-4 pb-3 mb-3 border-bottom d-between">
        <div class="title bar-left">축제 후기</div>
        <button class="btn-filled" data-toggle="modal" data-target="#insert-form">후기 등록</button>
    </div>
    <?php foreach($reviews as $review):?>
    <div class="py-3 border-bottom d-between">
        <div>
            <div>
                <span class="fx-3"><?=$review->user_name?></span>
                <span class="ml-3 text-red">
                    <?= str_repeat('<i class="fa fa-star"></i>', $review->score) ?>
                </span>
            </div>
            <div class="mt-3 textarea"><?=enc($review->content)?></div>
        </div>
        <?php if(user()):?>
            <a href="/delete/reviews/<?=$review->id?>" class="btn-filled">삭제</a>
        <?php endif;?>
    </div>
    <?php endforeach;?>
</div>

<form action="/insert/reviews" id="insert-form" class="modal fade" method="post">
    <input type="hidden" name="fid" value="<?=$festival->id?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-between">
                <div class="fx-4">후기 등록</div>
                <button class="icon" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
            <div class="form-group">
                    <label>이름</label>
                    <input type="text" class="form-control" name="user_name">
                </div>
                <div class="form-group">
                    <label>별점</label>
                    <select name="score" id="score" class="form-control">
                        <option value="5">5점</option>
                        <option value="4">4점</option>
                        <option value="3">3점</option>
                        <option value="2">2점</option>
                        <option value="1">1점</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>후기</label>
                    <textarea name="content" id="content" cols="30" rows="10" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-filled">저장</button>
            </div>
        </div>
    </div>
</form>