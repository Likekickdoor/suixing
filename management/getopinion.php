<?php
/**
 * 插入用户留言模块
 * @author rwb
 * @version 1.0
 * @data 2018-4-6
 */

 session_start();           //开启会话用于判断用户是不是打开主页发送留言
 $cur_dir = dirname(__FILE__); //获取当前文件的目录
 chdir($cur_dir); //把当前的目录改变为指定的目录。
 require_once("../MVC_frame/mysql.class.php");

 /**
  * 判断用户留言信息的格式是否正确并插入进去的类
  * @author rwb
  * @version 1.0
  * @date 2018,3.23
  * 插入用户留言
  */
  class insertopinion {
      /**
       * 检测用户留言的信息并查入数据库中
       * 获取并插入用户留言的方法
       * @access public
       * @since  数据库封装好的类，和获取信息得类
       * @return null 这个ip信息在数据库的主键 把状态信息输出来
       */
      public function insert_opinion(){

        $checkmail="/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/";            //检测邮箱格式是不是正确
        $pdoSqlObject = new pdoSql();
          if(empty($_SESSION['ip'])){                                           //如果用户没有正常打开页面就不插入留言
              die;
          }else{

            if(mb_strlen($_POST['y_name'])>100){                                //判断检测名字的长度
                echo "名字太长";
            }else if(mb_strlen($_POST['y_email'])>300){                         //邮箱地址的长度
                echo "电子邮箱地址太长";
            }else
                
                if(!preg_match($checkmail,$_POST['y_email'])){                       //用正则表达式函数进行判断  
                    echo "电子邮箱格式不正确";  
                 }else if(mb_strlen($_POST['opinion'])>500){                    //能存放评论字数
                    echo "评论字数太多了";  
                 }else  
            
              if(!empty($_POST['y_name'])&&!empty($_POST['y_email'])&&!empty($_POST['opinion'])){       //判断不为空
                $opinipn = htmlentities($_POST['opinion']);                                              //评论html标签的反转义

                $i_leave = array("ip_id"=>$_SESSION['ip'],"y_name"=>$_POST['y_name'],"y_email"=>$_POST['y_email'],"opinion"=>$opinipn);
                $leave_id = $pdoSqlObject->insert("management_leave",$i_leave);                         //执行插入，返回对应的id

                if(empty($leave_id)){
                    echo "留言失败";
                }else{
                    echo "留言成功";
                }
              }
          }
      }
  }

  $test = new insertopinion();
  $test->insert_opinion();