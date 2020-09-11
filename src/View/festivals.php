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
    <div class="d-between border-bottom pb-3 mb-3">
        <div>
            <div class="title bar-left">축제 정보</div>
        </div>
        <?php if(user()):?>
            <button class="btn-filled" data-toggle="modal" data-target="#insert-form">축제 등록</button>
        <?php endif;?>
    </div>
    <div class="t-head">
        <div class="cell-10">번호</div>
        <div class="cell-40">축제명(사진)</div>
        <div class="cell-20">다운로드</div>
        <div class="cell-20">기간</div>
        <div class="cell-10">장소</div>
    </div>
    <?php foreach($festivals->data as $festival):?>
        <!-- 데이터 행 -->
        <div class="t-row">
            <div class="cell-10" <?= user() ? "data-toggle='modal' data-target='#update-modal-$festival->id'" : "" ?>><?=$festival->id?></div>
            <div class="cell-40" onclick="location.href='/festivals/<?=$festival->id?>'">
                <?=enc($festival->name)?>
                <span class="badge badge-danger"><?=count($festival->images)?></span>
            </div>
            <div class="cell-20">
                <a href="/download/tar/<?=$festival->id?>" class="btn-bordered">tar</a>
                <a href="/download/zip/<?=$festival->id?>" class="btn-bordered">zip</a>
            </div>
            <div class="cell-20"><?= $festival->period ?></div>
            <div class="cell-10"><?= $festival->area ?></div>
        </div>
        <!-- /데이터 행 -->
        <!-- 수정 모달 -->
        <form id="update-modal-<?=$festival->id?>" class="modal fade" action="/update/festivals/<?=$festival->id?>" method="post" enctype="multipart/form-data">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header d-between">
                        <div class="fx-4">축제 관리</div>
                        <button class="icon" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>축제명</label>
                            <input type="text" class="form-control" name="name" value="<?=$festival->name?>">
                        </div>
                        <div class="form-group">
                            <label>기간</label>
                            <input type="text" class="form-control" name="period" value="<?=dt($festival->start_date)?> ~ <?=dt($festival->end_date)?>">
                        </div>
                        <div class="form-group">
                            <label>지역</label>
                            <input type="text" class="form-control" name="area" value="<?=$festival->area?>">
                        </div>
                        <div class="form-group">
                            <label>장소</label>
                            <input type="text" class="form-control" name="location" value="<?=$festival->location?>">
                        </div>
                        <div class="form-group">
                            <label>사진 관리</label>
                            <small class="text-muted">삭제하고 싶은 사진을 선택하세요</small>
                            <div class="p-2 border bg-light d-flex flex-wrap">
                                <input type="checkbox" name="rm_images[]" value="" hidden>
                                <?php foreach($festival->images as $image):?>
                                <span class="m-1">
                                    <input type="checkbox" name="rm_images[]" value="<?=$image?>">
                                    <?=$image?>
                                </span>
                                <?php endforeach;?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>사진 업로드</label>
                            <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-filled">저장</button>
                        <button class="btn-bordered" data-dismiss="modal">닫기</button>
                        <a href="/delete/festivals/<?=$festival->id?>" class="btn-bordered">삭제</a>
                    </div>
                </div>
            </div>
        </form>
        <!-- /수정 모달 -->
    <?php endforeach;?>
    <div class="mt-4 d-center">
        <a href="/festivals?page=<?=$festivals->prevPage?>" class="icon mx-1 bg-red text-white" <?=!$festivals->prev ? "disabled" : ""?>>
            <i class="fa fa-angle-left"></i>
        </a>
        <?php for($i = $festivals->start; $i <= $festivals->end; $i++):?>
            <a href="/festivals?page=<?=$i?>" class="icon mx-1 <?=$i == $festivals->page ? 'bg-red text-white' : 'border border-red text-red'?>"><?=$i?></a>
        <?php endfor;?>
        <a href="/festivals?page=<?=$festivals->nextPage?>" class="icon mx-1 bg-red text-white" <?=!$festivals->next ? "disabled" : ""?>>
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div>

<form action="/insert/festivals" id="insert-form" class="modal fade" method="post" enctype="multipart/form-data">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-between">
                <div class="fx-4">축제 관리</div>
                <button class="icon" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
            <div class="form-group">
                    <label>축제명</label>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="form-group">
                    <label>기간</label>
                    <input type="text" class="form-control" name="period" placeholder="ex: 2020-07-01 ~ 2020-07-13">
                </div>
                <div class="form-group">
                    <label>지역</label>
                    <input type="text" class="form-control" name="area">
                </div>
                <div class="form-group">
                    <label>장소</label>
                    <input type="text" class="form-control" name="location">
                </div>
                <div class="form-group">
                    <label>사진 업로드</label>
                    <input type="file" class="form-control" name="images[]" multiple accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-filled">저장</button>
                <button class="btn-bordered" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</form>