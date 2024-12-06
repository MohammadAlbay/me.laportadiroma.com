<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1 style="margin-left:2em">Add new asset type</h1>

    <div style="width:50%;margin:0 auto; margin-top:2em">
        <b style="text-align:left;margin-left: 10px;">Insert the name of new Asset type</b>
        <div class="input-form-v1">
                    <label for="asset_name">Name</label>
                    <input type="text" id="asset_name" name="asset_name">
        </div>
        <div style="position: relative;">
        <b style="text-align:left;margin-left: 10px;">Add all the fields for this new asset type</b>
        <img open-dialog dialog-id="d1" style="cursor:pointer;width: 30px; height:30px; position:absolute; top:0px; right:0px;" src="data:image/png;base64,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" alt="add">
        
        </div>
        <div id="fields_container" style="margin-top:1em; padding:0.5em; width:100%; max-height:500px; overflow-y:auto; overflow-x:hidden; border-radius:1em; border:1px solid black;">

        </div>

    </div>

    <form action="/" method="post" id="f1"></form>
    <dialog id="d1">
        <h1 style="margin-top:0em; padding-top:0.4em;">Add Field</h1>
        <hr>

        <div style="width:100%;">
            <div relative w95>
                <div class="input-form-v1">
                    <label for="field_name_value__form">Name</label>
                    <input type="text" id="field_name_value__form" name="field_name_value__form" form="f1">
                </div>
                <div class="input-form-v1">
                    <label for="type_value__from">Type</label>
                    <select class="input" form="f1" id="type_value__from" onchange="fieldTypeChanged(this)">
                        <option value="invalid" selected disabled>Choose from options</option>
                        <option value="string">Text</option>
                        <option value="number">Number</option>
                        <option value="double">Decimal</option>
                        <option value="boolean">Yes/No</option>
                        <option value="list-users">Choose an employee</option>
                        <option value="list-values">Choose from specified values</option>
                    </select>
                </div>
                <div class="input-form-v1" id="required_value_div">
                    <label for="required_value__from">Required</label>
                    <select class="input" form="f1" id="required_value__from" name="required_value__from" onchange="requiredValueCharnged(this)">
                        <option value="invalid" selected disabled>Choose from options</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="input-form-v1" id="target_values_div" hidden>
                    <label for="target_values__form">Targeted Values</label>
                    <input form="f1" type="text" id="target_values__form" name="target_values__form" placeholder="Type the values with | between each value">
                </div>
                <div class="input-form-v1" id="default_value_div" hidden>
                    <label for="default_value__form">Default Value</label>
                    <input form="f1" type="text" id="default_value__form" name="default_value__form" placeholder="type in the default value. ">
                </div>
<!-- Field to set the max length if type is text -->
                <div class="input-form-v1" id="max_length_value_div" hidden>
                    <label for="max_length__form">Max length</label>
                    <input type="number" id="max_length__form" name="max_length__form" step="1" minlength="1"  placeholder="at least 1">
                </div>
<!-- Field to set the min length if type is text -->
                <div class="input-form-v1" id="min_length_value_div" hidden>
                    <label for="min_length__form">Min length</label>
                    <input form="f1" type="number" id="min_length__form" name="min_length__form" step="1" minlength="1"  placeholder="at least 1">
                </div>

<!-- Field to set the max numerical value if type is text -->
                <div class="input-form-v1" id="max_value_value_div" hidden>
                    <label for="max_value__form">Max value</label>
                    <input form="f1" type="number" id="max_value__form" name="max_value__form" step="1" min="1"  placeholder="at least 1">
                </div>
<!-- Field to set the min length if type is text -->
                <div class="input-form-v1" id="min_value_value_div" hidden>
                    <label for="min_value__form">Min value</label>
                    <input form="f1" type="number" id="min_value__form" name="min_value__form" step="1" min="1"  placeholder="at least 1">
                </div>
<!-- Field to choose if multi select is allowed -->
                <div class="input-form-v1" id="multi_select_div" hidden>
                    <label for="multiselect_value__form">Multi select</label>
                    <select class="input" form="f1" id="multiselect_value__form" name="multiselect_value__form" onchange="fielMultiChooseChanged(this)">
                        <option value="invalid" selected disabled>Choose from options</option>
                        <option value="1">Allowed</option>
                        <option value="0">Not allowed</option>
                    </select>
                </div>
<!-- Field to choose boolean values -->
                <div class="input-form-v1" id="boolean_default_value_div" hidden>
                    <label for="boolean_default_value__form">Default value</label>
                    <select class="input" form="f1" id="boolean_default_value__form" name="boolean_default_value__form">
                        <option value="invalid" selected disabled>Choose from options</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>
        </div>
        <nav>
            <button secondary dialog-id="d1">Save</button>
            <button danger close-dialog dialog-id="d1">Cancel</button>
        </nav>
    </dialog>
    <div class="dialog-overlay" for="d1" allow-ignore></div>

    <script src="/js/asset/add_type.js"></script>

    <script>

        function addNewField() {

        }
        //let dialog = document.getElementById("d1");
        //dialog.classList.add("dialog-open");
        //dialog.showModal();
        function fielMultiChooseChanged(self = HTMLSelectElement) {
            const value = self.selectedOptions[0].value;
            const typeValue = type_value__from.selectedOptions[0].value;

            if(typeValue == "list-users") return;
            if(value == "1") {
                default_value_div.setAttribute("hidden", "");
            } else default_value_div.removeAttribute("hidden");
        }

        function fieldTypeChanged(self = HTMLSelectElement) {
            const value = self.selectedOptions[0].value;

            if(value == "list-users") {
                 /* hide some if the fields, and show some */
                [multi_select_div, required_value_div].forEach(e => e.removeAttribute("hidden"));
                 required_value__from.options.item(1).selected = true;
                 required_value__from.style = "pointer-events: none;"

                let t = [target_values_div,default_value_div, min_length_value_div, 
                 max_length_value_div,boolean_default_value_div];
                
                t.forEach(e => e.setAttribute("hidden", ""));

            } else if(value == "list-values") {
                    required_value__from.readonly = true;
                    required_value__from.options.item(1).selected = true;
                    required_value__from.style = "pointer-events: none;"

                    let t = [default_value_div, multi_select_div, multi_select_div, 
                    required_value_div, target_values_div];
                    t.forEach(e => e.removeAttribute("hidden"));

                    [min_length_value_div, max_length_value_div,boolean_default_value_div]
                    .forEach(e => e.setAttribute("hidden", ""));
            }
            else if(value == "string") {
                required_value__from.style = "pointer-events: all;";
                [min_length_value_div, max_length_value_div, 
                required_value_div, default_value_div, ]
                    .forEach(e => e.removeAttribute("hidden"));

                    [max_value_value_div, min_value_value_div, multi_select_div, 
                    target_values_div,boolean_default_value_div]
                    .forEach(e => e.setAttribute("hidden", ""));
            }
            else if(value == "number" || value == "double") {
                required_value__from.style = "pointer-events: all;";
                [required_value_div, default_value_div,  
                max_value_value_div, min_value_value_div]
                    .forEach(e => e.removeAttribute("hidden"));

                    [target_values_div,min_length_value_div, 
                    max_length_value_div, multi_select_div,boolean_default_value_div]
                    .forEach(e => e.setAttribute("hidden", ""));
            } 
            else if(value == "boolean") {
                required_value__from.style = "pointer-events: all;";
                [boolean_default_value_div, required_value_div]
                .forEach(e => e.removeAttribute("hidden"));

                [min_length_value_div, max_length_value_div, 
                multi_select_div,target_values_div, max_value_value_div,
                min_value_value_div, default_value_div]
                    .forEach(e => e.setAttribute("hidden", ""));
            }
        }

        
        function requiredValueCharnged(self) {
            const value = self.selectedOptions[0].value;
            const typeValue = type_value__from.selectedOptions[0].value;
            
            if(typeValue == "invalid") return;

            if(value == "1") {
                if(typeValue == "boolean")
                    boolean_default_value_div.setAttribute("hidden", "");
                else
                    default_value_div.setAttribute("hidden", "");
            } else {
                if(typeValue == "boolean")
                    boolean_default_value_div.removeAttribute("hidden");
                else 
                    default_value_div.removeAttribute("hidden");
            }
        }
    </script>
</body>

</html>