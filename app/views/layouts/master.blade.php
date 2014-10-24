<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{$website->complete_title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{$website->description}}">
    <meta name="author" content="Sarunas">
    <link href="/css/bootstrap.min.css" media="all" rel="stylesheet">
    <link href="/css/bootstrap-responsive.min.css"
          rel="stylesheet">
    <link href="/css/responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>

<nav class="navbar navbar-fixed-top"> <!-- TOP NAVIGATION BARR-->
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="{{URL::to('home')}}">{{$website->slogan}}</a>

            <div class="nav-collapse">
                <ul class="nav">
                    <li {{Request::is('home*') ? 'class="active"': ""}}>
                    <a href="{{URL::to('home')}}"><i class="icon-home icon-white"></i> Home</a></li>
                    <li {{Request::is('projects*') ? 'class="active"': ""}}>
                    <a href="{{URL::to('projects')}}"><i class="icon-tasks icon-white"></i> Projects</a></li>
                    <li {{Request::is('users*') ? 'class="active"': ""}}>
                    <a href="{{URL::to('users')}}"><i class="icon-user icon-white"></i> Users</a></li>
                </ul>

                <ul class="nav pull-right"> <!-- RIGHT menu side-->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i>
                            {{Auth::user()->first_name}} <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{URL::to('settings')}}"><i class="icon-cog"></i> Settings</a></li>
                            @if(Auth::user()->isRole('Administrator'))
							<li class="divider"></li>
                            <li><a href="{{URL::to('admin')}}"><i class="icon-wrench"></i> Admin</a></li>
							@endif
                        </ul>
                    </li>
                    <li class="tooltips">
                        <a href="{{URL::to('logout')}}" class="visible-desktop" data-rel="tooltip" data-placement="bottom" title="Logout">
                            <i class="icon-off icon-white"></i></a>
                        <a href="{{URL::to('logout')}}" class="hidden-desktop"><i class="icon-off icon-white"></i> Logout</a>
                    </li>
                </ul>
                <!--  / RIGHT meniu side-->
            </div>
        </div>
    </div>
</nav>
<!-- / TOP NAVIGATION BAR-->
<div class="container">

	<!--Search - hidden in phone mode-->
<!--    <div id="search" class="pull-right hidden-phone">
        <form class="navbar-search" action="">
            <input type="text" class="search-query span2" placeholder="Search">
        </form>
    </div>-->

	<!--Breadcrumbs-->
	<ul class="breadcrumb">
		<?php
		//Generating breadcrumbs
		$count = 0;
        $segment_url = '';
        $num_count = count(Request::segments());
		foreach(Request::segments() as $segment){
            if(++$count === $num_count) {
                echo '<li class="active">'.$website->title.'<span class="divider"></span></li>';
        
            }else {
                $segment_url .= '/'.$segment;
                if (is_numeric ($segment) and  strcmp(Request::segment($count - 1), 'projects') == 0)
				{
    			    if (!isset($project->title)){
                        $project = Project::find($segment);
                    }
                    echo '<li><a href="'.URL::to($segment_url).'" title="'.$project->title.'">'.$project->title.' </a><span class="divider">»</span></li>';

                }
				//tasks
				elseif (is_numeric ($segment) and  strcmp(Request::segment($count - 1), 'tasks') == 0)
				{
					$task = Task::find($segment);
					echo '<li><a href="'.URL::to($segment_url).'" title="'.$task->title.'">'.$task->title.' </a><span class="divider">»</span></li>';
				}
				//milestones
				elseif (is_numeric ($segment) and  strcmp(Request::segment($count - 1), 'milestones') == 0)
				{
					$milestone = Milestone::find($segment);
					echo '<li><a href="'.URL::to($segment_url).'" title="'.$milestone->title.'">'.$milestone->title.' </a><span class="divider">»</span></li>';
				}
				//files
				elseif (is_numeric ($segment) and  strcmp(Request::segment($count - 1), 'files') == 0)
				{
					$file = FileDB::find($segment);
					echo '<li><a href="'.URL::to($segment_url).'" title="'.$file->title.'">'.$file->title.' </a><span class="divider">»</span></li>';
				}
				//pages
				elseif (is_numeric ($segment) and  strcmp(Request::segment($count - 1), 'pages') == 0)
				{
					$page = Page::find($segment);
					echo '<li><a href="'.URL::to($segment_url).'" title="'.$page->title.'">'.$page->title.' </a><span class="divider">»</span></li>';
				}
				else
				{
                    echo '<li><a href="'.URL::to($segment_url).'" title="'.ucfirst($segment).'">'.ucfirst($segment).' </a><span class="divider">»</span></li>'; 
                }

            }
		}
		?>
    </ul>
    <!-- / Breadcrumbs-->

    <?php $project_id = Request::segment(2); ?>

    @if (Request::is('projects/*') AND is_numeric($project_id))
    <!-- Project menus-->
    <nav>
        <nav id="project-menu" class="navbar hidden-phone">
            <div class="navbar-inner">
                <div class="container">
                    <ul class="nav">
                        <li {{!Request::is('projects/*/*') ? 'class="active"': ""}}>
                        <a href="{{URL::to('projects/'.$project_id)}}">Dashboard</a></li>
                        <li {{Request::is('projects/*/milestones*') ? 'class="active"': ""}}>
                        <a href="{{URL::to('projects/'.$project_id.'/milestones')}}">Milestones</a></li>
                        <li {{Request::is('projects/*/tasks*') ? 'class="active"': ""}}>
                        <a href="{{URL::to('projects/'.$project_id.'/tasks')}}">Tasks</a></li>
                        <li {{Request::is('projects/*/discussions*') ? 'class="active"': ""}}>
                        <a href="{{URL::to('projects/'.$project_id.'/discussions')}}">Discussions</a></li>
                        <li {{Request::is('projects/*/files*') ? 'class="active"': ""}}>
                        <a href="{{URL::to('projects/'.$project_id.'/files')}}">Files</a></li>
                        <li {{Request::is('projects/*/pages*') ? 'class="active"': ""}}>
                        <a href="{{URL::to('projects/'.$project_id.'/pages')}}">Pages</a></li>
                        <li {{Request::is('projects/*/members*') ? 'class="active"': ""}}>
                        <a href="{{URL::to('projects/'.$project_id.'/members')}}">Members</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- This menu is visible to phone-->
        <div class="visible-phone">
            <ul class="nav nav-tabs nav-stacked">
                <li {{!Request::is('projects/*/*') ? 'class="active"': ""}}>
                <a href="{{URL::to('projects/'.$project_id)}}">Dashboard</a></li>
                <li {{Request::is('projects/*/milestones*') ? 'class="active"': ""}}>
                <a href="{{URL::to('projects/'.$project_id.'/milestones')}}">Milestones</a></li>
                <li {{Request::is('projects/*/tasks*') ? 'class="active"': ""}}>
                <a href="{{URL::to('projects/'.$project_id.'/tasks')}}">Tasks</a></li>
                <li {{Request::is('projects/*/discussions*') ? 'class="active"': ""}}>
                <a href="{{URL::to('projects/'.$project_id.'/discussions')}}">Discussions</a></li>
                <li {{Request::is('projects/*/files*') ? 'class="active"': ""}}>
                <a href="{{URL::to('projects/'.$project_id.'/files')}}">Files</a></li>
                <li {{Request::is('projects/*/pages*') ? 'class="active"': ""}}>
                <a href="{{URL::to('projects/'.$project_id.'/pages')}}">Pages</a></li>
                <li {{Request::is('projects/*/members*') ? 'class="active"': ""}}>
                <a href="{{URL::to('projects/'.$project_id.'/members')}}">Members</a></li>
            </ul>
        </div>
    </nav>
    <!--/Project menus-->
    @endif

    <!-- /Main Content-->
    <div id="content">
    @yield('content')
    </div>
    <!-- /Main Content-->

    <hr>
    <footer>
        <p>
			{{$website->footer}}
            <span class="to-top pull-right hidden-phone"><i class="icon-arrow-up"></i> Top</span>
        </p>
    </footer>

</div><!-- Container-->

<!-- To top icon for phones-->
<div id="to-top"><i class="icon-arrow-up icon-white"></i> Top</div>


<!-- JAVASCRIPT
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="/js/jquery-1.9.1.min.js"></script>
<script src="/js/bootstrap.js"></script>
<script src="/js/jquery-ui-1.10.2.custom.min.js"></script>
<script src="/js/application.js"></script>
</body>
</html>