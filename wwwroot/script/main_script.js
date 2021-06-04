
// Create the calendar on the current date.
scheduler.init("scheduler_here", new Date(), "month");
scheduler.setLoadMode("day");

// // Disable the drag feature for creating events.
// scheduler.config.drag_resize = false;
// scheduler.config.drag_move = false;
// scheduler.config.drag_create = false;

// // Disable the double click feature for creating events
// scheduler.config.dblclick_create = false;

// Load the data from the database.
scheduler.load("data/api.php");

// Upload any new data to the database.
let dp = new dataProcessor("data/api.php");
dp.init(scheduler);

// Set data exchange mode.
dp.setTransactionMode("JSON");