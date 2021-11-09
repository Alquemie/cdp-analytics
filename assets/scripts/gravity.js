
jQuery(document).ready(function() {
    let segmentForms = {};
    if(typeof sessionStorage.segmentForms === 'undefined'){
        // Initialize segmentForms if not defined
        sessionStorage.setItem("segmentForms", JSON.stringify(segmentForms));
    }

    jQuery(".gform_wrapper").each(function() {
        var segmentForms = JSON.parse(sessionStorage.getItem("segmentForms"));

        var formId = this.id.split("_").pop();
        var formName = "gform_" + formId;
        var formTitle = jQuery(this).find(".gform_title").html();

        segmentForms[formName] = { title: formTitle, fields: {}, legends: {} };

        nameField = jQuery("#" + formName + " .ginput_container_name").first().attr("id");
        segmentForms[formName].fields[nameField + "_3"] = "first_name";
        segmentForms[formName].fields[nameField + "_6"] = "last_name";

        addressField = jQuery(this).find(".ginput_container_address").first().attr("id");;
        segmentForms[formName].fields[addressField + "_1"] = "address.street";
        segmentForms[formName].fields[addressField + "_2"] = "address.street2";
        segmentForms[formName].fields[addressField + "_3"] = "address.city";
        segmentForms[formName].fields[addressField + "_4"] = "address.state";
        segmentForms[formName].fields[addressField + "_5"] = "address.postalCode";
        segmentForms[formName].fields[addressField + "_6"] = "address.country";

        emailField = jQuery(this).find("input[type=email]").first().attr("id");;
        segmentForms[formName].fields[emailField] = "email";
        
        phoneField = jQuery(this).find("input[type=tel]").first().attr("id");;
        segmentForms[formName].fields[phoneField] = "phone";

        jQuery(this).find('legend').each(function() {
            // console.log(jQuery(this).text());
            fieldsetId = jQuery(this).closest('fieldset').attr("id");

            segmentForms[formName].legends[fieldsetId] = jQuery(this).text();
        });

        sessionStorage.setItem("segmentForms", JSON.stringify(segmentForms));

        jQuery("#gform_submit_button_" + formId).click(function() {
            const formId = this.id.split("_").pop();
            const formName = "gform_" + formId;
            const formInfo = JSON.parse(sessionStorage.getItem("segmentForms"));
            jQuery("#gform_" + formId + "_validation_container").empty();

            var fields = jQuery("#" + formName + " input, #" + formName + " select, #" + formName + " textarea").serializeArray();
            for(i = 0; i < fields.length; i++) {
                // console.log ("Field: " + fields[i].name );
                // fields[i].id = (fields[i].name.indexOf(".") > 0) ? jQuery("[name='" + fields[i].name + "']").attr("id") : fields[i].name ;
                thisField = jQuery("[name='" + fields[i].name + "']");
                fields[i].id = jQuery(thisField).attr("id");

                if (typeof fields[i].id !== 'undefined') {
                    
                    if (jQuery(thisField).hasClass("textarea")) {
                        fields[i].type = "textarea";
                    } else if (jQuery(thisField).hasClass("gfield_select")) {
                        fields[i].type = "select";
                    } else if (jQuery(thisField).is("select")) {
                        fields[i].type = "select";
                    } else {
                        fields[i].type = jQuery(thisField).attr("type");
                    }

                    fieldNum = fields[i].id.split("_")[2];
                    legend = formInfo[formName].legends["field_" + formId + "_" + fieldNum];
                    legend = (typeof legend === 'undefined') ? "" : legend + " ";

                    fields[i].trait = snakeCase(legend + jQuery("label[for='" + fields[i].id + "']").text());
                }
                
            }
            // console.log(fields);
            sessionStorage.setItem(formName, JSON.stringify(fields));
        });
    });
    

    jQuery(document).on('gform_confirmation_loaded', function(event, formId){
        gfIdentify(formId);
    });


    jQuery(document).on('gform_post_render', function(event, form_id, current_page){
        console.log("#gform_" + form_id + "_validation_container");
        // This is not working yet
        /*
        if (jQuery("#gform_" + form_id + "_validation_container").length > 0) {
            gfTrack(form_id, jQuery("#gform_" + form_id + "_validation_container").text());
            console.log("EVENT");
            console.log(jQuery("#gform_" + form_id + "_validation_container").text());
        }
        */
    });

} );

function snakeCase(str) {
    return str && str.match(/[A-Z]{2,}(?=[A-Z][a-z]+[0-9]*|\b)|[A-Z]?[a-z]+[0-9]*|[A-Z]|[0-9]+/g)
        .map(s => s.toLowerCase())
        .join('_');
}

function gfTrack(formId, message) {
    var segmentForms = JSON.parse(sessionStorage.getItem("segmentForms"));
    var formName = "gform_" + formId;
       
    if (message == "SUCCESS") {
        analytics.track("Form Submitted", {
            form: segmentForms[formName].title
        });
    } else {
        analytics.track("Form Submission Failed", {
            form: segmentForms[formName].title,
            error: message
        });
    }
}

function gfIdentify(formId) {
    var formName = "gform_" + formId;

    var segmentForms = JSON.parse(sessionStorage.getItem("segmentForms"));
    var data = JSON.parse(sessionStorage.getItem(formName));
    
    // Build Traits
    delete traits;
    let traits = {};

    for(i = 0; i < data.length; i++) {
        if (data[i].type && data[i].trait != "") {
            value = "";

            switch (data[i].type) {
                case "checkbox":
                    value = true;
                    break;
                default:
                    value = data[i].value
                    break;
            }

            if (value != "") {
                id = (segmentForms[formName].fields[data[i].id]) ? segmentForms[formName].fields[data[i].id] : data[i].trait;
                if (id.indexOf(".") > 0) {
                    // check for id with . and split to object
                    obj = id.split(".",2);
                    if (!traits[obj[0]]) {
                        traits[obj[0]] = {};
                    }
                    traits[obj[0]][obj[1]] = value;
                } else if (id == "company") {
                    traits["company"] = {};
                    traits["company"]["name"] = value;
                } else {
                    traits[id] = value;
                }
            }
            
        }
    }
    console.log(JSON.stringify(traits));

    // Send Identify
    analytics.identify(traits);

    // Follow up with Track Call
    gfTrack(formId, "SUCCESS");
}