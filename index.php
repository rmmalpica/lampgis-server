<?php

$command = $_POST["command"];

if($command=="create_raster_layer")
{	
	
	$mysqli = connectDB();
	
	$name = $mysqli->real_escape_string($_POST["name"]); 
	$metadata = $mysqli->real_escape_string($_POST["metadata"]);
	$xmin = $_POST["xmin"];
	$ymin = $_POST["ymin"];
	$xmax = $_POST["xmax"];
	$ymax = $_POST["ymax"];
	$width = $_POST["width"];
	$height = $_POST["height"];
	$band_count = $_POST["bandCount"];
	$cell_size_x = $_POST["rasterUnitsPerPixelX"];
	$cell_size_y = $_POST["rasterUnitsPerPixelY"];
	$no_data_value = $_POST["noDataValue"];
	
	$query = "INSERT INTO RASTER_LAYER (name, metadata, xmin, ymin, xmax, ymax, width, height, 
				band_count, cell_size_x, cell_size_y, no_data_value) VALUES 
				('$name', '$metadata', $xmin, $ymin, $xmax, $ymax, $width, $height, 
				$band_count, $cell_size_x, $cell_size_y, '$no_data_value')";
	if($mysqli->query($query) == TRUE)
	{
		$id = $mysqli->insert_id;
		echo $id;
	}
	else 
	{
		echo "Error: " . $mysqli->error;
	}

	exit();
}

$data_file_name = $_FILES['dataFile']['name'];
$data_file_size =$_FILES['dataFile']['size'];
$data_file_tmp =$_FILES['dataFile']['tmp_name'];

$image_file_name = $_FILES['imageFile']['name'];
$image_file_size =$_FILES['imageFile']['size'];
$image_file_tmp =$_FILES['imageFile']['tmp_name'];


mkdir($id."_".$name);

$folder = 

move_uploaded_file($data_file_tmp, $id."_".$name."/".$data_file_name);
move_uploaded_file($image_file_tmp, $id."_".$name."/".$image_file_name);

$out_file_name = str_replace('.gz', '.dat', $data_file_name);

uncompress($id."_".$name."/".$data_file_name, $id."_".$name."/".$out_file_name);



function uncompress($data_file_name, $out_file_name)
{	
	// Raising this value may increase performance
	$buffer_size = 102400; // read 100kb at a time	
	
	// Open our files (in binary mode)
	$file = gzopen($data_file_name, 'rb');
	$out_file = fopen($out_file_name, 'wb');
	
	// Keep repeating until the end of the input file
	while (!gzeof($file)) {
		// Read buffer-size bytes
		// Both fwrite and gzread and binary-safe
		fwrite($out_file, gzread($file, $buffer_size));
	}
	
	// Files are done, close files
	fclose($out_file);
	gzclose($file);
}

function connectDB()
{
	$mysqli = new mysqli("localhost", "lampgis", "lampgis", "GIS");
	if ($mysqli->connect_errno) {
		echo "Error connecting to BD: (" . $mysqli->$connect_errno . ") " . $mysqli->connect_error;
	}
	
	return $mysqli;
}

?>