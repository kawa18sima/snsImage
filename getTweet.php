<?php
$mysqli = new mysqli(getenv('SERVER_URL'), getenv('SERVER_USER'), getenv('SERVER_PASS'), getenv('SERVER_DB'));
if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
} else {
    $mysqli->set_charset("utf8");
}
$sql = "SELECT sync FROM sns_acounts WHERE sns='twitter'";
while(true){
    if ($result = $mysqli->query($sql)) {
        while ($row = $result->fetch_assoc()) {
            if(!$row["sync"]) continue;
            echo getenv('TWEET_URL').$row['sync'].'/';
            $url = getenv('TWEET_URL').$row['sync'].'/';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_exec($curl);
            curl_close($curl);
            sleep(60);
        }
        $result->close();
    }
    sleep(60);
}
$mysqli->close();

?>
