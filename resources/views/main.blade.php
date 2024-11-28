<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <link rel="stylesheet" href="/css/employees/employees_add.css">
    <script src="/js/employees/employees_add.js"></script>
    <script>
        var a = 10;
        var main = {};
        main.finalize = () => {
            ViewLoader.drop('a', 'asx');
            // a = null;
            // asx = null;
            // delete window[a];
            // delete window[asx];
            // delete a;
            // delete as;
        }

        main.initialize = () => {
            console.log("View did initialized");
        }
    </script>
</head>

<body>
    <h1>Main view</h1>
    <script src="https://cdn.jsdelivr.net/npm/google-charts@2.0.0/dist/googleCharts.min.js"></script>
</body>

</html>