// 'use strict'
// let toolFunc = {
//   // 页面滚动头部动态效果
//   headerScroll: function() {
//     $(window).scroll(function() {
//       if($(window).scrollTop() === 0) {
//         $('body').removeClass('page-scroll');
//       } else {
//         $('body').addClass('page-scroll');
//       }
//     })
//   },
//   checkStr: function(param) {
//     let isPass = true;
//     let str = $(param.selStr).val();
//     if(param.minLength) {
//       if(this.getStrLength(str) < param.minLength) {
//         isPass = false;
//       }
//     }
//     if(param.maxLength) {
//       if(this.getStrLength(str) > param.maxLength) {
//         isPass = false;
//       }
//     }
//     if(param.reg) {
//       if(!this.regCheck(str, param.reg)) {
//         isPass = false;
//       }
//     }
//     if(!isPass) {
//       $(param.selStr).addClass('input-error');
//     } else {
//       $(param.selStr).removeClass('input-error');
//     }
//     return isPass;
//   },
//   regCheck: function(sStr, sReg) {
//     if (sReg.test(sStr)) {
//       return true;
//     } else {
//       return false;
//     }
//   },
//   getStrLength: function(str) {
//     return str.replace(/[^\u0000-\u00ff]/g,"aa").length;
//     st
//   },
//   // 平滑滚动
//   smoothRolling: function(sObjClick, sObjPos) {
//     $(sObjClick).click(function() {
//       $('html,body').animate({scrollTop: $(sObjPos).offset().top}, 1000);
//     });
//   }
// };
