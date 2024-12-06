<!DOCTYPE html>
<html lang="en">

<head>
    <script>
        var settings = {};
        settings.initialize = () => {};
        settings.finalize = () => {};
    </script>
</head>

<body>
    <h1 style="margin-left:2em;">Manage & Change Settigns</h1>
    <div class="flex-container" style="margin-top:0em">
        <div class="flex-items w15">
            <h4>Employees</h4>
            <ul>
                <li>Add employee</li>
                <li>Delete employee</li>
                <li>View employees</li>
            </ul>
        </div>
        <div class="flex-items w15">
            <h4>Brands & Departments</h4>
            <ul>
                <li>Add Brand</li>
                <li>Add Department</li>
                <li>View Brands</li>
            </ul>
        </div>
        <div class="flex-items w15">
        <h4>Assets</h4>
            <ul>
                <li>Add Asset</li>
                <li onclick="loadNewAssetTypeView()">Add Asset Type</li>
                <li>View Assets</li>
            </ul>
        </div>
        <div class="flex-items w15">
            <h4>Roles & Permissions</h4>
            <ul>
                <li>Manage Roles</li>
                <li>Manage Permissions</li>
            </ul>
        </div>
    </div>

    <div id="overlay-app-container" class="app-container-overlay half shifted-right">
        <div class="head">
            <b class="title"></b>
            <img class="close-icon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAABlklEQVR4nO2aUU7DMAyGcwi0jQvBPdgrG+/sUuMM8MAxGKj8fyftpSdARi6qqtKmTZdkmj+p2h7syb+c2Ek95wzDmERRFPcA9iS/SJ4SP58SS1mWd6NEkNxlEPzpn+fZOxPq8A3gkeTSJYbkEsBGYpLYvDID4EWMRYTLDABbjW0/aCzrUY0XLjMALHS1HAaN67XoMoW+8ZmQSNAykhm0jAwA4KGqqhs3EfGV30iaEQBrtX+bIkZ8xFf71jqZkKIoJJBX9Xkfc5xp+/o2YJ5rj0wRM1XE2Tf7GDEhIqJULR8xoSKild8+MXOIiNpHusTMJSJ6Q2yWVf38+x7Sc5J09lYWgjORlZA5rs20pTWSro0dcgLIovw298RcYhi7IXZt7DnEMOYRpa86hYphrEOjT4kNEcMYx/gxfWKqGNrF6hquuimgCckMWkYyg9eYkYMaJx+5tTkej7ca24cbQie58uZv4zKD5JP36E0GjY1h6LYsy1WUKPtjWqkIeA9DBRkBt0bClzeerhHVusx+h6MX+YcBwzBczQ8x2MZtl/ABXgAAAABJRU5ErkJggg==" alt="close-window--v1">
        </div>
        <div class="body">

        </div>
    </div>

    <script>
        async function loadNewAssetTypeView() {
            settings.loader.init("/asset-new-type");
        }
    </script>
</body>

</html>