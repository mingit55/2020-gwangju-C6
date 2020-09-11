class App {
    constructor(){
        this.init();
    }

    async init(){
        let ls_viewIndex = localStorage.getItem("viewIndex");
        let ls_scrollY = localStorage.getItem("scrollY");

        this.viewIndex = ls_viewIndex ? parseInt(ls_viewIndex) : 10;

        let {statusCd, statusMsg, dt, items} = await this.getExchangeData();
        
        if(statusCd != 200){
            $("#wrap").html(statusMsg);
        } else {
            $("#updated_at").text(dt);

            this.list = items;
            this.render();
            this.setEvents();

            if(ls_scrollY) {
                setTimeout(() => window.scrollTo(0, parseInt(ls_scrollY)));
            }
        }
    }

    // 데이터 불러오기
    getExchangeData(){
        return fetch("/restAPI/currentExchangeRate.php")
            .then(res => res.json());
    }

    // DOM 렌더링
    render(){
        $("#wrap").html('');
        localStorage.setItem("viewIndex", this.viewIndex);
        this.list.slice(0, this.viewIndex).forEach(item => {
            $("#wrap").append(`<div class="col-lg-3 mb-4">
                                    <div class="fx-6 font-weight-bold keep-all ${item.result != 1 ? 'text-red' : ''}">${item.cur_nm}(${item.cur_unit})</div>
                                </div>
                                <div class="col-lg-9 mb-4">
                                    <div class="border-top">
                                        <div class="t-row">
                                            <div class="cell-20 text-muted fx-n2">송금 시</div>
                                            <div class="cell-80 text-left">${item.ttb}</div>
                                        </div>
                                        <div class="t-row">
                                            <div class="cell-20 text-muted fx-n2">수금 시</div>
                                            <div class="cell-80 text-left">${item.tts}</div>
                                        </div>
                                        <div class="t-row">
                                            <div class="cell-20 text-muted fx-n2">매매 기준율</div>
                                            <div class="cell-80 text-left">${item.deal_bas_r}</div>
                                        </div>
                                        <div class="t-row">
                                            <div class="cell-20 text-muted fx-n2">장부 가격</div>
                                            <div class="cell-80 text-left">${item.bkpr}</div>
                                        </div>
                                        <div class="t-row">
                                            <div class="cell-20 text-muted fx-n2">년환가료율</div>
                                            <div class="cell-80 text-left">${item.yy_efee_r}</div>
                                        </div>
                                        <div class="t-row">
                                            <div class="cell-20 text-muted fx-n2">10일환가료율</div>
                                            <div class="cell-80 text-left">${item.ten_dd_efee_r}</div>
                                        </div>
                                        <div class="t-row">
                                            <div class="cell-20 text-muted fx-n2">매매 기준율</div>
                                            <div class="cell-80 text-left">${item.kftc_bkpr}</div>
                                        </div>
                                        <div class="t-row">
                                            <div class="cell-20 text-muted fx-n2">장부가격</div>
                                            <div class="cell-80 text-left">${item.kftc_deal_bas_r}</div>
                                        </div>
                                    </div>
                                </div>`);
        });

        if(this.viewIndex < this.list.length) {
            $("#wrap").append(`<div class="btn-load col-12 py-3 mt-4 text-center">
                                    <button class="btn-custom">더 보기</button>
                                </div>`);
        }
    }

    setEvents(){
        // 스크롤 이벤트
        $(window).on("scroll", e => {
            let scrollBottom = window.innerHeight + window.scrollY;
            localStorage.setItem("scrollY", window.scrollY)
            
            if(scrollBottom == document.body.offsetHeight){
                this.viewIndex += 10;
                this.render();
            }
        });

        // 더 보기 버튼
        $("#wrap").on("click", ".btn-load", e => {
            this.viewIndex += 10;
            this.render();
        });
    }
}

$(function(){
    let app = new App();
});