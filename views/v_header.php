<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'pddk';
?>
&nbsp;
<div class="pddk">
    <div class="container-fluid">
        <div class="ltbody">
            <nav class="navbar navbar-inverse" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> 
                            <span class="sr-only">Toggle navigation</span> 
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span> 
                            <span class="icon-bar"></span> 
                        </button>
                        <a class="navbar-brand" href="#"><i class="fa fa-users"></i> <strong>PENDUDUK</strong></a> 
                    </div>
                    <div class="collapse navbar-collapse" id="wpmenu">
                        <ul class="nav navbar-nav" style="height: 50px;">
                            <li <?php echo $page == 'pddk' ? 'class="active"' : ''; ?>><a href="?page=pddk"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                            <li <?php echo $page == 'pddk-residents' ? 'class="active"' : ''; ?>><a href="?page=pddk-residents"><i class="fa fa-user"></i> Residents</a></li>
                            <li <?php echo $page == 'pddk-houses' ? 'class="active"' : ''; ?>><a href="?page=pddk-houses"><i class="fa fa-home"></i> Houses</a></li>
                            <li <?php echo $page == 'pddk-settings' ? 'class="active"' : ''; ?>><a href="?page=pddk-settings"><i class="fa fa-gear"></i> Settings</a></li>
                            <li <?php echo $page == 'pddk-helps' ? 'class="active"' : ''; ?>><a href="?page=pddk-helps"><i class="fa fa-heart"></i> Help</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <?php $this->top_message(); ?>