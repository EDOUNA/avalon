<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">
                    <span data-feather="home"></span>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('bank/transactions') }}">
                    <span data-feather="file"></span>
                    Banktransacties
                </a>
                <ul class="nax flex-column">
                    <li class="nax-item">
                        <a class="nav-link" href="{{ url('bank/categories') }}">Categorieën</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('meter') }}">
                    <span data-feather="meter"></span>
                    Slimme meter
                </a>
            </li>
        </ul>
    </div>
</nav>
