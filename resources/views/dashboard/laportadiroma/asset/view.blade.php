<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assets</title>
    <script>
    var asset_view = {loader: null};
    asset_view.initialize = (prevViewName) => {}
    asset_view.finalize = () => {
        ViewloaderInstances[0].sendParams(asset_view.loader,{value: "Hi. I'm a view chillin' down there!"}); // send a parameter to the parent view
    }
    asset_view.paramsPassed = (from_view, params) => {
        consolelog(params);
    }
    </script>
</head>
<body>
    <div class="grid-container no-second-column">
        <div>
            <h1 class="head-title">View Assets</h1>
            <hr>
            <nav class="head-actions">
                <button class="btn-text" onclick='viewLoader.init("/asset-add-new");'> Add new</button>
                <button class="btn-text" onclick='viewLoader.init("/main");'>Back to Main</button>
            </nav>

            <table>
                <thead>
                    <th>#</th>
                    <th>Asset Name</th>
                    <th>Owner(s)</th>
                    <th>Available</th>
                    <th>Type</th>
                    <th>Created by</th>
                    <th>Created at</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach($assets as $asset)
                    <tr>
                        <td>{{$asset->id}}</td>
                        <td clickable  blue-on-hover>{{$asset->name}}</td>
                        <td>
                            <span clickable class="tag sales">Mohammad Albay</span>
                            <span clickable class="tag sales">Omar Dally</span>
                            <span clickable class="tag sales">Omar Dally</span>
                            <span clickable class="tag sales">khalid salem</span>
                        </td>
                        <td>{{$asset->state}}</td>
                        <td clickable>
                            <span class="tag it">
                                {{$asset->assetType->name}}
                            </span>
                        </td>
                        <td clickable>
                            <span class="tag marketing">
                                {{$asset->createdBy->firstname." ".$asset->createdBy->lastname}}
                            </span>
                        </td>
                        <td>{{$asset->created_at}}</td>
                        <td>
                            <span class="tag it" clickable>
                                Assign
                            </span>
                        </td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
        <div></div>
    </div>
    

    <dialog id="d1" class="dialog-open">
        <h1 style="margin-top:0em; padding-top:0.4em;">title</h1>
        <hr>
    
        <div style="width:100%;">
            <div relative w95>
                <h1>HELLO</h1>
            </div>
        </div>
        <nav>
            <button secondary success dialog-id="d1">Save</button>
            <button danger close-dialog dialog-id="d1">Cancel</button>
        </nav>
    </dialog>
    <div class="dialog-overlay" for="d1" allow-ignore></div>
</body>
</html>