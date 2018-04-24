$(document).ready(function(){
    $('#SEbutton').click(function(){
        var tempCity = '';
        tempCity = $('#start').val();
        $('#start').val($('#end').val());
        $('#end').val(tempCity);
    });
    function GETplace(){
        //以下添加自动填写表单内容
        var urlStr = decodeURIComponent(window.location.search);
        var arrStr = urlStr.split('=');
        var URLdpplace = arrStr[3].replace(/[^\u4e00-\u9fa5]/gi, "");
        var URLarrplace = arrStr[4];
        return URLdpplace + ',' + URLarrplace;
    };
    GETplace();
    var strSandEplace = GETplace();
    // alert(strSandEplace);
    arrSandEplace = strSandEplace.split(',');
    var startPlace = arrSandEplace[0];
    var endPlace = arrSandEplace[1];
    $('#start').val(startPlace);
    $('#end').val(endPlace);
    $('.zLeft').children('div').eq(1).html(startPlace);
    $('.zLeft').children('div').eq(3).html(endPlace);
    // alert(startPlace);
    // alert(endPlace);
    function GETJSONLENGTH(jsonData){
        var JSONLENGTH = 0;
        for(var item in jsonData){
            JSONLENGTH ++;
        }
        return JSONLENGTH;
    };
    $.ajax({
        url: './index.php?con=details&med=interchange&start=' + startPlace + '&end=' + endPlace,
        type: 'GET',
        dataType: 'json',
        beforeSend: function(){
            $('.wait').css({"display": "block"});
            $('.title').html('正在查询中...')
        },
        complete: function(){
            $('.wait').css({"display": "none"});
            $('.title').html('需换乘')
        },
        success: function(data){
            console.log(data);
            var tagFeature = "";
            var Price = "";
            var costTime = "";
            function add_about(){
                if(data[i].first.arrive_time && firstBUSwholeMINIUTE == 0){
                    return '约';
                }
                if(data[i].second.arrive_time && secondBUSwholeMINIUTE == 0){
                    return '约';
                }
            }
            function foolerTagFeature(){
                if(data[i].first.arrive_time && data[i].second.arrive_time){
                    tagFeature = 'zBUS zBUS';
                }
                if(data[i].first.arrive_time && data[i].second.Adistance){
                    tagFeature = 'zBUS zTRAIN'
                }
                if(data[i].first.arrive_time && data[i].second.valuation){
                    tagFeature = 'zBUS zPLANE'
                }
                if(data[i].first.arrive_time && data[i].second.length){
                    tagFeature = 'zBUS SHIP'
                }


                if(data[i].first.Adistance && data[i].second.Adistance){
                    tagFeature = 'zTRAIN zTRAIN'
                }
                if(data[i].first.Adistance && data[i].second.arrive_time){
                    tagFeature = 'zTRAIN zBUS'
                }
                if(data[i].first.Adistance && data[i].second.valuation){
                    tagFeature = 'zTRAIN zPLANE'
                }
                if(data[i].first.Adistance && data[i].second.length){
                    tagFeature = 'zTRAIN zSHIP'
                }


                if(data[i].first.f_flightCode && data[i].second.valuation){
                    tagFeature = 'zPLANE zPLANE'
                }
                if(data[i].first.f_flightCode && data[i].second.arrive_time){
                    tagFeature = 'zPLANE zBUS'
                }
                if(data[i].first.f_flightCode && data[i].second.Adistance){
                    tagFeature = 'zPLANE zTRAIN'
                }
                if(data[i].first.f_flightCode && data[i].second.length){
                    tagFeature = 'zPLANE zSHIP'
                }


                if(data[i].first.length && data[i].second.valuation){
                    tagFeature = 'zSHIP zPLANE'
                }
                if(data[i].first.length && data[i].second.arrive_time){
                    tagFeature = 'zSHIP zBUS'
                }
                if(data[i].first.length && data[i].second.Adistance){
                    tagFeature = 'zSHIP zTRAIN'
                }
                if(data[i].first.length && data[i].second.length){
                    tagFeature = 'zSHIP zSHIP'
                }
                return tagFeature;
            };
            function foolerPrice(){
                if(data[i].first.arrive_time && data[i].second.arrive_time){
                    Price = parseInt(data[i].first.price) + parseInt(data[i].second.price);
                }
                if(data[i].first.arrive_time && data[i].second.Adistance){
                    var train_prices = 0;
                    if(data[i].second.hardSeat){
                        train_prices = parseInt(data[i].second.hardSeat);
                    }else if(data[i].second.bSeat){
                        train_prices = parseInt(data[i].second.bSeat);
                    }
                    Price = train_prices + parseInt(data[i].first.price)
                }
                if(data[i].first.arrive_time && data[i].second.valuation){
                    Price = parseInt(data[i].first.price) + parseInt(data[i].second.valuation)
                }

                var firstTrainPrice = 0;
                if(data[i].first.bSeat){
                    firstTrainPrice = parseInt(data[i].first.bSeat);
                }else if(data[i].first.hardSeat){
                    firstTrainPrice = parseInt(data[i].first.hardSeat);
                }
                if(data[i].first.Adistance && data[i].second.Adistance){
                    var train_prices = 0;
                    if(data[i].second.hardSeat){
                        train_prices = parseInt(data[i].second.hardSeat);
                    }else if(data[i].second.bSeat){
                        train_prices = parseInt(data[i].second.bSeat);
                    }
                    Price = firstTrainPrice + train_prices
                }
                if(data[i].first.Adistance && data[i].second.arrive_time){
                    Price = firstTrainPrice + parseInt(data[i].second.price)
                }
                if(data[i].first.Adistance && data[i].second.valuation){
                    Price = firstTrainPrice + parseInt(data[i].second.valuation)
                }


                if(data[i].first.f_flightCode && data[i].second.valuation){
                    Price = parseInt(data[i].first.valuation) + parseInt(data[i].second.valuation)
                }
                if(data[i].first.f_flightCode && data[i].second.arrive_time){
                    Price = parseInt(data[i].first.valuation) + parseInt(data[i].second.price)
                }
                if(data[i].first.f_flightCode && data[i].second.Adistance){
                    var train_prices = 0;
                    if(data[i].second.hardSeat){
                        train_prices = parseInt(data[i].second.hardSeat);
                    }else if(data[i].second.bSeat){
                        train_prices = parseInt(data[i].second.bSeat);
                    }
                    Price = parseInt(data[i].first.valuation) + train_prices
                }
                return Price;

            };
            function foolerCostTime(){
                firstBUSwholeMINIUTE = 0;
                secondBUSwholeMINIUTE = 0;
                firstTRAINwholeMINIUTE = 0;
                secondTRAINwholeMINIUTE = 0;
                firstPLANEwholeMINIUTE = 0;
                secondPLANEwholeMINIUTE = 0;
                if(data[i].first.arrive_time){
                    firstBUSwholeMINIUTE = parseInt(data[i].first.time.split('时')[0])*60 + parseInt(data[i].first.time.split('时')[1]);
                    // alert(typeof firstBUSwholeMINIUTE);             
                }
                if(data[i].second.arrive_time){
                    secondBUSwholeMINIUTE = parseInt(data[i].second.time.split('时')[0])*60 + parseInt(data[i].second.time.split('时')[1]);
                    // alert(typeof secondBUSwholeMINIUTE);                     
                }
                if(data[i].first.Adistance){
                    firstTRAINwholeMINIUTE = data[i].first.BrunTime - data[i].first.ArunTime;
                    // alert(typeof firstTRAINwholeMINIUTE);
                }
                if(data[i].second.Adistance){
                    secondTRAINwholeMINIUTE = data[i].second.BrunTime - data[i].second.ArunTime;
                    // alert(typeof secondTRAINwholeMINIUTE);
                }
                if(data[i].first.f_flightCode){
                    firstPLANEwholeMINIUTE = parseInt(data[i].first.f_flightTime.split('时')[0])*60 + parseInt(data[i].first.f_flightTime.split('时')[1]);                        
                }
                if(data[i].second.valuation){
                    secondPLANEwholeMINIUTE = parseInt(data[i].second.f_flightTime.split('时')[0])*60 + parseInt(data[i].second.f_flightTime.split('时')[1]);                        
                }
                if(data[i].first.arrive_time && data[i].second.arrive_time){
                    // var secondBusTimeHOUR = parseInt(data[i].second.time.split('时')[0]);
                    costTime = Math.floor((firstBUSwholeMINIUTE + secondBUSwholeMINIUTE)/60) + '时' + (firstBUSwholeMINIUTE + secondBUSwholeMINIUTE)%60 + '分';
                    // var secondBusTimeMINT = parseInt(data[i].second.time.split('时')[1]);
                }
                if(data[i].first.arrive_time && data[i].second.Adistance){
                    costTime = Math.floor((firstBUSwholeMINIUTE + secondTRAINwholeMINIUTE)/60) + '时' + (firstBUSwholeMINIUTE + secondTRAINwholeMINIUTE)%60 + '分';
                }
                if(data[i].first.arrive_time && data[i].second.valuation){
                    costTime = Math.floor((firstBUSwholeMINIUTE + secondPLANEwholeMINIUTE)/60) + '时' + (firstBUSwholeMINIUTE + secondPLANEwholeMINIUTE)%60 + '分';
                }


                if(data[i].first.Adistance && data[i].second.Adistance){
                    costTime = Math.floor((firstTRAINwholeMINIUTE + secondTRAINwholeMINIUTE)/60) + '时' + (firstTRAINwholeMINIUTE + secondTRAINwholeMINIUTE)%60 + '分';
                }
                if(data[i].first.Adistance && data[i].second.arrive_time){
                    costTime = Math.floor((firstTRAINwholeMINIUTE + secondBUSwholeMINIUTE)/60) + '时' + (firstTRAINwholeMINIUTE + secondBUSwholeMINIUTE)%60 + '分';
                    // alert(Math.floor((firstTRAINwholeMINIUTE + secondBUSwholeMINIUTE)/60));
                    // alert(Math.floor((firstTRAINwholeMINIUTE + secondBUSwholeMINIUTE)/60));
                }
                if(data[i].first.Adistance && data[i].second.valuation){
                    costTime = Math.floor((firstTRAINwholeMINIUTE + secondPLANEwholeMINIUTE)/60) + '时' + (firstTRAINwholeMINIUTE + secondPLANEwholeMINIUTE)%60 + '分';                        
                }


                if(data[i].first.f_flightCode && data[i].second.valuation){
                    costTime = Math.floor((firstPLANEwholeMINIUTE + secondPLANEwholeMINIUTE)/60) + '时' + (firstPLANEwholeMINIUTE + secondPLANEwholeMINIUTE)%60 + '分';                        
                }
                if(data[i].first.f_flightCode && data[i].second.arrive_time){
                    costTime = Math.floor((firstPLANEwholeMINIUTE + secondBUSwholeMINIUTE)/60) + '时' + (firstPLANEwholeMINIUTE + secondBUSwholeMINIUTE)%60 + '分';                        
                }
                if(data[i].first.f_flightCode && data[i].second.Adistance){
                    costTime = Math.floor((firstPLANEwholeMINIUTE + secondTRAINwholeMINIUTE)/60) + '时' + (firstPLANEwholeMINIUTE + secondTRAINwholeMINIUTE)%60 + '分';                        
                }
                if(costTime == '约0时0分'||costTime == '0时0分'){
                    return '暂无数据'
                }else{
                    return costTime;
                }
            };
            function foolerDisplayTansCity(){
                var firstTansCityNAME = "";
                var secondTansCityNAME = "";
                var NAMEHtml = '';
                function addNAMEHtml(){
                    if(TransportNum == 2){
                        NAMEHtml = '<span>' + firstTansCityNAME + '</span>'
                    }
                    if(TransportNum == 3){
                        NAMEHtml = '<span>' + firstTansCityNAME + '</span>' + '<span class="has">' + '</span>'
                    }
                };
                if(data[i].first.arrive_time){
                    firstTansCityNAME = data[i].first.end_station_name;
                    addNAMEHtml()
                }
                if(data[i].first.Adistance){
                    firstTansCityNAME = data[i].first.arrSta;
                    addNAMEHtml()
                }
                if(data[i].first.f_flightCode){
                    firstTansCityNAME = data[i].first.f_toAirport;
                    addNAMEHtml()
                }
                return NAMEHtml;
            };
            function foolerCycleSplits(){
                var cycleHTMLs = "";
                for(var f=0; f<TransportNum; f++){
                    var StartNameColor = '';
                    var EndNameColor = '';
                    var firstSpanName = '';
                    var lastSpanName = '';
                    var centerSpanClass = '';
                    var singlePrice = '';
                    var singleCostTime = '';
                    var OTHERINFO = '';
                    var SHIPcon = '';
                    if(f==0){
                        StartNameColor = '#12a81e';
                        if(data[i].first.Adistance){
                            centerSpanClass = 'circleSpanTRAIN';
                            firstSpanName = data[i].first.dpSta;
                            lastSpanName = data[i].first.arrSta;
                            singleCostTime = '耗时：' + Math.floor((data[i].first.BrunTime - data[i].first.ArunTime)/60) + "时" + (data[i].first.BrunTime - data[i].first.ArunTime)%60 + '分';
                            OTHERINFO = '车次：' + data[i].first.trainNo + '次';
                            if(data[i].first.hardSeat){
                                singlePrice = '票价：￥' + data[i].first.hardSeat;
                            }else if(data[i].first.bSeat){
                                singlePrice = '票价：￥' + data[i].first.bSeat;
                            }
                        }
                        else if(data[i].first.arrive_time){
                            centerSpanClass = 'circleSpanBUS';
                            firstSpanName = data[i].first.start_station_name;
                            lastSpanName = data[i].first.end_station_name;
                            singlePrice = '票价：￥' + data[i].first.price;
                            singleCostTime = '耗时：' + data[i].first.time;
                        }
                        else if(data[i].first.f_flightCode){
                            centerSpanClass = 'circleSpanPLANE';
                            firstSpanName = data[i].first.f_fromAirport;
                            lastSpanName = data[i].first.f_toAirport;
                            singleCostTime = '耗时：' + data[i].first.f_flightTime;
                            OTHERINFO = '机型：' + data[i].first.f_flightCode;
                            if(data[i].first.valuation){
                                singlePrice = '票价：￥' + data[i].first.valuation;
                            }else{
                                singlePrice = '票价：' + '暂无'
                            }
                        }else if(data[i].first.length){
                            SHIPcon = 'circleSpanSHIP';
                            firstSpanName = data[i].first[0];
                            lastSpanName = data[i].first[1];
                            OTHERINFO = data[i].first[2];
                            singlePrice = '运营时间：' + data[i].first[4];
                            singleCostTime = data[i].first[3];
                            // alert(singleCostTime);
                        }
                    }else if(f==1){
                        if(data[i].second.Adistance){
                            centerSpanClass = 'circleSpanTRAIN';
                            firstSpanName = data[i].second.dpSta;
                            lastSpanName = data[i].second.arrSta;
                            singleCostTime = '耗时：' + Math.floor((data[i].second.BrunTime - data[i].second.ArunTime)/60) + "时" + (data[i].second.BrunTime - data[i].second.ArunTime)%60 + '分';;
                            if(data[i].second.hardSeat){
                                singlePrice = '票价：￥' + data[i].second.hardSeat;
                            }else if(data[i].second.bSeat){
                                singlePrice = '票价：￥' + data[i].second.bSeat;
                            }
                        }
                        else if(data[i].second.arrive_time){
                            centerSpanClass = 'circleSpanBUS';
                            firstSpanName = data[i].second.start_station_name;
                            lastSpanName = data[i].second.end_station_name;
                            singlePrice = '票价：￥' + data[i].second.price;
                            if(data[i].second.time == '0时0分'){
                                singleCostTime = '耗时：' + '未知'
                            }else{
                                singleCostTime = '耗时：' + data[i].second.time;
                            }
                        }
                        else if(data[i].second.valuation){
                            centerSpanClass = 'circleSpanPLANE';
                            firstSpanName = data[i].second.f_fromAirport;
                            lastSpanName = data[i].second.f_toAirport;
                            singleCostTime = '耗时：' + data[i].second.f_flightTime;
                            if(data[i].second.valuation){
                                singlePrice = '票价：￥' + data[i].second.valuation;
                            }else{
                                singlePrice = '票价：' + '暂无'
                            }
                        }else if(data[i].second.length){
                            SHIPcon = 'circleSpanSHIP';
                            firstSpanName = data[i].second[0];
                            lastSpanName = data[i].second[1];
                            OTHERINFO = data[i].second[2];
                            singlePrice = '运营时间：' + data[i].second[4];
                            singleCostTime = data[i].second[3];
                            // alert(singleCostTime);
                        }
                    }
                    if(f==TransportNum-1){
                        EndNameColor = '#2186fa';
                    }else if(f!=0){
                        StartNameColor = 'black';
                        EndNameColor = 'black';
                    };
                    var cycleHTML = 
                    `
                    <section class="splits">
                        <p><span style="color: ${StartNameColor}">${firstSpanName}</span><span class="${centerSpanClass + SHIPcon}">0</span><span style="color: ${EndNameColor}">${lastSpanName}</span></p>
                        <div class="expandSection">
                            <span class="outTHEInfo">
                                <span class="PJ">${singlePrice}</span>
                                <span class="HS">${singleCostTime}</span>
                                <span class="OTHERINFO">${OTHERINFO}</span>
                            </span>
                        </div>
                    </section>
                    `
                    cycleHTMLs += cycleHTML;
                }
                return cycleHTMLs;
            }
            // console.log(data.length)
            var HTMLs = "";
            // json = eval(data);
            for(var i=0; i<data.length; i++){
                var TransportNum = GETJSONLENGTH(data[i]);
                console.log('数组的第' + i + '条' + '有' + TransportNum + '条路线');
                function returnZero(i){
                    if(i<9){
                        i = i+1;
                        return "0" + i;
                    }else if(i>=9){
                        i = i+1;
                        return i;
                    }
                }
                // alert(fooler(Price));
                // alert(fooler(costTime));
                var HTML = 
                `
                <li class="Normal ${foolerTagFeature()}">
                    <div class="zMoreInfo">
                        <div class="zCostTime">
                            <span class="zTime">${foolerCostTime()}</span>
                        </div>
                        <div class="zPrices">
                            <span class="currencySymbol">￥</span>
                            <span class="prices">${foolerPrice()}</span>
                        </div>
                    </div>
                    <section class="ranking"><span>${returnZero(i)}</span></section>
                    <section class="split">
                        <div class="info">
                            <div><span></span></div>
                            <div><span></span></div>
                            <div><span></span></div>
                            <div><span></span></div>
                            <div><span></span></div>
                            <p>${foolerDisplayTansCity()}</p>
                        </div>
                        <div class="expandSection"></div>
                    </section>
                    ${foolerCycleSplits()}
                </li>
                `
                HTMLs += HTML;
            }
            $('.ulTRANSCITIES').html(HTMLs);
            // alert('success');
            showHidep();
            if($('.ulTRANSCITIES').children().length == 0){
                $('.ulTRANSCITIES').addClass('noCity');
                $('.title').html('暂无换乘结果')
            }

        },
        error: function(){
            alert('failed');
        }
    });
    function showHidep(){
        $('li .zMoreInfo').each(function(){
            $(this).parent().children('.split').children('.info').children('div').eq(4).children('span').click(function(){
                $(this).parent().stop().fadeOut('fast');
                $(this).parent().parent().parent().parent().find('p').removeClass('selected');
                // alert($(this).parent().parent().parent().parent().find('p').attr('class'));
            });
        });
        $('.splits').click(function(){
            // $(this).children('p').removeClass('selected');
            $(this).children('.expandSection').stop().slideToggle(400, function(){
                $(this).children('.outTHEInfo').stop().fadeToggle(200);
            });
            var splitColor = $(this).children('p').children('span').eq(1).css('backgroundColor').split(', ');
            splitBGColor = splitColor[0].split('(')[0].replace('rgb', 'rgba') + '(' + splitColor[0].split('(')[1] + ', ' + splitColor[1] + ', ' + splitColor[2].replace(')',', .8)')
            $(this).children('.expandSection').css({"backgroundColor": splitBGColor});
        });
        $('.splits p').click(function(){
            var oExSLength = $(this).parent().parent().find('.expandSection').length -1;
            $(this).toggleClass('selected');
            var selectedLength = $(this).parent().parent().find('.selected').length;
            if(selectedLength == 0){
                $(this).parent().parent().children('.split').children('.info').children('div').eq(4).stop().fadeOut(400);
            }else{
                $(this).parent().parent().children('.split').children('.info').children('div').eq(4).stop().fadeIn(400);
            }
        });
        $('.expandSection').each(function(){
            $(this).click(function(){
                $(this).parent().children('p').toggleClass('selected');
                var selectedLength = $(this).parent().parent().find('.selected').length;
                if(selectedLength == 0){
                    $(this).parent().parent().children('.split').children('.info').children('div').eq(4).stop().fadeOut(400);
                }else{
                    $(this).parent().parent().children('.split').children('.info').children('div').eq(4).stop().fadeIn(400);
                }
            });
        });
        $('.ulTRANSCITIES li .split .info').each(function(){
            $(this).click(function(){
                $(this).parent().parent().children('.splits').find('.expandSection').children('.outTHEInfo').stop().slideUp(200, function(){
                    $(this).parent('.expandSection').slideUp(400);
                });

            //     $(this).parent().parent().children('.splits').find('.expandSection').slideUp(400, function(){
            //         $(this).children('.PJ').fadeOut(400);
            // });
            });
        });
    };
});
// alert(GETplace());
$('#submit').click(function(){
    var zStartPlace = $('#start').val().replace(/\ +/g, "").replace(/[1-9]+/g, "");
    var zEndPlace = $('#end').val().replace(/\ +/g, "").replace(/[1-9]+/g, "");
    // alert(zStartPlace);
    // alert(zEndPlace);
    if(zStartPlace.length != 0 && zEndPlace.length != 0 && zStartPlace != zEndPlace){
        window.location.href = './index.php?con=details&med=display&start=' + zStartPlace + '&end=' + zEndPlace;
    }else if(zStartPlace != 0 && zEndPlace == 0){
        $('#end').focus();
        $('#end').css({"outlineColor": "#e81123"});
        $('#end').css({"backgroundColor": "#fff0f0"});
    }else if(zStartPlace == 0 && zEndPlace != 0){
        $('#start').focus();
        $('#start').css({"outlineColor": "#e81123"});
        $('#start').css({"backgroundColor": "#fff0f0"});
    }else{
        $('#end').focus();
        $('#end').css({"outlineColor": "#e81123"});
        $('#end').css({"backgroundColor": "#fff0f0"});
        $('#start').focus();
        $('#start').css({"outlineColor": "#e81123"});
        $('#start').css({"backgroundColor": "#fff0f0"});
        if(zStartPlace == zEndPlace && zStartPlace != 0 && zEndPlace != 0){
            $(this).parent().addClass('error');
        }
    }
});
$('#start, #end').on('input', function(){
    // console.log($(this).css('outlineColor'));
    $(this).css({"outlineColor": "#57acf6"});
    $(this).css({"backgroundColor": "rgba(255, 255, 255, .8)"});
    $(document).keydown(function(e){
        var key = e.keyCode;
        if(key == 13){
            jQuery("#submit").click();
        }
    });
});
