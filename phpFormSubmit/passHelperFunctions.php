<?php

function getCache($key)
{
    $cacheFile = sys_get_temp_dir() . '/cache_' . md5($key) . '.json';
    if (file_exists($cacheFile)) {
        $data = file_get_contents($cacheFile);
        return json_decode($data, true);
    }
    return false;
}

function setCache($key, $data)
{
    $cacheFile = sys_get_temp_dir() . '/cache_' . md5($key) . '.json';
    file_put_contents($cacheFile, json_encode($data));
}

function getDistanceBetweenPlaces($place1, $place2)
{
    $apiKey = '7a3a0c1b40264ee896db8d29b4f3c388';

    // Function to get coordinates using cURL Multi for parallel requests
    function getCoordinates($places, $apiKey)
    {
        $results = [];
        $mh = curl_multi_init();
        $curlHandles = [];

        foreach ($places as $key => $place) {
            // Check cache first
            $cached = getCache($place);
            if ($cached) {
                $results[$key] = $cached;
                continue;
            }

            $url = "https://api.opencagedata.com/geocode/v1/json?q=" . urlencode($place) . "&key=" . $apiKey . "&limit=1";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5); // Set timeout to 5 seconds
            curl_multi_add_handle($mh, $ch);
            $curlHandles[$key] = $ch;
        }

        // Execute all queries simultaneously
        $running = null;
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);

        // Retrieve the results
        foreach ($curlHandles as $key => $ch) {
            $response = curl_multi_getcontent($ch);
            $data = json_decode($response, true);
            if ($data && isset($data['results'][0]['geometry'])) {
                $coord = [
                    'lat' => $data['results'][0]['geometry']['lat'],
                    'lng' => $data['results'][0]['geometry']['lng']
                ];
                $results[$key] = $coord;
                setCache($places[$key], $coord); // Cache the result
            } else {
                $results[$key] = null; // Indicate failure
            }
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }

        curl_multi_close($mh);
        return $results;
    }

    // Function to calculate distance using Haversine formula
    function haversineDistance($coord1, $coord2)
    {
        $earthRadius = 6371; // Radius of the Earth in kilometers

        $latFrom = deg2rad($coord1['lat']);
        $lonFrom = deg2rad($coord1['lng']);
        $latTo = deg2rad($coord2['lat']);
        $lonTo = deg2rad($coord2['lng']);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Distance in kilometers
    }

    // Get coordinates for both places in parallel
    $places = ['place1' => $place1, 'place2' => $place2];
    $coords = getCoordinates($places, $apiKey);

    // Check if both places were found
    if ($coords['place1'] && $coords['place2']) {
        // Calculate distance
        $distance = haversineDistance($coords['place1'], $coords['place2']);
        return number_format($distance, 2);
    } else {
        return 0;
    }
}

function calculateTripPrice($distance)
{
    // Ensure distance is numeric
    if (!is_numeric($distance)) {
        return "Invalid input: Distance must be a numeric value.";
    }

    // Convert distance to a float just in case it was passed as a string
    $distance = floatval($distance);

    // Pricing rules
    $baseFare = 30; // Minimum charge for up to 1.5 km
    $baseDistance = 1.5; // Base distance in km
    $ratePerKm = 15; // Charge per additional km
    $commissionRate = 0.10; // Commission rate (10%)

    // Calculate total fare based on distance
    if ($distance <= $baseDistance) {
        $fare = $baseFare; // Minimum fare for distances up to 1.5 km
    } else {
        $additionalDistance = $distance - $baseDistance;
        $fare = $baseFare + ($additionalDistance * $ratePerKm);
    }

    // Add commission to the fare
    $commission = $fare * $commissionRate;
    $totalPrice = $fare + $commission;

    // Round the total price to the nearest whole number
    $totalPrice = round($totalPrice);

    return $totalPrice; // Return the rounded total price
}
//otp generator
function generateOTP($length = 6)
{
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= mt_rand(0, 9);
    }
    return $otp;
}
?>