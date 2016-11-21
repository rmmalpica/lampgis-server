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
	$stat_min = $_POST["stat_min"];
	$stat_max = $_POST["stat_max"];
	$stat_mean = $_POST["stat_mean"];
	$stat_std_dev = $_POST["stat_std_dev"];
	$extent = $_POST["extent"];
	$no_data_value = $_POST["noDataValue"];
		
	
	$query = "INSERT INTO RASTER_LAYER (name, metadata, xmin, ymin, xmax, ymax, width, height, 
				band_count, cell_size_x, cell_size_y, no_data_value, stat_min, stat_max,
				stat_mean, stat_std_dev, extent) VALUES 
				('$name', '$metadata', $xmin, $ymin, $xmax, $ymax, $width, $height, 
				$band_count, $cell_size_x, $cell_size_y, '$no_data_value', $stat_min, $stat_max,
				$stat_mean, $stat_std_dev, GeomFromText('$extent'))";
	if($mysqli->query($query) == TRUE)
	{
		$id = $mysqli->insert_id;
		echo $id;
	}
	else 
	{
		echo "Error: " . $mysqli->error;
	}

	$mysqli->close();
	exit();
}

if($command=="add_raster_layer_block")
{
	$mysqli = connectDB();
	
	$id = $_POST["id"];
	$name = $_POST["name"];
	$xblock = $_POST["xblock"];
	$yblock = $_POST["yblock"];
	$xmin = $_POST["xmin"];
	$ymin = $_POST["ymin"];
	$xmax = $_POST["xmax"];
	$ymax = $_POST["ymax"];
	$width = $_POST["width"];
	$height = $_POST["height"];	
	$extent = $_POST["extent"];
	$level = $_POST["level"];
	
	$data_file_name = $_FILES['dataFile']['name'];
	$data_file_size =$_FILES['dataFile']['size'];
	$data_file_tmp =$_FILES['dataFile']['tmp_name'];
	
	$image_file_name = $_FILES['imageFile']['name'];
	$image_file_size =$_FILES['imageFile']['size'];
	$image_file_tmp =$_FILES['imageFile']['tmp_name'];
	
	$query = "INSERT INTO RASTER_LAYER_BLOCK (xblock, yblock, xmin, ymin, xmax, ymax, width, height,
	 extent, level, raster_layer_id) VALUES
	('$xblock', '$yblock', $xmin, $ymin, $xmax, $ymax, $width, $height,
	 GeomFromText('$extent'), $level, $id)";
	if($mysqli->query($query) == TRUE)
	{
		$blockid = $mysqli->insert_id;
		echo $blockid;
	}
	else
	{
		echo "Error: " . $mysqli->error;
	}

	$folder = "./data/".$id."_".$name;
	if(!mkdir($folder, 0777, TRUE))
	{
		echo "Cannot create $folder";
	}
	
	move_uploaded_file($data_file_tmp, $folder."/".$data_file_name);
	move_uploaded_file($image_file_tmp, $folder."/".$image_file_name);	
	$out_file_name = str_replace('.gz', '.dat', $data_file_name);	
	uncompress($folder."/".$data_file_name, $folder."/".$out_file_name);		
	
	unlink( $folder."/".$data_file_name);
	
	$mysqli->close();	
}


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