    $(document).ready(function () {
        //判断浏览器，并提示用户换成 Chrome 或 Firefox 浏览器查看本页面主要是 IE 等一些浏览器版本不兼容
        if(navigator.userAgent.indexOf('Firefox') == -1 && navigator.userAgent.indexOf('Chrome') == -1){
            alert('温馨提示：' + '\n使用谷歌或火狐浏览器将会最大程度来使用本网站功能');
        }else if(navigator.userAgent.indexOf('Edge') != -1){
            alert('温馨提示：' + '\n使用谷歌或火狐浏览器将会最大程度来使用本网站功能');
        };

        $('input[name="dateRange"]').daterangepicker({
            format: 'YYYY-MM-DD',
            singleDatePicker: true,
            minDate: new Date(),

        });
    //城市名称输入预览
    //  判断字节数（2字节/汉字 1字节/字母）
    ////////////////////////新增////////////////////////
    //  判断字节数（2字节/汉字 1字节/字母），至少输入2个字母或1个汉字才能显示城市名候选下拉列表【.listCityName】
 
    $('#start').on('input', function (){
        var inputContent = $(this).val();
        var inputCityNameLength = $(this).val().length;
            if(inputContent.match(/^[\u4E00-\u9FA5]{1,}$/)){
                if(inputCityNameLength > 0){
                    $(this).parent().children('.listCityName').fadeIn(100, function(){
                        $(this).stop().animate({margin: '-30px auto'}, 100);
                    });
                        crAjax = $.ajax({
                            url: 'indexMessage/getCity.php',
                            beforeSend: function(){},
                            dataType: 'json',
                            data: {'city':inputContent},
                            
                            type: 'POST',
                            success:function(data){
                            $('.start').each(function(){
                                $('.start').children('.listCityName').html("");
                                var dataLength = data.length;
                                for(var i=0; i<dataLength; i++){
                                    $('<li />').appendTo($(this).children('.listCityName')).end();
                                    var datalLess = data[i][1].replace(/[a-zA-Z,（）\s]+/,"");
                                        $(this).children('.listCityName').children('li').eq(i).html(datalLess);
                                        $('.listCityName').children('li').each(function(){
                                            $(this).click(function(){
                                                var tempCityName = $(this).html();
                                                $(this).parent().parent().children('input').val(tempCityName);
                                            });
                                        }); 
                                }
                            });
                        }
                        
                        });
                }else{
                    $(this).parent().children('.listCityName').fadeOut(100, function(){
                        $(this).stop().animate({margin: '-10px auto'}, 100);
                    });
                };
            }else{
                if(inputCityNameLength > 1){
                    $(this).parent().children('.listCityName').fadeIn(100, function(){
                        $(this).stop().animate({margin: '-30px auto'}, 100);
                    });
                        crAjax = $.ajax({
                            
                            url: 'indexMessage/getCity.php',
                            beforeSend: function(){},
                            dataType: 'json',
                            data: {'city':inputContent},
                            
                            type: 'POST',
                            success:function(data){
                                if(crAjax){
                                $('.start').each(function(){
                                    $('.start').children('.listCityName').html("");
                                    var dataLength = data.length;
                                    for(var i=0; i<dataLength; i++){
                                        $('<li />').appendTo($(this).children('.listCityName')).end();
                                        var datalLess = data[i][1].replace(/[a-zA-Z,（）\s]+/,"");
                                        $(this).children('.listCityName').children('li').eq(i).html(datalLess);
                                    }
                                });
                            }
                        }
                        
                        });
                }else{
                    $(this).parent().children('.listCityName').fadeOut(100, function(){
                        $(this).stop().animate({margin: '-10px auto'}, 100);
                    });
                };
            };
    });
    $('#end').on('input', function (){
        var inputContent = $(this).val();
        var inputCityNameLength = $(this).val().length;
            if(inputContent.match(/^[\u4E00-\u9FA5]{1,}$/)){
                if(inputCityNameLength > 0){
                    $(this).parent().children('.listCityName').fadeIn(100, function(){
                        $(this).stop().animate({margin: '-30px auto'}, 100);
                    });
                        crAjax = $.ajax({
                            url: 'indexMessage/getCity.php',
                            beforeSend: function(){},
                            dataType: 'json',
                            data: {'city':inputContent},
                            
                            type: 'POST',
                            success:function(data){
                            $('.end').each(function(){
                                $('.end').children('.listCityName').html("");
                                var dataLength = data.length;
                                for(var i=0; i<dataLength; i++){
                                    $('<li />').appendTo($(this).children('.listCityName')).end();
                                    var datalLess = data[i][1].replace(/[a-zA-Z,（）]+/,"");
                                        $(this).children('.listCityName').children('li').eq(i).html(datalLess);
                                        $('.listCityName').children('li').each(function(){
                                            $(this).click(function(){
                                                var tempCityName = $(this).html();
                                                $(this).parent().parent().children('input').val(tempCityName);
                                            });
                                        }); 
                                }
                            });
                        }
                        
                        });
                }else{
                    $(this).parent().children('.listCityName').fadeOut(100, function(){
                        $(this).stop().animate({margin: '-10px auto'}, 100);
                    });
                };
            }else{
                if(inputCityNameLength > 1){
                    $(this).parent().children('.listCityName').fadeIn(100, function(){
                        $(this).stop().animate({margin: '-30px auto'}, 100);
                    });
                        crAjax = $.ajax({
                            
                            url: 'indexMessage/getCity.php',
                            beforeSend: function(){},
                            dataType: 'json',
                            data: {'city':inputContent},
                            
                            type: 'POST',
                            success:function(data){
                                if(crAjax){
                                $('.end').each(function(){
                                    $('.end').children('.listCityName').html("");
                                    var dataLength = data.length;
                                    for(var i=0; i<dataLength; i++){
                                        $('<li />').appendTo($(this).children('.listCityName')).end();
                                        var datalLess = data[i][1].replace(/[a-zA-Z,（）]+/,"");
                                        $(this).children('.listCityName').children('li').eq(i).html(datalLess);
                                    }
                                });
                            }
                        }
                        
                        });
                }else{
                    $(this).parent().children('.listCityName').fadeOut(100, function(){
                        $(this).stop().animate({margin: '-10px auto'}, 100);
                    });
                };
            };
    });
    });

    $('#start, #end').on('blur', function(){
        $(this).parent().children('.listCityName').fadeOut('fast', function(){
            $(this).stop().animate({margin: '-10px auto'}, 100);
        });
    });
    $('#start, #end').on('focus', function(){
        $(this).parent().children('.listCityName').fadeIn('fast', function(){
            $(this).stop().animate({margin: '-30px auto'}, 100);
        });
    });
    ///点击输入候选列表城市名功能
    
    ////////////////////////新增////////////////////////
    //若输入起始点至输入的终点之间无站点，则不显示【经停信息】按钮，否则显示
    //此处为 each() 的用法（单独地来操作所有有相同类的元素）
    $('.line').each(function(){
        var lineChildrenLength = $(this).find('.saveStationsName').children('div').length;
        // alert($(this).attr('id') + lineChildrenLength);
        if(lineChildrenLength == 0){
            $(this).children('.btnSlideRight').css({"display": "none"});
        }else{
            $(this).children('.btnSlideRight').css({"display": "block"});
        };
    });
    //激活在火车标签 .ulTRAIN 下的 li 下的的火车图标标签 .tt1，以及相应激活对应的图标标签
    $('.ulSUGGEST').find('.lists.Normal').addClass('J');
    $('.ulTRAIN').find('.lists.Normal').addClass('H');
    $('.ulBUS').find('.lists.Normal').addClass('Q');
    $('.ulPLANE').find('.lists.Normal').addClass('F').addClass('Z');
    $('.ulSHIP').find('.lists.Normal').addClass('C');

    $('ul').children('li:last-child').addClass('last');//【line #216】
    $('#SEbutton').click(function () {
        var str1 = $('#start').val();
        var str2 = $('#end').val();
        $('#start').val(str2);
        $('#end').val(str1);
    });
    ////修改
    $('.addScreen a').click(function(){
        $('.screenMenu').fadeToggle('fast');
    });
    $('.btnScreen').click(function(){
        $('.screenMenu').fadeOut('fast');
    });
    $('.resultsArea').children('#tag').click(function(){
        $(this).parents().find('div').removeClass('active');
        $(this).addClass("active");
    });
    $('.TAG-1').click(function(){
        $(this).parents().find('.listAll').find('ul').css({"display": "none"});
        $('.ulSUGGEST').slideDown('slow');
    });
    $('.TAG-2').click(function(){
        $(this).parents().find('.listAll').find('ul').css({"display": "none"});
        $('.ulTRAIN').css({"display": "table"});
    });
    $('.TAG-3').click(function(){
        $(this).parents().find('.listAll').find('ul').css({"display": "none"});
        $('.ulBUS').css({"display": "table"});
    });
    $('.TAG-4').click(function(){
        $(this).parents().find('.listAll').find('ul').css({"display": "none"});
        $('.ulPLANE').css({"display": "table"});
    });
    $('.TAG-5').click(function(){
        $(this).parents().find('.listAll').find('ul').css({"display": "none"});
        $('.ulSHIP').css({"display": "table"});
    });
    $('.sorts').children('div').bind('click', function(){
        $(this).children('a').toggleClass('selected');
    });//此为排序按钮chevron指示
    $('.showHideStationsName').click(function(){
        $(this).parent().parent().parent().parent().children('.dotss').toggleClass('bottomSlide');
        $(this).parent().parent().parent().parent().toggleClass('toHigh');
        $(this).toggleClass('actived');
        $(this).parent().parent().parent().children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.STAT').children('.saveStationsName').toggleClass('SHOW');
    });
    $('.showHideKm').click(function(){
        $(this).toggleClass('actived');
        $(this).parent().parent().parent().children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.STAT').children('.saveStationsGap').toggleClass('SHOW');
    });
    //激活标签
    // e.g. $('.lists.Normal').addClass('J').addClass('Q');
    //
    //
    //先占个位~~~~~
    //实现【经停信息】
    //【原理】通过获取 .line 标签下的 div 元素个数-3的值来确定站点数目
    // $('.sorts').parent().each(function(){
    //     var listLength = $(this).children('.lists').length;
    //         $('.resultsArea').children('div').click(function (){
    //                 var strTransName = $(this).next('span').html();
    //                 alert(strTransName);
            
    //         // return false;
    //         });
    //         // if(listLength == 0){
    //         //     // alert(listLength);
    //         //     $('<div />').appendTo($(this).parent()).addClass('alertNoResult').end().html(strTransName);
    //         // }
    //     // var strTransName = $(this).parent().parent().parent().children('.active').next().html();
    //     // alert(listLength);
    // });
    $('.line').each(function(){
        var length = $(this).find('.saveStationsName').children('div').length;
        for(var j=0; j<length; j++){
            $('<div />').appendTo($(this).children('.lineThroughDots')).addClass('throughDots').end();
        };
    });
    $('.throughDots').each(function(){
        $(this).mouseover(function(){
            var indexOfDots = $(this).index();
            $(this).parent().parent().children('.STAT').children('.saveStationsName').find('div').css({"opacity": "1"});
        });
    });
//*******此处排序功能*******
    //按价格升序排序
//要添加：
//判断【起点】、【终点】数据是否存在，存在则执行submit函数
jQuery.ready(function(){
    var startPlace = $('#start').val().length;
    var endPlace = $('#end').val().length;
    if(startPlace > 0 && endPlace > 0){
        $('#mainForm').ajaxSubmit({
            url: '',
            type: '',
            dataType: '',
            clearForm: false,
            resetForm: false
        });
    };
});
//解决火车列次过长显示不全的问题
$('.number').each(function(){
    $(this).addClass('addci');
        var numberLength = $(this).html().length * 10;
        if(numberLength > 60){
            $(this).removeClass('addci');
            $(this).css({"width": "100%", "padding": "0", "fontSize": "14px"});
            $(this).parent().children('.costTime').css({"width": "100%", "padding": "0"});
        }
});
////添加
    $('.lists').each(function(){
        // alert($(this).children('.moreTags').children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.btnSlideRight').attr('class'));
        $(this).children('.moreTags').children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.btnSlideRight').click(function(){
            var divsNumber = $(this).parent().children('.STAT').children('.saveStationsName').children('div').length;
            // alert(divsNumber);
            // alert($(this).parent().children('.STAT').children('.saveStationsName').children('div').eq(1).html());
            $(this).parent().children('.STAT').children('.saveStationsName').children('div').css({"display:": "none"});
            var oThisListsNormal = $(this).parent().parent().parent().parent().parent().parent();
            // alert(oThisListsNormal.attr('class'));
            if(divsNumber >= 10){
                $('.btnSlideRight').each(function(){
                    $(this).click(function(){
                        $(this).css({"display": "block"});
                        $(this).parent('.line').parent().parent().parent().parent().children('.moreTagsLeft').children('.showHideBtn').css({"display": "none"});
                        $(this).parent('.line').children('.STAT').css({"display": "none"});
                        $(this).parent().parent('.throughStations').stop().animate({width: '20%'}, 0, function(){
                            var LineWidth = parseFloat($(this).children('.line').css('width')) - 20;
                            var DivNum = $(this).children('.line').find('.saveStationsName').children('div').length + 3;
                            $(this).children('.line').children('.lineThroughDots').css({"display": "none"});
                            $(this).children('.line').children('.lineThroughDots').children('.throughDots').css({"margin": "0" + " " + LineWidth/(2*DivNum) + "px", "pointer-events": "auto", "display": "none"});
                            var dotsMargin = LineWidth/DivNum + 10;
                            $(this).children('.line').children('.STAT').children('.saveStationsGap').children('div').css({"width": dotsMargin + "px"});
                            $(this).children('.line').children('.STAT').children('.saveStationsName').children('div').css({"width": dotsMargin + "px"});
                            var dotsFLSpace = $(this).children('.line').children('.lineThroughDots').children('div:first-child').offset().left - $(this).children('.line').children('.SaEdots').offset().left + 5;
                            $(this).children('.line').children('.STAT').children('.saveStationsGap').children('div:first-child, div:last-child').css({"width": dotsFLSpace + "px"});
                        });
                        $('.foldBtn').click(function(){
                            $(this).parent('.showHideBtn').fadeOut('fast', function(){
                                $(this).parent().parent().parent().removeClass('toHigh');
                                $(this).parent().parent().parent().children('.dotss').removeClass('bottomSlide');
                                $(this).children('.showHideStationsName').removeClass('actived');
                                $(this).children('.showHideKm').removeClass('actived');
                                // alert($(this).parent().parent().parent().attr('class'));
                                $(this).parent().parent().children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.lineThroughDots').css({"display": "none"});
                                $(this).parent().parent().children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.btnSlideRight').fadeIn('slow');
                                $(this).parent().parent().children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.STAT').css({"display": "none"});
                                $(this).parent().parent().children('.stations').children('.StationCenter').children('.throughStations').stop().animate({width: '20%'}, 260);
                            });
                        });
                    }); 
                });
                // $('.btnSlideRight').each(function(){$(this).click(function(){
                //     $(this).fadeOut('fast');
                //     // $(this).parent().parent('.throughStations').children('.line').children('.lineThroughDots').css({"opacity": "0"});
                // })});
                $('.fullCover').fadeIn('fast', function(){
                    $('.centerBox').fadeIn(500);
                    $('html').css({"overflow": "hidden"});
                });
                $('#closeWindow .foldBtn').click(function(){
                    $('.centerBox').fadeOut('fast', function(){
                        $('.fullCover').fadeOut('fast');
                        $('html').css({"overflow": "auto"});
                });
                });
                not($('.centerBox')).click(function(){
                    $('.centerBox').fadeOut('fast', function(){
                        $('.fullCover').fadeOut('fast');
                        $('html').css({"overflow": "auto"});
                });
                });
                // var halfOfDivsNumber = divsNumber/2;
                // for(var j=halfOfDivsNumber; j<=divsNumber-5; j++){
                    
                //     // alert($(this).parent().children('.STAT').children('.saveStationsName').children('div').eq(6).html());
                // }
            }else{
                $('.btnSlideRight').each(function(){
                    $(this).click(function(){
                        $(this).css({"display": "none"});
                        $(this).parent('.line').parent().parent().parent().parent().children('.moreTagsLeft').children('.showHideBtn').css({"display": "flex"});
                        $(this).parent('.line').children('.STAT').css({"display": "block"});
                        $(this).parent().parent('.throughStations').stop().animate({width: '85%'}, 300, function(){
                            var LineWidth = parseFloat($(this).children('.line').css('width')) - 20;
                            var DivNum = $(this).children('.line').find('.saveStationsName').children('div').length + 3;
                            $(this).children('.line').children('.lineThroughDots').css({"display": "flex"});
                            $(this).children('.line').children('.lineThroughDots').children('.throughDots').css({"margin": "0" + " " + LineWidth/(2*DivNum) + "px", "pointer-events": "auto", "display": "inline-block"});
                            var dotsMargin = LineWidth/DivNum + 10;
                            $(this).children('.line').children('.STAT').children('.saveStationsGap').children('div').css({"width": dotsMargin + "px"});
                            $(this).children('.line').children('.STAT').children('.saveStationsName').children('div').css({"width": dotsMargin + "px"});
                            var dotsFLSpace = $(this).children('.line').children('.lineThroughDots').children('div:first-child').offset().left - $(this).children('.line').children('.SaEdots').offset().left + 5;
                            $(this).children('.line').children('.STAT').children('.saveStationsGap').children('div:first-child, div:last-child').css({"width": dotsFLSpace + "px"});
                        });
                        $('.foldBtn').click(function(){
                            $(this).parent('.showHideBtn').fadeOut('fast', function(){
                                $(this).parent().parent().parent().removeClass('toHigh');
                                $(this).parent().parent().parent().children('.dotss').removeClass('bottomSlide');
                                $(this).children('.showHideStationsName').removeClass('actived');
                                $(this).children('.showHideKm').removeClass('actived');
                                // alert($(this).parent().parent().parent().attr('class'));
                                $(this).parent().parent().children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.lineThroughDots').css({"display": "none"});
                                $(this).parent().parent().children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.btnSlideRight').fadeIn('slow');
                                $(this).parent().parent().children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.STAT').css({"display": "none"});
                                $(this).parent().parent().children('.stations').children('.StationCenter').children('.throughStations').stop().animate({width: '20%'}, 260);
                            });
                        });
                    }); 
                });
            };
        });
    });
