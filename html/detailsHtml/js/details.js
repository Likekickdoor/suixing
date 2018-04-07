    function GETplace(){
        //以下添加自动填写表单内容
        var urlStr = decodeURIComponent(window.location.search);
        // alert(urlStr);
        var arrStr = urlStr.split('=');
        var URLdpplace = arrStr[3].replace(/[^\u4e00-\u9fa5]/gi, "");
        var URLarrplace = arrStr[4];
        if(URLdpplace != 0 && URLarrplace != 0){
            $('[name="dpplace"]').attr("placeholder", URLdpplace);//css({"value": URLdpplace});
            $('[name="arrplace"]').attr("placeholder", URLarrplace);//css({"value": URLarrplace});
        }
        // alert(arrStr[2].replace(/[^\u4e00-\u9fa5]/gi, ""));
        // alert(arrStr[3]);
        // alert(urlStr.replace(/[^\u4e00-\u9fa5]/gi, ""));
        return URLdpplace + ',' + URLarrplace;
    };
    GETplace();
    var strSandEplace = GETplace();
    // alert(strSandEplace);
    arrSandEplace = strSandEplace.split(',');
    var startPlace = arrSandEplace[0];
    var endPlace = arrSandEplace[1];
    // alert(startPlace);//hongjiang
    // alert(endPlace);//taoyuan
    // alert(startPlace);
    // alert(endPlace);
        $.ajax({
            url: 'index.php?con=details&med=show&start=' + startPlace + '&end=' + endPlace,
            dataType: 'json',
            type: 'GET',
            beforeSend: function(){
                $('.wait').css({"display": "block"});
            },
            complete: function(){
                $('.wait').css({"display": "none"});
            },
            success: function(data){
                // alert($('.ulSUGGEST').children('div').length);
                // console.log(data);
                var dataLength = data.length;
                // alert(dataLength);
                var firstW = data[0];
                var DataFeature = "";
                // alert(dataLength);
                if(data == ''){
                    // console.log($('.ulSUGGEST').children('div').length);
                    $('.ulSUGGEST').addClass('noresult');
                    // alert('none');
                    // alert(data);
                };
                if(data[0].trainNo){
                    // alert('isTrain');
                    DataFeature = 'H';
                }else if(data[0].end_station_name){
                    // alert('isBUS');
                    DataFeature = 'Q';
                }
                // alert(trainDataFeature);
                
                if(DataFeature == 'H'){
                    var trainHTMLs = "";
                    for(var ts=0; ts<dataLength; ts++){
                        var aStartTime = data[ts].AstartTime;
                        var ARRaStartTime = aStartTime.split(':');
                        var ARRaStartTimeSF = ARRaStartTime[ts] + ':' + ARRaStartTime[ts];
                        var bStartTime = data[ts].BstartTime;
                        var ARRbStartTime = bStartTime.split(':');
                        var ARRbStartTimeSF = ARRbStartTime[ts] + ':' + ARRbStartTime[ts];
                        var RUNingTIME = data[ts].BrunTime - data[ts].ArunTime;
                        RUNTIME = Math.floor(RUNingTIME/60) + '时' + RUNingTIME%60 + '分';
                        var SEATPRICE = ""
                        if(data[ts].hardSeat){
                            SEATPRICE = data[ts].hardSeat;
                        }else if(data[ts].bSeat){
                            SEATPRICE = data[ts].bSeat;
                        }
                        // alert(RUNTIME);
                        // alert(ARRaStartTimeSF);
                        // alert(ARRbStartTimeSF);
                        var trainHTML = 
                        `
                        <li class="lists Normal H">
                        <div class="dotss"></div>
                        <div class="moreTags">
                            <div class="moreTagsLeft">
                                <div class="tg1">荐</div>
                                <div class="tg2">直达</div>
                                <div class="tg3">路线最短</div>
                                <span class="tg4">
                                    <span class="tt1"><img src="html/detailsHtml/img/train.svg" /></span>
                                    <span class="tt2"><img src="html/detailsHtml/img/bus.svg" /></span>
                                    <span class="tt3"><img src="html/detailsHtml/img/plane.svg" /></span>
                                    <span class="tt4"><img src="html/detailsHtml/img/ship.svg" /></span>
                                </span>
                                <span class="showHideWindow"></span>
                                <sapn class="showHideBtn">
                                    <span class="showHideStationsName"></span>
                                    <span class="showHideKm"></span>
                                    <span class="foldBtn"></span>
                                </span>
                            </div>
                            <div class="moreTagsRight">
                                <span class="currencySymbol">￥</span>
                                <span class="prices">
                                `+
                                SEATPRICE
                                +
                                `
                                </span>
                            </div>
                            <div class="stations">
                                <div class="StationLeft">
                                    <div class="number">${data[ts].trainNo}</div>
                                    <div class="costTime">
                                    `+
                                    RUNTIME
                                   +` 
                                    </div>
                                </div>
                                <div class="StationCenter">
                                    <div class="startStation">
                                        <div class="startTime">
                                    `+ARRaStartTimeSF+

                                    `
                                        </div>
                                        <div class="startPlace">${data[ts].dpSta}</div>
                                    </div>
                                    <div class="throughStations slideRight">
                                        <div class="line" id="first">
                                            <div class="SaEdots"></div>
                                            <div class="CenterDots"></div>
                                            <div class="btnSlideRight" onclick="station_stopApi(<?=$id.','.$dataOne['about_id'].','.$dataOne['dpSort'].','.$dataOne['arrSort']; ?>)">经停信息</div>
                                            <div class="lineThroughDots"></div>
                                            <div class="STAT"  id=<?php echo '"train_stop'."$id".'"';?> >
                                                <div class="saveStationsGap">
                                                     <!-- <div>666</div> -->
                                                     <!-- 停站距离 -->
                                                </div>
                                                <div class="space"></div>
                                                <div class="saveStationsName">
                                                    <div>默认站X</div>
                                                    <!-- 留一站才停站信息按钮显示 -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="endStation">
                                        <div class="endTime">
                                        `+ARRbStartTimeSF+
                                        `
                                        </div>
                                        <div class="endPlace">${data[ts].arrSta}</div>
                                    </div>
                                </div>
                                <div class="StationRight"></div>
                            </div>
                        </div>
                        <div class="fullCover" id="fullCover">
                            <div class="centerBox">
                                <div class="caption">
                                    <div class="closeBtn" id="closeWindow"><div class="foldBtn"></div></div>
                                    <div class="StarttoEnd">
                                        <div class="wlineInfo">
                                            <div class="wStart">长沙南</div>
                                            <div class="to">至</div>
                                            <div class="wEnd">北京西</div>
                                        </div>
                                        <div class="wCostTime">22时02分</div>
                                        <div class="wStationNum">34</div>
                                    </div>
                                    <div class="wNumber"></div>
                                </div>
                                <div class="content">
                                    <div class="outOFcolumn">
                                        <div class="column" id=<?php echo '"train_stopWin'."$id".'"';?> >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                        `             
                        trainHTMLs += trainHTML;
                    }
                    $('.ulSUGGEST').append(trainHTMLs);
                    $('.ulSUGGEST').find('.lists.Normal').addClass('J');
                    $('.number').each(function(){
                        $(this).addClass('addci');
                            var numberLength = $(this).html().length * 10;
                            if(numberLength > 60){
                                $(this).removeClass('addci');
                                $(this).css({"width": "100%", "padding": "0", "fontSize": "14px"});
                                $(this).parent().children('.costTime').css({"width": "100%", "padding": "0"});
                            }
                    });
                    // console.log(trainHTMLs);
                // alert($('.ulSUGGEST').children('div').length);
                // $('.ulSUGGEST').append('<div></div>');
                }else if(DataFeature == 'Q'){
                    var busHTMLs = "";
                    for(var bs=0; bs<dataLength; bs++){
                        var busHTML = 
                        `
                        <li class="lists Normal Q">
                        <div class="dotss"></div>
                        <div class="moreTags">
                            <div class="moreTagsLeft">
                                <div class="tg1">荐</div>
                                <div class="tg2">直达</div>
                                <div class="tg3">路线最短</div>
                                <span class="tg4">
                                    <span class="tt1"><img src="html/detailsHtml/img/train.svg" /></span>
                                    <span class="tt2"><img src="html/detailsHtml/img/bus.svg" /></span>
                                    <span class="tt3"><img src="html/detailsHtml/img/plane.svg" /></span>
                                    <span class="tt4"><img src="html/detailsHtml/img/ship.svg" /></span>
                                </span>
                                <span class="showHideWindow"></span>
                                <sapn class="showHideBtn">
                                    <span class="showHideStationsName"></span>
                                    <span class="showHideKm"></span>
                                    <span class="foldBtn"></span>
                                </span>
                            </div>
                            <div class="moreTagsRight">
                                <span class="currencySymbol">￥</span>
                                <span class="prices">
                                ${data[bs].price}
                                </span>
                            </div>
                            <div class="stations">
                                <div class="StationLeft">
                                    <div class="number"></div>
                                    <div class="costTime">
                                    ${data[bs].timer}
                                    </div>
                                </div>
                                <div class="StationCenter">
                                    <div class="startStation">
                                        <div class="startTime">${data[bs].start_time}
                                        </div>
                                        <div class="startPlace">${data[bs].start_station_name}</div>
                                    </div>
                                    <div class="throughStations slideRight">
                                        <div class="line" id="first">
                                            <div class="SaEdots"></div>
                                            <div class="CenterDots"></div>
                                            <div class="btnSlideRight" onclick="station_stopApi(<?=$id.','.$dataOne['about_id'].','.$dataOne['dpSort'].','.$dataOne['arrSort']; ?>)">经停信息</div>
                                            <div class="lineThroughDots"></div>
                                            <div class="STAT"  id=<?php echo '"train_stop'."$id".'"';?> >
                                                <div class="saveStationsGap">
                                                     <!-- <div>666</div> -->
                                                     <!-- 停站距离 -->
                                                </div>
                                                <div class="space"></div>
                                                <div class="saveStationsName">
                                                    <div>默认站X</div>
                                                    <!-- 留一站才停站信息按钮显示 -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="endStation">
                                        <div class="endTime">${data[bs].arrive_time}
                                        </div>
                                        <div class="endPlace">${data[bs].end_station_name}</div>
                                    </div>
                                </div>
                                <div class="StationRight"></div>
                            </div>
                        </div>
                    </li>
                        `
                        busHTMLs += busHTML;
                    }
                }
                $('.ulSUGGEST').append(busHTMLs);
                $('.ulSUGGEST').find('.lists.Normal').addClass('J');
                $('.number').each(function(){
                    $(this).addClass('addci');
                        var numberLength = $(this).html().length * 10;
                        if(numberLength > 60){
                            $(this).removeClass('addci');
                            $(this).css({"width": "100%", "padding": "0", "fontSize": "14px"});
                            $(this).parent().children('.costTime').css({"width": "100%", "padding": "0"});
                        }
                });
                // console.log(trainHTMLs);
                // // alert(data[0].Adistance);
                // if($('.ulSUGGEST').children('div').length == 3){
                //     // alert('a');
                //     $('.ulSUGGEST').addClass('noresult');
                // }
            },
            error: function(){
                // alert('失败');
            }
        });
        $.ajax({
            // index.php?con=ajax&med=getCenterCity&start=洪江&end=桃源
            url: 'index.php?con=ajax&med=getCenterCity&start=' + startPlace + '&end=' + endPlace,
            dataType: 'text',
            type: 'GET',
            beforeSend: function(){
                $('.wait').css({"display": "block"});
            },
            complete: function(){
                $('.wait').css({"display": "none"});
            },
            success: function(data){
                // console.log(data);
                if(data == 'true'){
                    $('.sorts').css({"display": "none"});
                    $('.ACity').css({"display": "none"});
                    $('.BCity').css({"display": "none"});
                    $('.ulSUGGEST, .ulTRAIN, .ulBUS, .ulPLANE, .ulSHIP').css({"paddingTop": "15px"});
                }else{
                    $('.ulSUGGEST, .ulTRAIN, .ulBUS, .ulPLANE, .ulSHIP').css({"paddingTop": "15px"});
                    var arrData = data.split('|');
                    var ACities = arrData[0].split(',');
                    // alert(ACities);
                    var ACitiesLength = ACities.length;
                    var htmlACs = "";
                    for(var x=0; x<ACitiesLength; x++){
                        var htmlAC = '<div>' + ACities[x] + '</div>'
                        htmlACs += htmlAC;
                    }
                    $('.ACity').html('<span class="Udot"></span><span class="Bdot"></span>' + htmlACs);
                    
                    var BCities = arrData[1].split(',');
                    // alert(arrData[1]);
                    var BCitiesLength = BCities.length;
                    var htmlBCs = "";
                    for(var y=0; y<BCitiesLength; y++){
                        var htmlBC = '<div>' + BCities[y] + '</div>'
                        htmlBCs += htmlBC;
                    }
                    $('.BCity').html('<span class="Udot"></span><span class="Bdot"></span>' + htmlBCs);
                    // alert(arrData[0]);
                    // alert(arrData[1]);
                    // alert('success');
                    $('.BCity div').each(function(){
                        $(this).click(function(){
                            var strCity = $(this).html();
                            $(this).parent().parent().children('.sorts').children('.clearCities').css({"margin-left": "-200px"});
                            $(this).parent().parent().children('.sorts').children('.CityB').stop().animate({width: "25%"}, 200, function(){
                                $(this).html('第二个中转城市：' + '<b style="color: #57acf6">' + strCity + '</b>' + '<a></a>');
                            });
                        });
                    });
                    $('.ACity div').each(function(){
                        $(this).click(function(){
                            var strCity = $(this).html();
                            $(this).parent().parent().children('.sorts').children('.CityA').stop().animate({width: "25%"}, 200, function(){
                                $(this).html('第一个中转城市：' + '<b style="color: #57acf6">' + strCity + '</b>' + '<a></a>');
                            });
                            $.ajax({
                                url: 'index.php?con=ajax&med=chooseFristCity&start=' + startPlace + '&end=' + endPlace + '&fristCity=' + strCity,
                                type: 'GET',
                                dataType: 'text',
                                beforeSend: function(){
                                    $('.wait').css({"display": "block"});
                                },
                                complete: function(){
                                    $('.wait').css({"display": "none"});
                                },
                                success: function(BCitiesNames){
                                    // alert(BCitiesNamesLength);
                                    // console.log(BCitiesNames);
                                    arrBCitiesNames = BCitiesNames.split(',');
                                    // alert(arrBCitiesNames[0]);
                                    // alert(arrBCitiesNames[1]);
                                    var BCitiesNamesLength = arrBCitiesNames.length;
                                    var BCitiesHTMLs = "";
                                    for(var a=0; a<BCitiesNamesLength-1; a++){
                                        var BCitiesHTML = '<div>' + arrBCitiesNames[a] + '</div>'
                                        BCitiesHTMLs += BCitiesHTML;
                                    }
                                    $('.BCity').html('<span class="Udot"></span><span class="Bdot"></span>' + BCitiesHTMLs);
                                },
                                error: function(){
                                    alert('failed');
                                }
                            });
                        });
                    });
                }
            },
            error: function(){
                // alert('failed');
            }
        });
  
    // alert(GETplace());
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
    $('.listCityName').children('li').each(function(){
        $(this).click(function(){
            var tempCityName = $(this).html();
            $(this).parent().parent().children('input').val(tempCityName);
        });
    });
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
    $('.ulSUGGEST').find('.lists.Normal').addClass('J').addClass('Z');
    $('.ulTRAIN').find('.lists.Normal').addClass('H').addClass('Z');
    $('.ulBUS').find('.lists.Normal').addClass('Q');
    $('.ulPLANE').find('.lists.Normal').addClass('F').addClass('Z').addClass('L');
    $('.ulSHIP').find('.lists.Normal').addClass('C');

    $('ul').children('li:last-child').addClass('last');//【line #216】
    $('#SEbutton').click(function () {
        var str1 = $('#start').val();
        var str2 = $('#end').val();
        var str3 = $('#start').attr('placeholder');
        var str4 = $('#end').attr('placeholder');
        if(str1.length==0 && str2.length==0){
            if(str3 != '输入起点' && str4 != '输入终点' && str1.length != 0 && str2.length != 0 ){
                $('#start').attr('placeholder', str4);
                $('#end').attr('placeholder', str3);
            }
        }else if(str1.length!=0 && str2.length==0){
            $('#start').val(str4);
            $('#end').val(str1)
        }else if(str1.length==0 && str2.length!=0){
            $('#start').val(str3);
            $('#end').val(str2)
        }else if(str1.length!=0 && str2.length!=0){
            $('#start').val(str2);
            $('#end').val(str1)
        }
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
        $('.ulSUGGEST').css({"display": "table"});
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
    $('.sorts').parent().each(function(){
        var listLength = $(this).children('.lists').length;
            if(listLength == 0){
                $(this).addClass('noInfo');
                $('.ulSUGGEST').removeClass('noInfo');
            }
        var strTransName = $(this).parent().parent().parent().children('.active').next().html();
    });
    
    var browserHeight = $(window).height();
    $('.line').each(function(){
        var length = $(this).find('.saveStationsName').children('div').length;
        var oThisFullCover = $(this).parent().parent().parent().parent().parent().children('.fullCover');
        var NewstrwNumber = $(this).parent().parent().parent().children('.StationLeft').children('.number').html();
        var NewstrwCostTime = $(this).parent().parent().parent().children('.StationLeft').children('.costTime').html();
        var NewstrStartTime = $(this).parent().parent().parent().children('.StationCenter').children('.startStation').children('.startTime').html();
        var NewstrEndTime = $(this).parent().parent().parent().children('.StationCenter').children('.endStation').children('.endTime').html();
        // alert('a');
        var strNoDotsStartTime = NewstrStartTime.substr(0, 2) + NewstrStartTime.substr(-2, 2);
        var strNoDotsEndTime = NewstrEndTime.substr(0, 2) + NewstrEndTime.substr(-2, 2);
        // alert(strNoDotsStartTime);
        // alert(strNoDotsEndTime);
        var CostTimeOF24 = NewstrwCostTime.split('时');
        var minTemp = parseInt(CostTimeOF24[1])/60;
        var CostTimeOFdec = parseFloat(parseInt(CostTimeOF24[0]) + minTemp);
        var numCostTimeOFdecOF24 = Math.ceil(CostTimeOFdec/24);
        if(parseInt(strNoDotsStartTime) >= parseInt(strNoDotsEndTime)){
            $(this).parent().parent().parent().children('.StationCenter').children('.endStation').children('.endTime').addClass('tow');
            if(numCostTimeOFdecOF24 >= 1){
                $(this).parent().parent().parent().children('.StationCenter').children('.endStation').children('.endTime').addClass('tow');
                if(numCostTimeOFdecOF24 >= 2){
                    $(this).parent().parent().parent().children('.StationCenter').children('.endStation').children('.endTime').addClass('three');
                    if(numCostTimeOFdecOF24 >= 3){
                        $(this).parent().parent().parent().children('.StationCenter').children('.endStation').children('.endTime').addClass('four');
                    }
                }
            }
        }else if(parseInt(strNoDotsStartTime) < parseInt(strNoDotsEndTime)){
            if(numCostTimeOFdecOF24 > 1){
                $(this).parent().parent().parent().children('.StationCenter').children('.endStation').children('.endTime').addClass('tow');
                if(numCostTimeOFdecOF24 >= 2){
                    $(this).parent().parent().parent().children('.StationCenter').children('.endStation').children('.endTime').addClass('three');
                    if(numCostTimeOFdecOF24 >= 3){
                        $(this).parent().parent().parent().children('.StationCenter').children('.endStation').children('.endTime').addClass('four');
                    }
                }
            }
        }
        // alert(this)
        // alert(strNoDotsStartTime);
        // alert(strNoDotsEndTime);
        // if()
        if(length > 11){
            $(this).children('.newbtnSlideRight').removeClass('newbtnSlideRight').addClass('btnSlideRight');
            $(this).children('.btnSlideRight').click(function(){
                $(this).parent().parent().parent().parent().parent().children('.moreTagsLeft').children('.showHideWindow').css({"display": "block"});
                $(this).removeClass('btnSlideRight').addClass('newbtnSlideRight').css({"display": "none"});
                $(this).parent().children('.lineThroughDots').css({"display": "none"});
                $(this).parent().parent().parent().parent().parent().children('.moreTagsLeft').children('.showHideBtn').remove();
                $(this).parent().children('.line').remove();
                oThisFullCover.find('.wStationNum').html(length);
                oThisFullCover.find('.wCostTime').html(NewstrwCostTime);
                oThisFullCover.find('.wNumber').html(NewstrwNumber);
                var NewwStartPlace = $(this).parent().parent().parent().parent().children('.StationCenter').children('.startStation').children('.startPlace').html();
                var NewwEndPlace = $(this).parent().parent().parent().parent().children('.StationCenter').children('.endStation').children('.endPlace').html();
                oThisFullCover.children('.centerBox').children('.caption').children('.StarttoEnd').children('.wlineInfo').children('.wStart').html(NewwStartPlace);
                oThisFullCover.children('.centerBox').children('.caption').children('.StarttoEnd').children('.wlineInfo').children('.wEnd').html(NewwEndPlace);
                var timer = setInterval(SetTimer, 8000);
                function SetTimer(){
                    oThisFullCover.children('.centerBox').children('.caption').children('.StarttoEnd').children('.wCostTime').show(1000, function(){
                        $(this).fadeIn(1000);
                        setTimeout(function(){
                            oThisFullCover.children('.centerBox').children('.caption').children('.StarttoEnd').children('.wCostTime').fadeOut(1000, function(){
                                    $(this).hide(1000);
                                });
                        }, 5000);
                    });
                };
                oThisFullCover.fadeIn('fast', function(){
                    oThisFullCover.children('.centerBox').fadeIn(500);
                    $('html').css({"overflow": "hidden"});
                });
                oThisFullCover.children('.centerBox').children('.caption').children('.closeBtn').children('.foldBtn').click(function(){
                        $(this).parent().parent().parent().parent().parent().find('.newbtnSlideRight').css({"display": "block"});
                        oThisFullCover.children('.centerBox').fadeOut('fast', function(){
                        oThisFullCover.css({"display": "none"});
                        $('html').css({"overflow": "auto"});
                        // clearInterval(timer);
                        $('.newbtnSlideRight').css({"display": "none"});
                });
                });
                if(length > 11 && length <= 20){
                    var listsAllHeight = length*35 + 20 ;
                    var oThisFullCoverCenterBoxHeigt = listsAllHeight + 41;
                    var windowMarginTop = (browserHeight - oThisFullCoverCenterBoxHeigt)/2;
                    oThisFullCover.children('.centerBox').children('.content').find('.column').css({"width": "230px", "height": listsAllHeight + "px"});
                    oThisFullCover.children('.centerBox').css({"margin-top": windowMarginTop + "px"});
                }else if(length > 21 && length <= 40){
                    oThisFullCover.children('.centerBox').children('.content').find('.column').css({"width": "460px"});
                }else{
                    oThisFullCover.children('.centerBox').children('.content').find('.column').css({"width": "690px"});
                }
            });
            
        }else{
            for(var j=0; j<length; j++){
            $('<div />').appendTo($(this).children('.lineThroughDots')).addClass('throughDots').end();
            
        };

        }
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

// alert(startPlace);
// alert(endPlace);
    $('#submit').click(function(){
        var startPlace = $('#start').val().replace(/\ +/g, "");
        var endPlace = $('#end').val().replace(/\ +/g, "");
        // if(startPlace.length > 0 && endPlace.length > 0 && startPlace!=endPlace){
        //     // './index.php?con=details&med=show&start=' + startPlace + '&end=' + endPlace
        // }
        // alert('ok');
        // if(startPlace.length==0 || endPlace.length==0){

        // }else{
            
        // }
        if(startPlace.length != 0 && endPlace.length == 0){
            if(startPlace != $('#end').attr('placeholder')){
                window.location.href = './index.php?con=details&med=display&start=' + startPlace + '&end=' + $('#end').attr('placeholder');
            }else{
                $(this).parent().addClass('error');
            }
        }else if(startPlace.length == 0 && endPlace.length !=0){
                // alert('a');
            if($('#start').attr('placeholder') != endPlace){
                window.location.href = './index.php?con=details&med=display&start=' + $('#start').attr('placeholder') + '&end=' + endPlace;
            }else{
                $(this).parent().addClass('error');
            }
        }else if(startPlace.length == 0 && startPlace.length == 0){
            if($('#start').attr('placeholder') != $('#end').attr('placeholder')){
                window.location.href = './index.php?con=details&med=display&start=' + $('#start').attr('placeholder') + '&end=' + $('#end').attr('placeholder');
            }else{
                $(this).parent().addClass('error');
            }
        }else if(startPlace.length != 0 && startPlace.length != 0){
            if( startPlace != endPlace){
                window.location.href = './index.php?con=details&med=display&start=' + startPlace + '&end=' + endPlace;
            }else{
                $(this).parent().addClass('error');
            }
        }
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
        $(this).children('.moreTags').children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.btnSlideRight').click(function(){
            var divsNumber = $(this).parent().children('.STAT').parent().parent()
            $(this).parent().children('.STAT').children('.saveStationsName').children('div').css({"display:": "none"});
            var oThisListsNormal = $(this).parent().parent().parent().parent().parent().parent();
                $('.btnSlideRight, .newbtnSlideRight').each(function(){
                    $(this).click(function(){
                        // alert('<11');
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
                            // alert('a');
                            $(this).children('.line').children('.STAT').children('.saveStationsGap').children('div:first-child, div:last-child').css({"width": dotsFLSpace + "px"});
                        });
                        $('.foldBtn').click(function(){
                            $(this).parent('.showHideBtn').fadeOut('fast', function(){
                                $(this).parent().parent().parent().removeClass('toHigh');
                                $(this).parent().parent().parent().children('.dotss').removeClass('bottomSlide');
                                $(this).children('.showHideStationsName').removeClass('actived');
                                $(this).children('.showHideKm').removeClass('actived');
                                $(this).parent().parent().children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.lineThroughDots').css({"display": "none"});
                                $(this).parent().parent().children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.btnSlideRight').fadeIn('slow');
                                $(this).parent().parent().children('.stations').children('.StationCenter').children('.throughStations').children('.line').children('.STAT').css({"display": "none"});
                                $(this).parent().parent().children('.stations').children('.StationCenter').children('.throughStations').stop().animate({width: '20%'}, 260);
                            });
                        });
                    }); 
                });
            
        });
    });
    $('.showHideWindow').each(function(){
        $(this).click(function(){
            var oThisFullCover = $(this).parent().parent().parent().children('.fullCover');
            oThisFullCover.css({"display": "block"});
            oThisFullCover.children('.centerBox').css({"display": "block"});
        });
    });
    $('.CityA').click(function(){
        $(this).parent().children('.CityB').children('.selected').removeClass('selected');
        $(this).parent().parent().children('.BCity').slideUp(400);
        $(this).parent().parent().children('.ACity').slideToggle(400);
        $(this).parent().parent().children('.ACity').css({"display": "flex"});
    });
    // $('.ACity div').each(function(){
    //     $(this).click(function(){
    //         alert(startPlace);
    //         alert(endPlace);
    //         var strCity = $(this).html();
    //         $(this).parent().parent().children('.sorts').children('.CityA').html('已选择中转城市：' + strCity + '<a></a>');
    //         // alert(startPlace);
    //         // alert(endPlace);
    //         // $.ajax({});
    //     });
    // });
    $('.CityB').click(function(){
        $(this).parent().children('.CityA').children('.selected').removeClass('selected');
        $(this).parent().parent().children('.ACity').slideUp(400);
        $(this).parent().parent().children('.BCity').slideToggle(400);
        $(this).parent().parent().children('.BCity').css({"display": "flex"});
    });
    