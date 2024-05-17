<?php
namespace App\Helpers;
 
class ExportToExcelHelper 
{
	
	static function ExportDestroyedPackages($data) {
		$output = '<html xmlns:x=\"urn:schemas-microsoft-com:office:excel\">
			<head>
			    <meta charset="UTF-8">
			    <style> td {mso-number-format:\\@;} </style>
			</head>

			<body>
			   <table>'.self::prepareDataForExport($data).'</table>
			</body>
		</html>';
		return $output;
	}

	static function prepareDataForExport($packages) {
		$t = "";
		foreach($packages as $package) 
		{
			$t .= 
			"<tr>
				<td>PAKET: <b>{$package['serialNumber']}</b></td>
				<td>Datum prevzema: <b>".self::formatDateTime($package['packageAssumedDate'], "d.m.Y")."</b></td>
				<td align='right'>Datum uničenja: <b>".self::formatDateTime($package['packageDestroyedDate'], "d.m.Y")."</b></td>
			</tr>";

			$t .= 
				"<tr>
					<td>&nbsp;</td>
					<td><b>ASCII</b></td>
					<td align='right'><b>DATUM UNIČENJA</b></td>
				</tr>";
			foreach ($package["destroyedPlates"] as $plate) 
			{
				$t .= 
				"<tr>
					<td></td>
					<td>{$plate['asciiPlateNumber']}</td>
					<td align='right'>".self::formatDateTime($plate['destructionDateTime'], "d.m.Y H:i:s")."</td>
				</tr>";
			}
			$t .= "<tr><td></td><td></td><td></td></tr>";
		}
		return $t;
	}

	static function formatDateTime($value, $format) {
		$date = date_create($value);
		return date_format($date, $format);
	}


	static function fakeDateForExport() {
		return 
		[
			[
				"serialNumber" => "2012 01648",
				"packageDestroyedDate" => "2012-10-23T00:00:00+02:00",
				"packageAssumedDate" => "2012-10-23T00:00:00+02:00",
				"destroyedPlates" => [
					[
						"asciiPlateNumber" => "LJAA1234",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					],
					[
						"asciiPlateNumber" => "LJAA1235",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					],
					[
						"asciiPlateNumber" => "LJAA1236",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					],
					[
						"asciiPlateNumber" => "LJAA1237",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					]
				]				
			],
			[
				"serialNumber" => "2012 01648",
				"packageDestroyedDate" => "2012-10-23T00:00:00+02:00",
				"packageAssumedDate" => "2012-10-23T00:00:00+02:00",
				"destroyedPlates" => [
					[
						"asciiPlateNumber" => "LJAA1234",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					],
					[
						"asciiPlateNumber" => "LJAA1235",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					],
					[
						"asciiPlateNumber" => "LJAA1236",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					],
					[
						"asciiPlateNumber" => "LJAA1237",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					]
				]				
			],
			[
				"serialNumber" => "2012 01648",
				"packageDestroyedDate" => "2012-10-23T00:00:00+02:00",
				"packageAssumedDate" => "2012-10-23T00:00:00+02:00",
				"destroyedPlates" => [
					[
						"asciiPlateNumber" => "LJAA1234",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					],
					[
						"asciiPlateNumber" => "LJAA1235",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					],
					[
						"asciiPlateNumber" => "LJAA1236",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					],
					[
						"asciiPlateNumber" => "LJAA1237",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					]
				]				
			],
			[
				"serialNumber" => "2012 01648",
				"packageDestroyedDate" => "2012-10-23T00:00:00+02:00",
				"packageAssumedDate" => "2012-10-23T00:00:00+02:00",
				"destroyedPlates" => [
					[
						"asciiPlateNumber" => "LJAA1234",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					],
					[
						"asciiPlateNumber" => "LJAA1235",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					],
					[
						"asciiPlateNumber" => "LJAA1236",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					],
					[
						"asciiPlateNumber" => "LJAA1237",
						"destructionDateTime" => "2014-10-02T12:22:57+02:00"
					]
				]				
			]
		];
	} 
}