<?php
    //SESSION
    session_start();
    if (!isset($_SESSION['visitor']))$_SESSION['visitor']=NULL;
       
    
    //链接 创建数据库
    $con=mysqli_connect('localhost:3306','root','123456');
    if (!$con) {die('Could not connect: ' . mysqli_error());}
    mysqli_query($con,"set names utf8");
    $str1="CREATE DATABASE IF NOT EXISTS Task DEFAULT CHARSET utf8 COLLATE utf8_general_ci;";
    $result=mysqli_query($con,$str1);
    if (!$result) {die('创建数据库失败！:'.mysqli_error($con));}
    mysqli_select_db($con,'Task');
    $str2="CREATE TABLE IF NOT EXISTS Task_1(".
            "username VARCHAR(50) NOT NULL ,".
            "password VARCHAR(50) NOT NULL ,".
            "logintime INT(100) NOT NULL ,".
            "lastlogin DATETIME ,".
            "PRIMARY KEY(username))ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $result=mysqli_query($con,$str2);
    if(!$result) {die('数据表创建失败!: ' . mysqli_error($con));}
    
    //同步时钟
    date_default_timezone_set("Asia/Shanghai");

    // Login
    if (isset($_POST["ins"])&&$_POST["ins"]=="login")
    {
        $user=$_POST["userName"];
        $psw=$_POST["password"];
        if ($user==""||$psw=="")
        {
            $back=['status'=>"0",'msg'=>"密码或用户名为空！"];
            echo json_encode($back);
        }
        else
        {
            $str="SELECT * FROM Task_1 WHERE username='$user' and password='$psw'";
            $result=mysqli_query($con,$str);
            $num=mysqli_num_rows($result); 
            if ($num)
            {                
                mysqli_query($con,"UPDATE Task_1 SET logintime = logintime+1 WHERE username='$user'");
                $result=mysqli_query($con,$str);
                $row=mysqli_fetch_assoc($result);
                $_SESSION['visitor']=$user;
                if ($row['logintime']>"1")
                {
                    $str3="登录成功！这是第".$row['logintime']."次登录，上一次登录是".$row['lastlogin'];
                }
                else 
                {
                    $str3="登录成功！这是第1次登录！";
                }
                $back=['status'=>"1",'msg'=>$str3];
                echo json_encode($back);
                $time=date('Y-m-d H:i:s');
                mysqli_query($con,"UPDATE Task_1 SET lastlogin = '$time' WHERE username='$user'");
                
            }
            else
            {
                $str="SELECT * FROM Task_1 WHERE username='$user'";
                $result=mysqli_query($con,$str);
                $num=mysqli_num_rows($result); 
                if ($num)
                {
                    $str="密码不正确！(▼へ▼メ)";
                }
                else
                {
                    $str="用户不存在！(〝▼皿▼)";
                }
                $back=['status'=>"0",'msg'=>$str];
                echo json_encode($back);
            }            
        }
    }
    // Register
    if (isset($_POST["ins"])&&$_POST["ins"]=="sign")
    {
        $user=$_POST["userName"];
        $psw=$_POST["password"];
        if ($user==""||$psw=="")
        {
            $back=['status'=>"0",'msg'=>"密码或用户名为空！"];
            echo json_encode($back);
        }
        else
        {
            $str="SELECT username FROM Task_1 WHERE username='$user'";
            $result=mysqli_query($con,$str);
            $num=mysqli_num_rows($result); 
            if ($num)
            {                
                $back['status']="0";
                $back['msg']="注册失败，用户名已被占用！(〝▼皿▼)";
                echo json_encode($back);
            }
            else
            {
                $str="INSERT INTO Task_1".
                     "(username,password,logintime)".
                     "VALUES ".
                     "('$user','$psw','0')";
                $result=mysqli_query($con,$str);
                if ($result)
                {
                    $back['status']="1";
                    $back['msg']="注册成功！(<ゝω・)☆";
                    echo json_encode($back);
                }
                else
                {
                    $back['status']="0";
                    $back['msg']="系统繁忙！(〃'▽'〃)";
                    echo json_encode($back);                
                }
            } 
        }
    }
    mysqli_close($con);
?>