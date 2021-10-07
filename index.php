<?php

function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
	// convert from degrees to radians
	$latFrom = deg2rad($latitudeFrom);
	$lonFrom = deg2rad($longitudeFrom);

	$latTo = deg2rad($latitudeTo);
	$lonTo = deg2rad($longitudeTo);

	$latDelta = $latTo - $latFrom;
	$lonDelta = $lonTo - $lonFrom;

	$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
	
	return $angle * $earthRadius;
}

$curl = curl_init();

curl_setopt($curl, CURLOPT_URL, "https://bpdts-test-app.herokuapp.com/users");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$output = curl_exec($curl);

curl_close($curl);

$send_user = [];
$json_arr = json_decode($output, true);

foreach ($json_arr as $value) {
	$distance_in_mile = haversineGreatCircleDistance(51.509865,  -0.118092, $value['latitude'], $value['longitude'], 3959);

	if ((int)$distance_in_mile<=50){
		$value['distance'] = $distance_in_mile;
		$send_user[]=$value;
	}
}

$json_data = json_encode($send_user);

echo "<H3>List of Users Outside London</H3>";
echo "<p>JSON Data:</p>";

echo $json_data;

echo "<br>";

echo "<p>Table Format:</p>";
echo '
<html>
	<head>
		<style>
			table {
				font-family: arial, sans-serif;
				border-collapse: collapse;
				width: 100%;
			}

			td, th {
				border: 1px solid #dddddd;
				text-align: left;
				padding: 8px;
			}

			tr:nth-child(even) {
				background-color: #dddddd;
			}
		</style>
	</head>
	<body>
		<table>
			<tr>
				<th>Id</th>
				<th>First Name</th>
				<th>Last Name</th>
				<th>Email</th>
				<th>IP Address</th>
				<th>Latitude</th>
				<th>Longitude</th>
				<th>Distance From London</th>
			</tr>
		';
		foreach($send_user as $user){
			echo '
				<tr>
					<td>'.$user['id'].'</td>
					<td>'.$user['first_name'].'</td>
					<td>'.$user['last_name'].'</td>
					<td>'.$user['email'].'</td>
					<td>'.$user['ip_address'].'</td>
					<td>'.$user['latitude'].'</td>
					<td>'.$user['longitude'].'</td>
					<td>'.$user['distance'].' Miles</td>
				</tr>';
		}
		echo '</table>
	</body>
</html>';

$inside_curl = curl_init();

curl_setopt($inside_curl, CURLOPT_URL, "https://bpdts-test-app.herokuapp.com/city/London/users");
curl_setopt($inside_curl, CURLOPT_RETURNTRANSFER, 1);

$inside_output = curl_exec($inside_curl);
curl_close($inside_curl);

echo "<br><H3>List of Users Inside London</H3>";
echo "<p>JSON Data:</p>";

echo $inside_output;

$inside_london_arr = json_decode($inside_output, true);

echo "<p>Table Format:</p>";

echo '
<html>
	<head>
		<style>
		table {
		font-family: arial, sans-serif;
		border-collapse: collapse;
		width: 100%;
		}

		td, th {
		border: 1px solid #dddddd;
		text-align: left;
		padding: 8px;
		}

		tr:nth-child(even) {
		background-color: #dddddd;
		}
		</style>
	</head>

	<body>
		<table>
			<tr>
				<th>Id</th>
				<th>First_name</th>
				<th>Last_name</th>
				<th>Email</th>
				<th>Ip_address</th>
				<th>latitude</th>
				<th>longitude</th>
			</tr>';
			foreach($inside_london_arr as $inside_user){
				echo ' <tr>
					<td>'.$inside_user['id'].'</td>
					<td>'.$inside_user['first_name'].'</td>
					<td>'.$inside_user['last_name'].'</td>
					<td>'.$inside_user['email'].'</td>
					<td>'.$inside_user['ip_address'].'</td>
					<td>'.$inside_user['latitude'].'</td>
					<td>'.$inside_user['longitude'].'</td>
					</tr>
				';
			}
			echo '
		</table>

	</body>
</html>';