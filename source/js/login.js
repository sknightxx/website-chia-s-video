function checkInput(data) {
    if(data.type === "login") {
        if(data["username"] === "") {
            $(".user-container").append("<p id='invalidMSG' class='text-danger'>Vui lòng nhập tên đăng nhập</p>")
            $("#username").focus().select();
            return false;
        }

        if(data["password"] === "") {
            $(".password-container").append("<p id='invalidMSG' class='text-danger'>Vui lòng nhập mật khẩu</p>")
            $("#password").focus().select();
            return false;
        }
    }
    else if(data.type === "register") {
        if(data["username"] === "") {
            $(".user-container").append("<p id='invalidMSG' class='text-danger'>Vui lòng nhập tên đăng nhập</p>")
            $("#rusername").focus().select();
            return false;
        }

        if(data["password"] === "") {
            $(".password-container").append("<p id='invalidMSG' class='text-danger'>Vui lòng nhập mật khẩu</p>")
            $("#rpassword").focus().select();
            return false;
        }

        if(data["password"].length < 6) {
            $(".password-container").append("<p id='invalidMSG' class='text-danger'>Mật khẩu phải có ít nhất 6 ký tự</p>")
            $("#rpassword").focus().select();
            return false;
        }
        
        if(data["email"] === "") {
            $(".email-container").append("<p id='invalidMSG' class='text-danger'>Vui lòng nhập email</p>")
            $("#email").focus().select();
            return false;
        }

        let reg = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if(!reg.test(data["email"])) {
            $(".email-container").append("<p id='invalidMSG' class='text-danger'>Email không hợp lệ</p>")
            $("#email").focus().select();
            return false;
        }
    }

    return true;
}

function isRemember() {

}

$('.register-btn').click(function(){
    setTimeout(function() {
        $("#loginForm").removeClass("d-flex")
    }, 600)
    $("#loginForm").animate({height: "toggle", opacity: "toggle"}, "slow");
    $("#registerForm").stop().delay(650).animate({height: "toggle", opacity: "toggle"}, "slow");
    setTimeout(function() {
        $("#registerForm").addClass("d-flex")
    }, 700)
});

$('.login-btn').click(function(){
    setTimeout(function() {
        $("#registerForm").removeClass("d-flex")
    }, 600)
    $("#registerForm").animate({height: "toggle", opacity: "toggle"}, "slow");
    $("#loginForm").stop().delay(650).animate({height: "toggle", opacity: "toggle"}, "slow");
    setTimeout(function() {
        $("#loginForm").addClass("d-flex")
    }, 700)
});

$("#username").keyup(function() {
    $("#invalidMSG").remove()
});

$("#password").keyup(function() {
    $("#invalidMSG").remove()
});

$("#rusername").keyup(function() {
    $("#invalidMSG").remove()
});

$("#rpassword").keyup(function() {
    $("#invalidMSG").remove()
});

$("#email").keyup(function() {
    $("#invalidMSG").remove()
});


$("#loginForm").submit(function(event) {  
    event.preventDefault();
    $("#invalidMSG").remove()
    let data = {
        type : "login",
        username : $("#username").val(),
        password : $("#password").val()
    }

    if(checkInput(data)) {
        $.ajax({
            method: 'POST',
            url : "../source/login.php",
            "data" : data
        }).done(function(data) {
            data = JSON.parse(data)
            console.log(data)
            if(data.status) {
                
                window.location.href = "./index.php"
            }
            else{
                $("#loginbtn").after("<p id='invalidMSG' class='text-danger'>Sai thông tin đăng nhập</p>")
            }
        }).fail(function(data) {
            console.log(data)
        })
    }
})

$("#registerForm").submit(function(event) {
    event.preventDefault();
    $("#invalidMSG").remove()

    let data = {
        type : "register",
        username : $("#rusername").val(),
        password : $("#rpassword").val(),
        email : $("#email").val()
    }
    if(checkInput(data)) {
        //ajax
        $.ajax({
            method: 'POST',
            url : "../source/login.php",
            "data" : data
        }).done(function(data) {
            data = JSON.parse(data)
            console.log(data)
            $("#registbtn").after("<p id='invalidMSG'>" + data.messege +"</p>")
            if(data.status) {
                $("#invalidMSG").addClass("text-success")
            }
            else{
                $("#invalidMSG").addClass("text-danger")
            }
            
        }).fail(function(data) {
            console.log(data)
        })
    }
})

const rmCheck = document.getElementById("remember"),
    username = document.getElementById("username");

if (localStorage.checkbox && localStorage.checkbox !== "") {
  rmCheck.setAttribute("checked", "checked");
  username.value = localStorage.username;
} else {
  rmCheck.removeAttribute("checked");
  username.value = "";
}

function isRememberMe() {
  if (rmCheck.checked && username.value !== "") {
    localStorage.username = username.value;
    localStorage.checkbox = rmCheck.value;
  } else {
    localStorage.username = "";
    localStorage.checkbox = "";
  }
}

window.onload = function(){ // Trigger when the page is ready
    log = document.getElementById('showlogin').addEventListener('change', showPass, false);
    regist = document.getElementById('showregist').addEventListener('change', showPass, false);
    document.getElementById('remember').addEventListener('change', isRememberMe, false);
}

function showPass() {
    let checked1 = document.getElementById('showlogin').checked
    let checked2 = document.getElementById('showregist').checked

    if(checked1) {
        $("#password").attr("type", "text")
    }
    else {
        $("#password").attr("type", "password")
    }
    if(checked2) {
        $("#rpassword").attr("type", "text")
    }
    else {
        $("#rpassword").attr("type", "password")
    }
    
}