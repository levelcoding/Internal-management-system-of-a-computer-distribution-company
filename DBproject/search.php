<?php session_start();?>
<!DOCTYPE html>
<html>
    <head>
        <title>Search Page</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/customize.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/jasny-bootstrap.css">
        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php
            include 'conn.php';

            if($_GET['keyword']){
                $keyword = $_GET['keyword'];
                $result1=mysqli_query($conn,"SELECT * FROM product WHERE name LIKE '%$keyword%'");
            }
            elseif ($_GET['type'] || $_GET['cate']) {
                $type=$_GET['type'];
                $cate=$_GET['cate'];
                $result1=mysqli_query($conn,"SELECT * FROM product WHERE productType='$type' AND category='$cate'");
            }
            $Page_size=8;
            $count = mysqli_num_rows($result1);
            $page_count = ceil($count/$Page_size);

            $init=1;
            $page_len=7;
            $max_p=$page_count;
            $pages=$page_count;

            //判断当前页码
            if(empty($_GET['page'])||$_GET['page']<0){
            $page=1;
            }else {
            $page=$_GET['page'];
            }

            $offset=$Page_size*($page-1);
            if($_GET['keyword']){
                $sql="SELECT * FROM product WHERE name LIKE '%$keyword%' LIMIT $offset,$Page_size";
            }
            elseif ($_GET['type'] && $_GET['cate']) {
                $sql="SELECT * FROM product WHERE productType='$type' AND category='$cate' LIMIT $offset,$Page_size";
            }
            $result=mysqli_query($conn,$sql);
     
        ?>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div style="margin-left: 180px !important;">
                        <a class="navbar-brand" href="#">Dell</a>
                    </div>    
                </div>

                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul class="nav navbar-nav">
                        <!-- Server Dropdown -->
                    </ul>

                    <!-- User Dropdown -->
                    <div  style="margin-left: 24% !important;">
                        <ul class="nav navbar-nav navbar-default navbar-center">
                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">For Home<span class="caret"></span></a>
                              <ul class="dropdown-menu" style="color: #0099CC !important;" role="menu">
                                  <li><a href="search.php?type=home&cate=laptop&keyword=" style="color: #000000 !important;">Laptops</a></li>
                                <li class="divider"></li>
                                <li><a href="search.php?type=home&cate=PC&keyword=" style="color: #000000 !important;">PC</a></li>
                                <li class="divider"></li>
                                <li><a href="search.php?type=home&cate=accessory&keyword=" style="color: #000000 !important;">Accessories</a></li>
                              </ul>
                            </li>
                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">For Work<span class="caret"></span></a>
                              <ul class="dropdown-menu" style="color: #0099CC;" role="menu">
                                <li><a href="search.php?type=work&cate=laptop&keyword=" style="color: #000000 !important;">Laptops</a></li>
                                <li class="divider"></li>
                                <li><a href="search.php?type=work&cate=PC&keyword=" style="color: #000000 !important;">PC</a></li>
                                <li class="divider"></li>
                                <li><a href="search.php?type=work&cate=accessory&keyword=" style="color: #000000 !important;">Accessories</a></li>
                              </ul>
                            </li>
                            <?php
                                if ($_SESSION['username']) {
                                    
                                }
                                else {
                            ?>
                            <li><a href="login.php"><i class="fa fa-btn "></i><span class="glyphicon glyphicon-user"></span>&nbsp Sign in</a></li>  
                            <?php
                                }
                            ?>
                        </ul>
                    </div>
                    <form class="navbar-form navbar-right" role="search" action="search.php" method="get">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="keyword">
                                    <input type="hidden" class="form-control" name="cate" value="">
                                    <input type="hidden" class="form-control" name="type" value="">
                                    <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">Search</button>
                                    </span>
                                </div><!-- /input-group -->
                            </div><!-- /.col-lg-6 -->
                    </form>
                    
                </div>
            </div>
        </nav>
        <div class="col-sm-10 col-sm-offset-1" style="min-height: 500px !important;">
            
            <?php
                while ($row=mysqli_fetch_array($result)) {
            ?>   
            <div class="col-sm-3 col-sm-offset-.5" style="margin-top: 3%;">         
                <div class="row result-group" id="search_result" style="margin-top: 2%; margin-left: 4%" >
                    <div class='result' >
                        <div class="row text-center" style="color: #0099CC">
                            <div class="col-sm-8 col-sm-offset-2">
                                <?php
                                echo "<img class='img-thumbnail' style='margin-top:5%; margin-bottom:3%' width='200px' height='200px' src='". $row['imageFileName'] ."'/>";
                                ?>
                            </div>
                            <div class="col-sm-12">
                                <p class="name"><strong><?php echo $row['name'];?></strong></p>
                                <p class="price"><strong><?php echo $row['referenceUnitPrice'];?></strong></p>
                            </div>
                            
                        </div> 
                    </div>
                </div>
            </div>
            
            <?php            
            }
            
            $page_len = ($page_len%2)?$page_len:$pagelen+1;//页码个数
            $pageoffset = ($page_len-1)/2;//页码个数左右偏移量

            $key='<div class="col-sm-offset-5" style=""><nav><ul class="pagination">';
//            $key.="<span>$page/$pages "; //第几页,共几页
            
            $key.="<li><a href=\"".$_SERVER['PHP_SELF']."?type=".$_GET['type']."&cate=".$_GET['cate']."&keyword=".$_GET['keyword']."&page=".($page-1)."\"><span aria-hidden=\"true\">&laquo;</span><span class=\"sr-only\">Previous</span></a></li>"; //上一页

            if($pages>$page_len){
            //如果当前页小于等于左偏移
            if($page<=$pageoffset){
            $init=1;
            $max_p = $page_len;
            }else{//如果当前页大于左偏移
            //如果当前页码右偏移超出最大分页数
            if($page+$pageoffset>=$pages+1){
            $init = $pages-$page_len+1;
            }else{
            //左右偏移都存在时的计算
            $init = $page-$pageoffset;
            $max_p = $page+$pageoffset;
            }
            }
            }
            for($i=$init;$i<=$max_p;$i++){
            if($i==$page){
//            $key.=' <span>'.$i.'';
                $key.=" <li><a href=\"".$_SERVER['PHP_SELF']."?type=".$_GET['type']."&cate=".$_GET['cate']."&keyword=".$_GET['keyword']."&page=".$i."\">".$i."</a></li>";
            } else {
            $key.=" <li><a href=\"".$_SERVER['PHP_SELF']."?type=".$_GET['type']."&cate=".$_GET['cate']."&keyword=".$_GET['keyword']."&page=".$i."\">".$i."</a></li>";
            }
            }
            if($page!=$pages){
            $key.="<li><a href=\"".$_SERVER['PHP_SELF']."?type=".$_GET['type']."&cate=".$_GET['cate']."&keyword=".$_GET['keyword']."&page=".($page+1)."\"><span aria-hidden=\"true\">&raquo;</span><span class=\"sr-only\">Next</span></a></li> ";//下一页
//            $key.="<a href=\"".$_SERVER['PHP_SELF']."?page={$pages}\">Last</a>"; //最后一页
            }else {
             
            $key.="<li><a href=\"#\"><span aria-hidden=\"true\">&raquo;</span><span class=\"sr-only\">Next</span></a></li> ";//下一页
//              $key.="Next ";//下一页
//            $key.="Last"; //最后一页
            }
            $key.='</ul></nav></div>';
            ?>      
        </div>
        <div class="col-sm-10 col-sm-offset-1">
            <?php echo $key?>
        </div>         
    </body>
</html>    