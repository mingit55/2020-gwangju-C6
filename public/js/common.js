location.getQueryString = function(){
    let search = this.search.substr(1);
    if(search === "") return {};
    else {
        return search.split("&").reduce((p, c) => {
            let [key, value] = c.split("=");
            p[key] = value;
            return p;
        }, {});
    }
};


$(function(){
    $("[data-target='#roadmap']").on("click", e => {
        e.stopPropagation();
        
        let timeout = false;
        setTimeout(() => {
            if(!timeout){
                timeout = true;
                alert("찾아오시는 길을 표시할 수 없습니다.");
            }
        }, 1000);

        fetch("/location.php")
            .then(res => res.text())
            .then(htmlText => {
                if(!timeout) {
                    timeout = true;
                    $("#roadmap .modal-body").html(htmlText);
                    $("#roadmap").modal("show");
                }
            });
    });
});