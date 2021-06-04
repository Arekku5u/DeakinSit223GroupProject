<?php
function read($db, $requestParams){
    $queryParams = [];
    $queryText = "SELECT * FROM `event_information`";

    // handle dynamic loading
    if (isset($requestParams["from"]) && isset($requestParams["to"])) {
        $queryText .= " WHERE `end_date`>=? AND `start_date` < ?;";
        $queryParams = [$requestParams["from"], $requestParams["to"]];
    }
    $query = $db->prepare($queryText);
    $query->execute($queryParams);
    $events = $query->fetchAll();
    return $events;
}

// create a new event
function create($db, $event){
    $queryText = "INSERT INTO `event_information` SET
        `start_date`=?,
        `end_date`=?,
        `text`=?,
        `event_pid`=?,  
        `event_length`=?,  
        `rec_type`=?";
    $queryParams = [
        $event["start_date"],
        $event["end_date"],
        $event["text"],
        $event["event_pid"] ? $event["event_pid"] : 0,
        $event["event_length"] ? $event["event_length"] : 0,
        $event["rec_type"]
    ];
    $query = $db->prepare($queryText);
    $query->execute($queryParams);
    return $db->lastInsertId();
}
// update an event
function update($db, $event, $id){
    $queryText = "UPDATE `event_information` SET
        `start_date`=?,
        `end_date`=?,
        `text`=?,
        `event_pid`=?, 
        `event_length`=?, 
        `rec_type`=? 
        WHERE `id`=?";
    $queryParams = [
        $event["start_date"],
        $event["end_date"],
        $event["text"],
        $event["event_pid"] ? $event["event_pid"] : 0,
        $event["event_length"] ? $event["event_length"] : 0,
        $event["rec_type"],
        $id
    ];
    if ($event["rec_type"] && $event["rec_type"] != "none") {
        //all modified occurrences must be deleted when you update recurring  series
        //https://docs.dhtmlx.com/scheduler/ server_integration.html#recurringevents
        $subQueryText = "DELETE FROM `event_information` WHERE `event_pid`=? ;";
        $subQuery = $db->prepare($subQueryText);
        $subQuery->execute([$id]);
    }
    $query = $db->prepare($queryText);
    $query->execute($queryParams);
}
// delete an event
function delete($db, $id){
    // some logic specific to recurring events support
    // https://docs.dhtmlx.com/scheduler/server_integration.html#recurringevents
    $subQueryText = "SELECT * FROM `event_information` WHERE id=? LIMIT 1;";
    $subQuery = $db->prepare($subQueryText);
    $subQuery->execute([$id]);
    $event = $subQuery->fetch();
    if ($event["event_pid"]) {
        // deleting a modified occurrence from a recurring series
        // If an event with the event_pid value was deleted - it needs updating
        // with rec_type==none instead of deleting.
        $subQueryText="UPDATE `event_information` SET `rec_type`='none' WHERE `id`=?;";
        $subQuery = $db->prepare($subQueryText);
        $subQuery->execute([$id]);
    }else{
        if ($event["rec_type"] && $event["rec_type"] != "none") {
            // if a recurring series deleted, delete all modified occurrences
            // of the series
            $subQueryText = "DELETE FROM `event_information` WHERE `event_pid`=? ;";
            $subQuery = $db->prepare($subQueryText);
            $subQuery->execute([$id]);
        }
        /*
        end of recurring events data processing
        */
        $queryText = "DELETE FROM `event_information` WHERE `id`=? ;";
        $query = $db->prepare($queryText);
        $query->execute([$id]);
    }
}


$dsn = "mysql:host=localhost;dbname=calendar"; // Double check the host.
$username = "root";
$password = "root"; // Change this to whatever you set your password as.

$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
);
$db = new PDO($dsn, $username, $password, $options);
try {
    switch ($_SERVER["REQUEST_METHOD"]) {
        case "GET":
            $result = read($db, $_GET);
            break;
        case "POST":
            $requestPayload = json_decode(file_get_contents("php://input"));
            $id = $requestPayload->id;
            $action = $requestPayload->action;
            $body = (array) $requestPayload->data;
            $result = [
                "action" => $action
            ];
            if ($action == "inserted") {
                $databaseId = create($db, $body);
                $result["tid"] = $databaseId;
                // delete a single occurrence from  recurring series
                if ($body["rec_type"] === "none") {
                    $result["action"] = "deleted";
                }
            } elseif($action == "updated") {
                update($db, $body, $id);
            } elseif($action == "deleted") {
                delete($db, $id);
            }
            break;
        default:
            throw new Exception("Unexpected Method");
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    $result = [
        "action" => "error",
        "message" => $e->getMessage()
    ];
}


header("Content-Type: application/json");
echo json_encode($result);



