var now = "visitor";
var id;
var vis;

$(document).ready(function () {
    $.ajax({
        type: 'POST',
        url: './message/mes.php',
        data: {
            ins: "gett"
        },
        success: function (data) {
            value = JSON.parse(data);
            id = value[value[0]].id;
            vis = value['user'];
            if (vis !== null) {
                now = vis;
                $("#logina").hide();
                $("#loginb").show();
                var str = "欢迎  " + vis;
                $("#loginb").text(str);
                $("#btnzhuxiao").show();
            } else {
                $("#loginb").hide();
                $("#logina").show();
                $("#btnzhuxiao").hide();
            }
            for (var i = 1; i <= value[0]; i++) {
                var str = "<div class=mes owner=" + value[i].username + " idd=" + value[i].id + ">" +
                        "<h4>" + " " + value[i].username + "</h4>" +
                        "<p>" + value[i].data + "</p>" +
                        "<h5>"+value[i].time+"</h5>"+
                        "<button class=del>删除</button><button class=up>修改</button></div>";
                $("#add").prepend(str);

            }
            // $(".del").click(function () {alert("123")});
            btnn();
        }
    })
    btnn();


})

//$(".del").attr('onclick', null);
function btnn() {
    $(".del").click(function () {
        owner = $(this).parent().attr("owner");
        idd = $(this).parent().attr("idd");
        if (now!=="GM"&&owner !== now) {
            alert("没有权限！");
            return;
        }
        $(this).parent().remove();
        $.ajax({
            type: 'POST',
            url: './message/mes.php',
            data: {
                ins: "delete",
                id: idd,
                owner: owner
            } //,
            //success: function (data) {

            //}
        })
    })

    

    $(".up").click(function () {

        owner = $(this).parent().attr("owner");
        idd = $(this).parent().attr("idd");
        if (now!=="GM"&&owner !== now) {
            alert("没有权限！");
            return;
        }
        datain = $(this).siblings("p").text();
        //datain=datain.replace("删除修改","");
        //datain=datain.replace(idd+" "+owner+":","");
        str = prompt("请输入修改后的内容：", datain);
        if (str){datain = str;
        //$(this).siblings("p").text(idd+" "+owner+":"+datain);
        $(this).siblings("p").text(datain);
        $.ajax({
            type: 'POST',
            url: './message/mes.php',
            data: {
                ins: "updata",
                id: idd,
                owner: owner,
                data: datain
            }
        })
    }
    })
    $(".mes").click(function(){
        $(this).find("button").toggle();
        //$(this).find("h5").toggle();
    })
}

$("#btn").click(function () {
    //str=$(this).parent().text();
    //str=str.replace("确认","");
    //prompt(str);
    if (now === "visitor") {
        alert("请先登录！");
        return;
    }
    var datain = $("#datain").val()
    if (datain === "") {
        alert("内容不能为空！");
        return;
    }
    $("#datain").val("");
    $.ajax({
        type: 'POST',
        url: './message/mes.php',
        data: {
            ins: "save",
            data: datain,
            username: now
        },
        success: function (data) {
            var str = "<div class=mes owner=" + now + " idd=" + (++id) + ">" +
                    "<h4>" + " " + now + "</h4>" +
                    "<p>" + datain + "</p>" +
                    "<h5>"+timee()+"</h5>"+
                    "<button class=del>删除</button><button class=up>修改</button></div>";
            $("#add").prepend(str);
            btnn();
        }
    })
    btnn();
})

$("#btnzhuxiao").click(function () {
    $.ajax({
        type: 'POST',
        url: './message/mes.php',
        data: {
            ins: "logout",
        },
        success: function (data) {
            $("#loginb").hide();
            $("#logina").show();
            $("#btnzhuxiao").hide();
            now = "visitor";
        }
    })
})

function timee(){
    function p(s) {
        return s < 10 ? '0' + s: s;
    }
    
    var myDate = new Date();
    //获取当前年
    var year=myDate.getFullYear();
    //获取当前月
    var month=myDate.getMonth()+1;
    //获取当前日
    var date=myDate.getDate(); 
    var h=myDate.getHours();       //获取当前小时数(0-23)
    var m=myDate.getMinutes();     //获取当前分钟数(0-59)
    var s=myDate.getSeconds();  
    
    var now2=year+'-'+p(month)+"-"+p(date)+" "+p(h)+':'+p(m)+":"+p(s);
    return now2;
}