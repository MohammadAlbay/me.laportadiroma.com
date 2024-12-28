/**
 * FieldCollection is a class used for adding an extra field for the specified form.
 * It uses the document.elements object to generate and manipulate new HTML content
 */
class FieldCollection {
    constructor() {
        this.fields = [];
        this.container = null;
    }

    reset() {
        this.fields.length = 0;
        this.container?.replaceChildren();
    }

    setContainer(id) {
        this.container = document.getElementById(id);
    }
    getContainer() { return this.container; }

    hideAddDialog(dialogId = "add-field") {
        let dialog = document.getElementById(dialogId);
        dialog.classList.toggle("dialog-open", false);
        dialog.classList.toggle("dialog-close", true);
        dialog.close();
    }
    showAddDialog(dialogId = "add-field") {
        let dialog = document.getElementById(dialogId);
        dialog.classList.toggle("dialog-open", true);
        dialog.classList.toggle("dialog-close", false);
        dialog.showModal();

        const successButton = dialog.querySelector(`button[dialog-id='${dialogId}'][success]`);
        // const closeButton = dialog.querySelector(`button[dialog-id='${dialogId}'][dialog-close]`);

        if (successButton) {
            //successButton.removeEventListener('click', this.handleSuccessClick);
            successButton.onclick = () => this.handleSuccessClick(dialogId, null);
        }

        // if (closeButton) {
        //     closeButton.removeEventListener('click', this.handleCloseClick);
        //     closeButton.addEventListener('click', this.handleCloseClick.bind(this, dialogId));
        // }
    }

    // Handler function for success button
    handleSuccessClick(dialogId, e) {
        this.processFieldData(dialogId);
    }

    // Handler function for close button
    handleCloseClick(dialogId, e) {
        this.hideAddDialog(dialogId);
    }
    /*
        collect all inputs from form, calls processFieldData2 to
        display DIV. then, clear form and close dialog
    */
    async processFieldData(dialogId = "add-field") {

        let form1 = document.forms["f1"];
        const object = {
            field_name: form1.elements['field_name_value__form'].value,
            field_type: form1.elements['type_value__from'].value,
            field_required: form1.elements['required_value__from'].value,
            field_targetvalues: form1.elements['target_values__form'].value,
            field_default: form1.elements['default_value__form'].value,
            field_maxlength: form1.elements['max_length__form'].value,
            field_minlength: form1.elements['min_length__form'].value,
            field_maxvalue: form1.elements['max_value__form'].value,
            field_minvalue: form1.elements['min_value__form'].value,
            field_multiselect: form1.elements['multiselect_value__form'].value,
            field_booleandefault: form1.elements['boolean_default_value__form'].value,
        }

        await this.processFieldData2(object);
        let tempname = form1.elements["asset_name"].value;
        form1.reset();
        form1.elements["asset_name"].value = tempname;
        this.hideAddDialog(dialogId);
    }
    /* save the new field. then, add html content to UI */
    async processFieldData2(object = {}) {
        const index = this.fields.push(object) - 1;


        let content = `<div index="${index}" style="position:relative; height: 4em; width:100%; border:1px solid gray; border-radius:0.5em;margin-top:0.4em; background-color:rgb(250,250,250)">
                <img style="position: absolute; left:1em; top:50%; transform: translateY(-50%); width:30px; height:30px;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAAACXBIWXMAAAsTAAALEwEAmpwYAAACGUlEQVR4nO1bQUsCQRT25J+YLhFB/QmhwzQrru7K7FJn6xxBoUdvaZC/oc4d+gFCxw6ZYJAeOgRRGgrJBmFKBC92xUXKQpF1Ruc9+GB5y+6++XjvzTcPNhRCQ5vIbNsOmxo/NjTrxdQskAu8YTIr78YYCspMZuXFL/R/GIzngiNA4w33I+xsDWiRSAXtdN3PhAAJsLyPiF7sXxjEhwQEZSZmgIUlYGIPsIQ3O2mbYKa8Da1uHdq9FmRvd9Qj4LnzAAN7+2zDZnHJ89ecGxjXqk7Jf9+o54bvzw0BVac0NgF3zrX/vlHPDd+XjoB0eQuaH0/w2mtCtpJSrwSo6k2QIgEEM4CqXAIZ1AEPU+uASfb9hdQBk+z70hGQRh1A1G6CFAkgmAEUS4BgD6DYBAnuAlTVbTBbSQkZhkhBwO7VBnS/Oh7c64FfiZlg/HIVHt/v/UDda9enzEywUDv8FexJ7UCtEqCoBIlQEjADNCwBEN4DsqgDOlPpgEn2/YXUAXM7EyygDiCoAyjqAIJCiKISJCiFKZ4FCB6GKJ4GCR6HKc4DyOIPRBIpBvEUk8InhABTQl/wBFwsA9uPQDSp+x+N6oaPmfqSOmh7EaDnK7MjIGrF/EASUe5hODghPq7PkADdkBIzIIB7v83FYqbwxf6EG5NHAOP1wAgwGM8NWJYVhsaPAiPAtu1wn4R+JkgFxuvu4gP9dRYttFj2DSIAhLiBhGBZAAAAAElFTkSuQmCC" alt="sidebar-menu">
                <b style="position:absolute; top:0.7em; left:3.27em; font-size:13.5pt;">${object.field_name}</b>
                <b style="position:absolute; top:2.6em; left:4em; font-size:11pt; font-weight: lighter; color:white; background-color:rgb(220,220,220); border-radius:2em; padding:0.1em 0.3em;">${object.field_type}</b>
                <img onclick="fieldCollection.removeField(this, ${index});" style="cursor:pointer;position:absolute; top:50%; transform:translateY(-50%); right:1em; width:35px; height: 35px;" src="data:image/png;base64,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" alt="cancel--v3">
                </div>`;

        this.container.insertAdjacentHTML("beforeend", content);
    }

    async submit() {
        const f1 = document.forms["f1"];
        const details = {
            asset_name: f1.elements["asset_name"].value,
            asset_section: f1.elements["asset_section"].value,
            asset_is_unique: f1.elements["asset_is_unique"].value,
            asset_multiowner: f1.elements["asset_multiowner"].value,
            fields: this.fields
        }
        if(details.asset_name === "") {
            errorAlert("Fill out required fields!","Asset type name required");
            return;
        }

        let response = await fetcher.sendFormData("/laportadiroma/create-new-asset", { type: "POST", data: { details }, wantJSON: true })
        if (response.error != null) {
            Swal.fire({
                icon: 'error',
                title: 'Error occured while processing request',
                text: response.error
            });
        } else {
            response = response.result;
            if (response.State != 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong!',
                    text: response.Message
                });
            }
            else {
                this.fields.length = 0;
                successAlert("New Asset", `Asset ${details.asset_name} created successfully`);
                return [0, response.Message];
            }
        }
        return [1, null];
    }

    /**
     *
     * @param {object} options an object for the input field to be created
     */
    addField(options = { name: null, required: false, type: "string", limits: { minlength: 0, maxlength: 100 }, className: null }) {
        if (this.haveSample && this.sample != null) {
            this.form.insertAdjacentElement("beforeend", element);
        } else {
            let inputType;
            switch (options.type) {
                case "string": inputType = "text"; break;
                case "number": inputType = "number"; break;
                case "phone": inputType = "tel"; break;
                case "date": inputType = "date"; break;
                case "time": inputType = "time"; break;
                case "email": inputType = "email"; break;
                default: inputType = "text";
            }
            const inputElement = elements.createChild("INPUT", {
                type: inputType,
                required: options.required ?? false,
                minlength: limits?.minlength ?? 0,
                maxlength: limits?.maxlength ?? 100000000,
            });

            inputElement.className = options.className ?? "";
            this.form.insertAdjacentElement("beforeEnd", inputElement);
            this.fields.push({});
        }
    }
    removeField(element, index = -1) {
        if(index === -1) return;

        this.fields = this.fields.filter( (item, i) => index != i);
        element.parentElement.remove();
    }


}

var fieldCollection = new FieldCollection();


async function processData(self) {
    document.startButtonLoader(self);

    const result = (await fieldCollection.submit());
    if(result[0] == 0) {
        document.forms["f1"].reset();
        fieldCollection.getContainer().replaceChildren();
        elements.addElement(
            document.getElementById("parent_select"),
            "option",
            {value: result[1].name, text: result[1].name}
        );
        asset_new_type.types.push(result[1]);
        //
    }

    document.endButtonLoader(self);
}

