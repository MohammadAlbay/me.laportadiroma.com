<div class="nav-menu">
        <div isolator>
            <div class="top-bar">
                <div class="me-logo">
                    <img src="/sources/imgs/logo.jpeg">
                    <b>ME</b>
                </div>
            </div>

            <div class="menu">
                <ul>
                <li onclick='viewLoader.init("/main");'>
                        <i class="icon home"></i>
                        <b>Home</b>
                    </li>
                    <li>
                        <i class="icon employees"></i>
                        <b>Employees Management</b>
                    </li>
                    <li>
                        <i class="icon category"></i>
                        <b>Category Management</b>
                    </li>
                    <li>
                        <i class="icon assets"></i>
                        <b>Asset Inventory</b>
                        <ul >
                            <li onclick='viewLoader.init("/create-asset-type");'>Create New Type</li>
                            <li onclick="clicked()">Manage Inventory</li>
                            <li>Aquired Assets</li>
                        </ul>
                    </li>
                    <li class="tag" tag="Alpha">
                        <i class="icon cloud-storage"></i>
                        <b>Storage</b>
                    </li>
                    
                    <!-- THis is just a temp menu item-->
                    <li class="tag" tag="New" onclick="location.href = '/auth/logout';">
                        <i class="icon cloud-storage"></i>
                        <b>Logout</b>
                    </li>
                </ul>
            </div>
        </div>
    </div>