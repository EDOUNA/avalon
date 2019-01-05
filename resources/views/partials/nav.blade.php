<ul class="sidebar-menu" data-widget="tree">
    <li class="treeview" id="account-menu">
        <a href="#">
            <i class="fa fa-tachometer fa-fw"></i>
            <span>Meter</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <!--
            <li class="">
                <a href="#">
                    <i class="fa fa-dashcube fa-fw"></i>
                    Live UI
                </a>
            </li>!-->
            <li class="">
                <a href="{{ url('meter/static') }}">
                    <i class="fa fa-mortar-board fa-fw"></i>
                    Static board
                </a>
            </li>
            <li class="">
                <a href="{{ url('meter/budget') }}">
                    <i class="fa fa-money fa-fw"></i>
                    Budget
                </a>
            </li>
        </ul>
    </li>
</ul>
