<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Asset</title>
    <style>
        .mainColumn {
            min-width:500px;
        }
        .secondColumn {
            min-width: 250px;
        }

        @media only screen and (min-width:1024px) {
            .mainColumn {min-width:550px;    max-width: 65vw;}
        }


        
    </style>
    <script>
        var asset_add_new = {
            loader: null,
            types: @json($asset_types),
            employees: [],
            async reloadTypes() {
                this.types = (await fetcher.getJSON("/laportadiroma/get-asset-types")).result.Message;
                renderLoadedAssetTypes();
            },
            async getEmployees() {
                if(this.employees.length !== 0)
                    return this.employees;

                const temp = await fetcher.getJSON("/auth/get-employees");
                if(!temp.error) {
                    return;
                }


                this.employees = temp.result;
                console.log(this.employees);
            }
        };
        asset_add_new.paramsPassed = async (fromView, params) => {
            //debugger;
            try {
                if(fromView === "asset_new_type") {
                if(asset_add_new.types.length !== params)
                    await asset_add_new.reloadTypes();
                asset_add_new.initialize();
            }
            console.log("Data passed from", fromView, "with params", params);
            } catch (error) {
                console.error(error);
                
            }
            
        };
        asset_add_new.initialize = (prevViewName) => {
            try {
                asset_add_new.types = asset_add_new.types.map(t => {
                    if(typeof t.fields === "string")
                        t.fields = JSON.parse(t.fields); 
                return t;
            });
            } catch (error) {
                console.error(error);
            }
            
        };
        asset_add_new.finalize = () => {
            
        };
    </script>
</head>
<body>
    @if($asset_types->count() == 0)
    <script>
        let x= 10;
        Swal.fire({
            title: "Oops! You have no asset types defined yet!",
            text: "You can define asset types from Settings > Create asset type",
            showConfirmButton: true
        }).then(() => {
            viewLoader.init("/main");
            displaySettingsView();

        });
        

    </script>
    @else
    <form id="F00" action="/asset/create" method="post" enctype="multipart/form-data"></form>
    <div class="grid-container">
        <div class="mainColumn">
            <h1 class="head-title">Add Asset</h1>
            <hr>
            <nav class="head-actions">
                <button id="add-new-asset_save-button" class="color-green btn-text" disabled>Save</button>
                <button id="add-new-asset_clear-button" class="color-red btn-text">Cancel</button>
            </nav>

            <div margin-free w100>
                <div class="input-form-v1" id="asset_name">
                  <label for="asset_name_input">Asset name</label>
                  <input form="F00" type="text" id="asset_name_input" name="asset_name_input">
                </div>
                    <div class="input-form-v1" id="asset_type">
                        <label for="asset_type_input">Asset type</label>
                        <select class="input" form="F00" id="asset_type_input" name="asset_type_input" onchange="assetTypeChanged(this)">
                            <option selected disabled>Choose the Asset type</option>
                            @foreach($asset_types as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-form-v1" id="asset_quantity" hidden>
                        <label for="asset_quantity_input">Quantity</label>
                    <input form="F00" type="number" min="0" max="100000" id="asset_quantity_input" name="asset_quantity_input" on>
                </div>
                <div id="fields" title="Asset fields" class="labeled-div"></div>
            </div>
        </div>
        <div class="secondColumn">
            @include("desc.asset_add_new")
        </div>
    </div>
    

    <script>
        /* clear button click event */
        document.find("#add-new-asset_clear-button").addEventListener("click", e => {
            document.find("#fields").replaceChildren();
            document.forms["F00"].reset();
            document.find("#add-new-asset_save-button").setAttribute("disabled", "");
        });
        /* submit button event */
        document.find("#add-new-asset_save-button").addEventListener("click", async e => {
            const formData = new FormData(document.forms["F00"]);
            let fields = [];
            document.querySelectorAll("[asset-field-name]").forEach(input => {
                fields.push({name: input.getAttribute("asset-field-name"), type: input.type, input: input.name});
            });
            formData.append("fields", JSON.stringify(fields));
            const res = await fetcher.sendFormData("/laportadiroma/add-new-asset", {
                type: "POST", data: formData, wantJSON: true
            });

            if (res.error) {
                    errorAlert("Failed to add new asset", res.error);
                } else {
                    const result = res.result;
                    if(result.State != 0) {
                        errorAlert("Failed to add new asset", result.Message);
                    } else {
                        successAlert("Asset added successfully", `Asset ${result.Message.name} added successfully!`);
                    }
                }

        });

        async function assetTypeChanged(self) {
            const value = self.selectedOptions[0]?.value ?? null;

            if(!value) return;

            document.find("#fields").replaceChildren();

            const assetType = asset_add_new.types.filter(type => type.id == value)[0];

            if(assetType.unique == 1) {
                document.find("#asset_quantity").setAttribute("hidden", "");
            } else {
                document.find("#asset_quantity").removeAttribute("hidden");
            }

            const fieldsContainer = document.find("#fields");
            await addFieldUI(fieldsContainer, assetType);
            document.find("#add-new-asset_save-button").removeAttribute("disabled");
        }

        async function addFieldUI(parent, fieldInfo) {
            fieldInfo.fields.forEach(async field => {
                let fieldType = field.type;

                let inputElement = "<input>"; 
                if(fieldType == "string")  {
                    inputElement = `<input asset-field-name="${field.name}" form="F00" type="text" ${field.required ? "required" : ""} ${field.default !== "" ? `value='${field.default}'` : ""} minlength="${field.minlength}" maxlength="${field.maxlength}" id="field_${field.name}_input" name="field_${field.name}_input">`;
                } else if(fieldType == "double" || fieldType == "number") {
                    inputElement = `<input asset-field-name="${field.name}" form="F00" type="number" ${field.required ? "required" : ""} ${field.default !== "" ? `value='${field.default}'` : ""} min="${field.minvalue}" max="${field.maxvalue}" id="field_${field.name}_input" name="field_${field.name}_input">`;
                } else if(fieldType == "list-users") {
                    let usersOptions = "";
                    let users = await fetcher.getJSON("/auth/get-employees");
                    if(users.error != null)  {
                        document.find("#add-new-asset_clear-button").click();
                        errorCard("Failed to load users list");
                        return;
                    }

                    users.result.Message.forEach(user => {
                        usersOptions += `<option value="${user.id}">${user.firstname} ${user.lastname}</option>`;
                    })
                    inputElement = `<select asset-field-name="${field.name}" class="input" form="F00" ${field.required ? "required" : ""} ${field.multiselect ? "multiselect" : ""}><option disabled selected>Choose value for ${field.name}</option>${usersOptions}</select>`;
                } else if(fieldType == "list-values") {
                    let options = "";
                    field.values.forEach(value => {
                        options += `<option value="${value}"  ${field.default === value ? "selected" : ""}>${value}</option>`;
                    })
                    inputElement = `<select asset-field-name="${field.name}" class="input" form="F00" ${field.required ? "required" : ""} ${field.multiselect ? "multiselect" : ""}><option disabled selected>Choose value for ${field.name}</option>${options}</select>`;
                } else if(fieldType == "boolean") {
                    let options = "";
                    [{value: 1, text: "Yes"}, {value: 0, text: "No"}].forEach(op => {
                        options += `<option value="${op.value}"  ${field.default === op.value ? "selected" : ""}>${op.text}</option>`;
                    })
                    inputElement = `<select asset-field-name="${field.name}" class="input" form="F00" ${field.required ? "required" : ""}><option disabled selected>Choose value for ${field.name}</option>${options}</select>`;
                } else if(fieldType == "file" || fieldType == "image" || fieldType == "video") {
                    inputElement = `<input asset-field-name="${field.name}" form="F00" type="file" ${field.required ? "required" : ""} ${fieldType == "image" ? 'accept="image/*"' : (fieldType == "video" ? 'accept="video/*"' : "")} ${field.multiselect !== "" ? `value='${field.default}'` : ""} ${field.multiselect ? "multiple" : ""} id="field_${field.name}_input[]" name="field_${field.name}_input[]">`;
                }
                const text = `
                <div class="input-form-v1" id="field_${field.name}" id="field_${field.name}">
                    <label for="asset_quantity_input">${field.name}</label>
                    ${inputElement}
                </div>`;
    
                parent.insertAdjacentHTML("beforeend", text);
                //fieldsContainer.insertAdjacentHTML("beforeend", text);
            });
        }


        function renderLoadedAssetTypes() {
            const selectField = document.find("#asset_type_input");
            selectField.replaceChildren();
            asset_add_new.types.forEach(type => {
                elements.addElement(selectField, "option", {
                    value: type.id,
                    text: type.name
                });
            });
        }
    </script>
    @endif
</body>
</html>