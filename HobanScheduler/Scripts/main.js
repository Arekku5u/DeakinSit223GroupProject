// Create the calendar on the current date.
scheduler.init("scheduler_here", new Date(), "month");
scheduler.setLoadMode("day");


// Loading event information from the JSON file
scheduler.load("../Data_Files/Event_Information.json");

// Custom Attributes
scheduler.data_attributes = function () {
    return [
        ["id"],
        ["text"],
        ["start_date", scheduler.templates.format_date],
        ["end_date", scheduler.templates.format_date],
        ["type"]];
}



// Custom Light box buttons
scheduler.config.buttons_left = ["dhx_delete_btn", "booked_button"];
scheduler.config.buttons_right = ["dhx_save_btn", "dhx_cancel_btn"];
scheduler.locale.labels["booked_button"] = "Book";


// Changing the template of the events to use the css.
scheduler.templates.event_class = function (start, end, event) {
    if (event.type === 'booked') return "booked_event";
    return "unbooked_event";
};

//This is for changing event colour
scheduler.attachEvent("onLightboxButton", function(button_id, node, e) {
    var event_id = scheduler.getState().lightbox_id;
    if (button_id === "booked_button") {
        if (scheduler.getEvent(event_id).type === "booked") {
            scheduler.getEvent(event_id).type = "";
            console.log("Unbooked");
            scheduler.updateView();
            save();
        } else if (scheduler.getEvent(event_id).type === "") {

            scheduler.getEvent(event_id).type = "booked";
            console.log("Booked");
            scheduler.setCurrentView();
            save();
        }
    }
})



// For saving event information to the JSON file
function save() {
    var form = document.getElementById("json_form");
    form.elements.data.value = scheduler.toJSON();
    form.submit();
}

// To Update the JSON File whenever an event is saved or deleted.
scheduler.attachEvent("onEventSave", function (id, node, e) {
    save();
    scheduler.updateView();
    return true;
})
scheduler.attachEvent("onEventDeleted", function (id, node, e) {
    save();
    scheduler.updateView();
})

function myFunction() {
    var x = document.createElement("INPUT");
    x.setAttribute("type", "hidden");
    document.body.appendChild(x);

    document.getElementById("demo").innerHTML = "The Hidden Input Field was created. However, you are not able to see it because it is hidden (not visible).";
}




