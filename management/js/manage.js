window.onload = function () {
    //表单验证
    var oTxt = document.querySelector('.txt');
    var oPsw = document.querySelector('.psw');
    var oTs1 = document.querySelectorAll('.txt-1');
    var oCode = document.querySelector('.code');

    var oSubmit = document.querySelector('.submit');


    //用户名  
    oTxt.onclick = function () {
        if (oTxt.value.length == 0) {
            oTs1[0].style.display = 'block';
            oSubmit.style.top = '60%';
        }
        return;
    }
    oTxt.oninput = function () {
        if (oTxt.value.length == 0) {
            oTs1[0].style.display = 'block';
            oSubmit.style.top = '60%';
            return;
        }
        else {
            oTs1[0].style.display = 'none';
            oSubmit.style.top = '0';
            return;
        }
    }
    //密码
    oPsw.onclick = function () {
        if (oPsw.value.length == 0) {
            oTs1[1].style.display = 'block';
            oSubmit.style.top = '60%';
        }
        return;
    }
    oPsw.oninput = function () {
        if (oPsw.value.length == 0) {
            oTs1[1].style.display = 'block';
            oSubmit.style.top = '60%';
            return;
        }
        else {
            oTs1[1].style.display = 'none';
            oSubmit.style.top = '0';
            return;
        }
    }
    //验证码
    oCode.onclick = function () {
        if (oCode.value.length == 0) {
            oTs1[2].style.display = 'block';
            oSubmit.style.top = '60%';
        }
        return;
    }
    oCode.oninput = function () {
        if (oCode.value.length == 0) {
            oTs1[2].style.display = 'block';
            oSubmit.style.top = '60%';

            return;
        }
        else {
            oTs1[2].style.display = 'none';
            oSubmit.style.top = '0';
            return;
        }
    }

    //看不清换一张
    /* var oChange=document.querySelector('.change');
    oChange.onclick=function(){
        arra({
            url: 'login.php',
            type: 'POST',
            data: {
                login_user: oTxt.value,
                login_pass: oPsw.value,
                yan:oCode.value,
            },

            success: function (arr1) {
                if (arr1 == 'true') {
                  
                   
                }
                else {
                    alert("加载失误！")
                }
            }
        })
    } */



    //登录验证
    // var oLogin = document.getElementById('btn-login');

    oSubmit.onclick = function () {
        if (oTxt.value.length == 0) {
            oTs1[0].style.display = 'block';
            oSubmit.style.top = '60%';            
        }

        if (oPsw.value.length == 0) {
            oTs1[1].style.display = 'block';
            oSubmit.style.top = '60%';            
        }
        if (oTxt.value.length != 0 & oPsw.value.length != 0) {
            arra({
                url: '../login.php',
                type: 'POST',
                data: {
                    login_user: oTxt.value,
                    login_pass: oPsw.value,
                    yan:oCode.value,
                },

                success: function (arr1) {
                    if (arr1 == 'true') {
                        // oTs1[0].style.display = 'block';
                        // oTs2[1].style.display = 'block';
                        window.location.href='message.php'
                        // addCookie(oTxt2, oTxt2.value, 1);
                    }
                    else {
                        alert("用户名或密码错误！")
                    }
                }
            })
            // }
        }
    }


    var oChange=document.querySelector('.change');
    oChange.onclick=function(){
        arra({
<<<<<<< HEAD
            url: '../../phpImageChecked/setImageChecked.php',
=======
<<<<<<< HEAD
            url: '../../phpImageChecked/setImageChecked.php',
=======
            url: 'http://localhost/suixing/phpImageChecked/setImageChecked.php',
>>>>>>> 14b5cd170ebe1a05a8ee36c0c4e3e5bf80c674a0
>>>>>>> 69e9b7252036dec56d20cec5e16e2a2d67ff623a
            

            success: function (arr1) {
               
<<<<<<< HEAD
               $(".yan").attr('src',"../../phpImageChecked/setImageChecked.php"); 
=======
<<<<<<< HEAD
               $(".yan").attr('src',"../../phpImageChecked/setImageChecked.php"); 
=======
               $(".yan").attr('src',"http://localhost/suixing/phpImageChecked/setImageChecked.php"); 
>>>>>>> 14b5cd170ebe1a05a8ee36c0c4e3e5bf80c674a0
>>>>>>> 69e9b7252036dec56d20cec5e16e2a2d67ff623a
            }
        })
    }

}
