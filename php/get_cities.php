<?php
    $stmt = $db->prepare("SELECT `id`, `city_name` FROM `cities` ORDER BY `city_name` ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cities = [];
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row;
    }

    $stmt->close();
?>