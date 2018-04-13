<?php
    //session
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
    $str2="CREATE TABLE IF NOT EXISTS Task_2(".
            "id INT UNSIGNED AUTO_INCREMENT ,".
            "username VARCHAR(50) NOT NULL ,".
            "data VARCHAR(65535) NOT NULL ,".
            "time DATETIME ,".
            "PRIMARY KEY(id))ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $result=mysqli_query($con,$str2);
    if(!$result) {die('数据表创建失败!: ' . mysqli_error($con));}
    
    //同步时钟
    date_default_timezone_set("Asia/Shanghai");

    // save
    if (isset($_POST["ins"])&&$_POST["ins"]=="save")
    {
        $user=$_POST["username"];
        $da=$_POST["data"];
        /*if ($user==""||$psw=="")
        {
            $back=['status'=>"0",'msg'=>"密码或用户名为空！"];
            echo json_encode($back);
        }
        else*/
        {
            $time=date('Y-m-d H:i:s');
            $str="INSERT INTO Task_2".
            "(username,data,time)".
            "VALUES ".
            "('$user','$da','$time')";
            $result=mysqli_query($con,$str); 
            
        }
    }
    
    //gett
    if (isset($_POST["ins"])&&$_POST["ins"]=="gett")
    {
        $str="SELECT * FROM Task_2";
        $result=mysqli_query($con,$str);
        $i=1;
        while($row=mysqli_fetch_assoc($result))
        {
            //$back[$i++]=htmlspecialchars($row); 
            $back[$i++]=$row;   
        }
        $back[0]=$i-1;
        $back['user']=$_SESSION['visitor'];
        echo json_encode($back);
    }   

    //logout
    if (isset($_POST["ins"])&&$_POST["ins"]=="logout")
    {
        unset($_SESSION['visitor']);
    }

    //delete
    if (isset($_POST["ins"])&&$_POST["ins"]=="delete")
    {
        $id=$_POST['id'];
        $owner=$_POST['owner'];
        $str="DELETE FROM Task_2 WHERE id='$id' and username='$owner'";
        $result=mysqli_query($con,$str);
    }

    //updata
    if (isset($_POST["ins"])&&$_POST["ins"]=="updata")
    {
        $id=$_POST['id'];
        $owner=$_POST['owner'];
        $data=$_POST['data'];
        $time=date('Y-m-d H:i:s');
        $str="UPDATE `Task_2` SET data = '$data',time = '$time' WHERE username='$owner' and id='$id'";
        $result=mysqli_query($con,$str);
        echo json_encode($time);
    }
    mysqli_close($con);
?>