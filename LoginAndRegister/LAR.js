var type = "login"
var now = 0

$("#login").click(function () {
    $("#login").toggleClass("selected")
    $("#sign").toggleClass("selected")
    $("#pw2").toggleClass("hidden")
    $("#name").val("")
    $("#password").val("")
    $("#password2").val("")
    type = "login"
})

$("#sign").click(function () {
    $("#login").toggleClass("selected")
    $("#sign").toggleClass("selected")
    $("#pw2").toggleClass("hidden")
    $("#name").val("")
    $("#password").val("")
    $("#password2").val("")
    type = "sign"
})

$("#btn").click(function () {
    var usn = $("#name").val()
    var pw = $("#password").val()
    var pw2 = $("#password2").val()
    if (usn === "") {
        alert("请填写用户名");
        return;
    }
    if (pw === "") {
        alert("请填写密码");
        return;
    }
    if (pw2 === "" && type === "sign") {
        alert("请重新填写密码");
    }
    if (pw !== pw2 && type === "sign") {
        alert("两次填写的密码不一样");
        return;
    }
    $.ajax({
        type: 'POST',
        url: './LAR.php',
        data: {
            ins: type,
            userName: usn,
            password: pw
        },
        success: function (data) {
            $("#response").removeClass("hidden")
            var text = ""
            try {
                var data = JSON.parse(data)
                text = data['msg']
                now = data['status']
            } catch (e) {
                text = e
            }
            $("#response").text(text);
            if (now=='1' && type == 'login') {
                var str="window.location.assign('../index.html')";
                var t = setTimeout(str, 1500);
            }
        }
    })
    
    now = 0;
})

/*
$("#btn2").click(function () {
    var datain = $("#datain").val()
    if (now==0){
        alert("请先登录！");
        return;
    }
    $("#add").prepend("<div>",datain,"</div>");
    /*
    $.ajax({
        type: 'POST',
        url: 'http://localhost/task/index.php',
        data: {
            message: datain
        },
        success: function () {
            $("#add").prepend("<div>",datain,"</div>");
        }
    })
})
/*
.fadeIn()
.fadeOut()
*/