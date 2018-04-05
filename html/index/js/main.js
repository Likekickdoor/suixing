// 页面头部滚动效果
let toolFunc = {
  // 页面滚动头部动态效果
  headerScroll: function() {
    $(window).scroll(function() {
      if($(window).scrollTop() === 0) {
        $('body').removeClass('page-scroll');
      } else {
        $('body').addClass('page-scroll');
      }
    })
  },
  checkStr: function(param) {
    let isPass = true;
    let str = $(param.selStr).val();
    if(param.minLength) {
      if(this.getStrLength(str) < param.minLength) {
        isPass = false;
      }
    }
    if(param.maxLength) {
      if(this.getStrLength(str) > param.maxLength) {
        isPass = false;
      }
    }
    if(param.reg) {
      if(!this.regCheck(str, param.reg)) {
        isPass = false;
      }
    }
    if(!isPass) {
      $(param.selStr).addClass('input-error');
    } else {
      $(param.selStr).removeClass('input-error');
    }
    return isPass;
  },
  regCheck: function(sStr, sReg) {
    if (sReg.test(sStr)) {
      return true;
    } else {
      return false;
    }
  },
  getStrLength: function(str) {
    return str.replace(/[^\u0000-\u00ff]/g,"aa").length;
    st
  },
  // 平滑滚动
  smoothRolling: function(sObjClick, sObjPos) {
    $(sObjClick).click(function() {
      $('html,body').animate({scrollTop: $(sObjPos).offset().top}, 1000);
    });
  }
};




$(function() {
  if($(window).scrollTop() === 0) {
    $('body').removeClass('page-scroll');
    console.log(1);
  } else {
    $('body').addClass('page-scroll');
    console.log(2);
  }
  toolFunc.headerScroll();
  $('ul.city-auto').on('click', 'li', function() {
    let sType = $(this).parent().attr('title');
    if(sType === 'from') {
      $('form.index-query').find('input[title=from]').val($(this).text());
    } else {
      $('form.index-query').find('input[title=to]').val($(this).text());
    }
  })

  // 获得全部城市名称
  $.ajax({
  　　url: "./index.php",
  　　type: "GET",
      data: "con=ajax&med=getStation",
  　　success: function(res) {
        let temp = res.split(';');
        let aCityName = new Array();
        $.each(temp, function(i, item) {
          let t = item.split(',');
          let cityName = {
            cn: t[0],
            en: t[1]
          }
          aCityName.push(cityName);
        })
        window.aCityName = aCityName;
        console.log(aCityName);
      },
      error: function() {
        console.log('城市列表获取失败');
      }
  });

  // 信息提交反馈
  $('#msg-btn').click(function() {
    let isPass = true;
    isPass = toolFunc.checkStr({
      selStr: '#msg-name',
      minLength: 1,
      maxLength: 20
    });
    isPass = toolFunc.checkStr({
      selStr: '#msg-mail',
      maxLength: 20,
      reg: /^[_a-z0-9]+@([_a-z0-9]+\.)+[a-z0-9]{2,3}$/
    });
    isPass = toolFunc.checkStr({
      selStr: '#msg-text',
      minLength: 1,
      maxLength: 100
    });
    if(isPass) {
  //    Ajax提交
    }
    return false;
  })

  //移动端导航栏显示隐藏
  $('div.nav-btn').click(function() {
    $(this).toggleClass('on');
    $('header.header').toggleClass('header-show');
  })

  // 城市输入自动完成
  $('form.index-query').find('input[type=text]').on('focus', function() {
    $('ul.city-auto').attr('title', $(this).attr('title'));
  });
  $('form.index-query').find('input[type=text]').on('input', function() {
    let cnt = 0;
    let sInput = $(this).val();
    $('ul.city-auto').css('top', this.offsetTop + this.offsetHeight + 5)
    $('ul.city-auto').empty();
    // 去空格
    sInput = sInput.replace(/(^\s+)|(\s+$)/g,"");
    if(sInput != "") {
      $('form.index-query').find('ul.city-auto').fadeIn('slow');
      if(/^[\x00-\xff]/.test(sInput.charAt(0))) {
        $.each(aCityName, function(i, item) {
          if(cnt < 5 && item.en.indexOf(sInput) != -1) {
            let temp = $('<li></li>');
            temp.text(item.cn);
            $('ul.city-auto').append(temp);
            cnt++;
          }
        })
      } else {
        $.each(aCityName, function(i, item) {
          if(cnt < 5 && item.cn.indexOf(sInput) != -1) {
            let temp = $('<li></li>');
            temp.text(item.cn);
            $('ul.city-auto').append(temp);
            cnt++;
          }
        })
      }
      $('ul.city-auto').find('li').click(function() {
        console.log(1);
      })
      if(!cnt) {
        $('form.index-query').find('ul.city-auto').hide(200);
      }
    } else {
      $('form.index-query').find('ul.city-auto').hide(200);
    }
  });
  $('form.index-query').find('input[type=text]').on('blur', function() {
    $('form.index-query').find('ul.city-auto').hide(200);
  })

  toolFunc.smoothRolling('#to_about', '#about');
  toolFunc.smoothRolling('#to_team', '#team');
  toolFunc.smoothRolling('#to_contact', '#contact');

})
