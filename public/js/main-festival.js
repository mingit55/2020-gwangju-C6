class App {
    constructor(){
        this.init();
    }

    async init(){
        this.festivals = await this.getFestivals();

        this.render();
        this.setEvents();
    }


    // DOM 렌더링
    render(){
        let {page, type} = location.getQueryString();
        page = !isNaN(parseInt(page)) && page >= 1 ? page : 1;
        type = !["normal", "list"].includes(type) ? "normal" : type;

        const COUNT = type == 'normal' ? 6 : 10;
        const BCOUNT = 5;

        let totalPage = Math.ceil(this.festivals.length / COUNT);
        let c_block = Math.ceil(page / BCOUNT);

        let start = (c_block - 1) * BCOUNT + 1;
        let end = start + BCOUNT - 1;
        end = end > totalPage ? totalPage : end;

        let prevPage = start - 1;
        let prev = prevPage >= 1;

        let nextPage = end + 1;
        let next = nextPage <= totalPage;

        let sp = (page-1) * COUNT;
        let ep = sp + COUNT;
        let data = this.festivals.slice(sp, ep);

        $(".link__" + type).addClass("text-red");

        $(".paging").html(`<a href="?page=${prevPage}&type=${type}" class="icon mx-1 bg-red text-white" ${!prev ? "disabled" : ""}>
                                <i class="fa fa-angle-left"></i>
                            </a>`);
        for(let i = start; i <= end; i++){
            $(".paging").append(`<a href="?page=${i}&type=${type}" class="icon mx-1 ${page == i ? 'bg-red text-white' : 'border border-red text-red'}">${i}</a>`);
        }
        $(".paging").append(`<a href="?page=${nextPage}&type=${type}" class="icon mx-1 bg-red text-white" ${!next ? "disabled" : ""}>
                                <i class="fa fa-angle-right"></i>
                            </a>`);

        if(type == "normal") this.renderNormal(data);
        else this.renderList(data);
    }
    renderNormal(data){
        let first = this.festivals[ this.festivals.length - 1 ];
        $("#wrap").html(`<div class="normal">
                            <div class="row festival" data-toggle="modal" data-target="#view-modal" data-id="${first.id}">
                                <div class="col-lg-5">
                                    <img src="${first.imagePath}/${first.images.length > 0 && first.images[0]}" alt="축제 이미지" class="fit-cover border">
                                </div>
                                <div class="col-lg-7">
                                    <div class="fx-4">${first.name}</div>
                                    <div class="mt-3 textarea">${first.content}</div>
                                    <div class="mt-4 d-between">
                                        <div>
                                            <span class="fx-n2 text-muted">축제 기간</span>
                                            <span class="ml-3 fx-n1">${first.period}</span>
                                        </div>
                                        <button class="btn-custom">자세히 보기</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4 list-row">
                                
                            </div>  
                        </div>`);

        data.forEach(item => {
            $("#wrap .list-row").append(`<div class="col-lg-4 mb-4">
                                            <div class="festival border bg-white" data-target="#view-modal" data-toggle="modal" data-id="${item.id}">
                                                <img src="${item.imagePath}/${item.images.length > 0 && item.images[0]}" alt="축제 이미지" class="fit-cover hx-200">
                                                <div class="p-3">
                                                    <div class="fx-3">${item.name}</div>
                                                    <div class="mt-2">
                                                        <span class="fx-n2 text-muted">축제 기간</span>
                                                        <span class="ml-2 fx-n1">${item.period}</span>
                                                    </div>
                                                    <div class="mt-2 image-cnt">
                                                        <span class="fx-n2 text-muted">사진 개수</span>
                                                        <span class="ml-2 fx-n1">${item.images.length}개</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`);
        });

        $("#wrap img").on("error", e => {
            e.target.src = "/images/no-image.jpg";
            $(e.target).closest(".festival").find(".image-cnt").remove();
        });
    }
    renderList(data){
        $("#wrap").html(`<div class="list">
                            <div class="t-head">
                                <div class="cell-10">번호</div>
                                <div class="cell-50">축제명</div>
                                <div class="cell-30">기간</div>
                                <div class="cell-10">장소</div>
                            </div>
                        </div>`);

        data.forEach(item => {
            $("#wrap .list").append(`<div class="t-row" data-toggle="modal" data-target="#view-modal" data-id="${item.id}">
                                        <div class="cell-10">${item.id}</div>
                                        <div class="cell-50">${item.name}</div>
                                        <div class="cell-30">${item.period}</div>
                                        <div class="cell-10">${item.area}</div>
                                    </div>`);
        });
    }
    
    // 이벤트 설정
    setEvents(){
        // 모달 이벤트
        $("#wrap").on("click", "[data-target='#view-modal']", e => {
            let festival = this.festivals.find(festival => festival.id == e.currentTarget.dataset.id);
            
            $("#view-modal .modal-body").html(`<div class="row">
                                                    <div class="col-lg-5">
                                                        <img src="${festival.imagePath}/${festival.images.length > 0 && festival.images[0]}" alt="이미지" class="fit-cover">
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <div class="fx-4 mb-4">${festival.name}</div>
                                                        <div class="mt-2">
                                                            <span class="fx-n2 text-muted">지역</span>
                                                            <span class="ml-2 fx-n1">${festival.area}</span>
                                                        </div>
                                                        <div class="mt-2">
                                                            <span class="fx-n2 text-muted">장소</span>
                                                            <span class="ml-2 fx-n1">${festival.location}</span>
                                                        </div>
                                                        <div class="mt-2">
                                                            <span class="fx-n2 text-muted">기간</span>
                                                            <span class="ml-2 fx-n1">${festival.period}</span>
                                                        </div>
                                                        <div class="mt-3 textarea">${festival.content}</div>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <div class="fx-3">축제 사진</div>
                                                    <div class="slide mt-2">
                                                        <div class="inner" style="width: ${festival.images.length * 100}%">
                                                            ${
                                                                festival.images.map(image => `<img style="width: ${100 / festival.images.length}%" src="${festival.imagePath}/${image}" alt="축제 이미지">`).join('')
                                                            }
                                                        </div>
                                                    </div>
                                                    <div class="slide-control mt-3 d-center"></div>
                                                </div>`);

            // 슬라이드 버튼
            $("#view-modal .slide-control").html(`<button class="relative icon mx-1 bg-red text-white" data-value="-1" disabled>
                                                        <i class="fa fa-angle-left"></i>
                                                    </button>`);
            for(let i = 0; i < festival.images.length; i++)
                $("#view-modal .slide-control").append(`<a class="absolute icon mx-1 ${i == 0 ? 'active' : ''}" data-value="${i}">${i + 1}</a>`);
            $("#view-modal .slide-control").append(`<button class="relative icon mx-1 bg-red text-white" data-value="1">
                                                        <i class="fa fa-angle-right"></i>
                                                    </button>`);

            $("#view-modal").data("sno", 0);
        });


        // 슬라이드 이벤트
        $("#view-modal").on("click", ".slide-control .icon", e => {
            let sno = $("#view-modal").data("sno");
            let value = $(e.currentTarget).data("value");
            let imgCnt = $("#view-modal .slide img").length;

            if(e.currentTarget.classList.contains("absolute")) {
                sno = value;
            }
            else if(e.currentTarget.classList.contains("relative")) {
                sno += value;
            }
            else return;
            
            $("#view-modal").data("sno", sno);
            
            $("#view-modal .slide .inner").css("left", -100 * sno + "%");
            
            $("#view-modal .slide-control .absolute").removeClass("active");
            $("#view-modal .slide-control .absolute").eq(sno).addClass("active");

            $("#view-modal .slide-control .relative").removeAttr("disabled");
            
            
            if(sno + 1 >= imgCnt)
                $("#view-modal .slide-control .relative").eq(1).attr("disabled", "disabled");

            if(sno - 1 < 0) 
                $("#view-modal .slide-control .relative").eq(0).attr("disabled", "disabled");
        });
    }


    // 데이터 불러오기
    getFestivals(){
        return fetch("/api/festivals")
            .then(res => res.json());
        // return fetch("/xml/festivalList.xml")
        //     .then(res => res.text())
        //     .then(xmlText => {
        //         let parser = new DOMParser();
        //         let xml = parser.parseFromString(xmlText, "text/xml");
                
        //         let festivals = Array.from( xml.querySelectorAll("item") ).map(festival => ({
        //             id: parseInt(festival.querySelector("sn").innerHTML),
        //             no: parseInt(festival.querySelector("no").innerHTML),
        //             name: festival.querySelector("nm").innerHTML,
        //             area: festival.querySelector("area").innerHTML,
        //             location: festival.querySelector("location").innerHTML,
        //             period: festival.querySelector("dt").innerHTML,
        //             content: festival.querySelector("cn").innerHTML,
        //             images: Array.from(festival.querySelectorAll("image")).map(xmlImg => xmlImg.innerHTML),
        //         }));

        //         return festivals.map(item => ({...item, imagePath: "/xml/festivalImages/" + `${item.id}`.padStart(3, '0') + "_" + item.no,}))
        //     });
    }
}

$(function(){
    let app = new App();
});