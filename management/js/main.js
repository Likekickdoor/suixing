window.onload = function () {
    //留言
   
    $('.liejian').eq(0).on('click', function () {
        var angle=$('.angle').css('display');
        var hidden=$('.hidden').css('display');

        $(this).toggleClass(function () {
            if (angle == 'none' && hidden == 'none') {
                $('.angle').css('display' , 'block');
                $('.hidden').css('display', 'block');
                $('.angle-1').css('display', 'none');
                $('.hidden-1').css('display', 'none');
                $('.icon-0').eq(0).attr('src','../img/a1.png');
                $('.icon-0').eq(1).attr('src','../img/b.png');                                                
                $('.right').css('opacity',0.3);
                return;
            }
            else {
                $('.angle').css('display', 'none');
                $('.hidden').css('display', 'none');
                $('.icon-0').eq(0).attr('src','../img/a.png');                
                $('.right').css('opacity',1);
                return;
            }
        })
    })
    //任务
    $('.liejian').eq(1).on('click', function () {
        var angle1=$('.angle-1').css('display');
        var hidden1=$('.hidden-1').css('display');

        $(this).toggleClass(function () {
            if (angle1 == 'none' && hidden1 == 'none') {
                $('.angle-1').css('display' , 'block');
                $('.hidden-1').css('display', 'block');
                $('.angle').css('display', 'none');
                $('.hidden').css('display', 'none');
                $('.icon-0').eq(1).attr('src','../img/b1.png');   
                $('.icon-0').eq(0).attr('src','../img/a.png');                             
                $('.right').css('opacity',0.3);
                return;
            }
            else{
                $('.angle-1').css('display', 'none');
                $('.hidden-1').css('display', 'none');
                $('.icon-0').eq(1).attr('src','../img/b.png');                                
                $('.right').css('opacity',1);                
                return;
            }
        })
    })

    //侧面导航切换
    $('.nav-0').eq(0).on('click',function(){
        $('.control').css('display','block');
        $('.data').css('display','none');    
        $('.daily').css('display','one');                    
    })
    $('.nav-0').eq(1).on('click',function(){
        $('.control').css('display','none');
        $('.data').css('display','block');    
        $('.daily').css('display','none');                    
    })
    $('.nav-0').eq(2).on('click',function(){
        $('.control').css('display','none');
        $('.data').css('display','none');    
        $('.daily').css('display','block');                    
    })
};
