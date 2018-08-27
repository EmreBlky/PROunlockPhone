function showLoading(){
    $(".submit").hide(),
    $(".submit").closest("div").append('<div class="iS-Loading load"><div class="iS-Loadingbox"><div class="loader">Loading...</div></div></div>')
}

function showButton(){
    $(".submit").show(),$(".load").remove()
}

function executeRequest(a,e,t){
    return a.unshift({
        name:"_token",
        required:!0,
        type:"text",
        value:token
    }),
    showLoading(),
    $("#messages").empty(),
    !0
}

function showError(){
    $("#errorModal").modal("toggle")
}

$(document).on("click","a[data-toggle=modal]",function(a){a.preventDefault();var e=$(this).attr("data-webx"),t=$(this).attr("data-target");$(t+" .modal-content").html('<div class="iS-Loading load"><div class="iS-Loadingbox"><div class="loader">Loading...</div></div></div>'),$(t+" .modal-content").load(e,function(){$(t).modal("show")})});var openModals=0;$(document).on("shown.bs.modal",".modal",function(){openModals++,$("body").removeClass("modal-open"),$("body").addClass("modal-open")}),$(document).on("hidden.bs.modal",".modal",function(){openModals--,$("body").removeClass("modal-open"),openModals>0&&$("body").addClass("modal-open")});