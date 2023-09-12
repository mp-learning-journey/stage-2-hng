<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers");

date_default_timezone_set('UTC');
$slack = $_GET['slack_name'] ?? "";
$track = $_GET['track'] ?? "";

if($_SERVER["REQUEST_METHOD"] == 'GET') {
    if(empty($slack) || empty($track)) {
        http_response_code(422);
        echo json_encode(["error" => 'slack_name and track are required']);
    }else{
        $datetime = new DateTime('now', new DateTimeZone('UTC'));
        $response = [
            "slack_name" => $slack,
            "current_day" => date('l'),
            "utc_time" => $datetime->format('Y-m-d\TH:i:s\Z'),
            "track" => $track,
            "github_file_url" => "https://github.com/mp-learning-journey/stage-one-hng/blob/main/api.php",
            "github_repo_url" => "https://github.com/mp-learning-journey/stage-one-hng",
            "status_code" => 200
        ];

        http_response_code(200);
        echo json_encode($response);
    }
}else{
    header("HTTP/1.1 404 Route Not Found");
    echo json_encode(["error" => "Route not found"]);
}
