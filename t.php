<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set server details
$host = '128.199.31.121';
$username = 'chemistry1';
$password = 'Ravi@1234';

// Connect to server using SSH
$connection = ssh2_connect($host, 22);
ssh2_auth_password($connection, $username, $password);

// Set the directory to change to
$directory = '/home/chemistry1/einnel/opencosmos/openCOSMO-RS_py/src/opencosmorspy';

print_r($_POST);

$input = $_POST['inputn']; // Get the input data from the form
  $filename = $_POST['filename']; // Get the filename from the form
  
  $filename = basename($filename, ".inp") . ".inp"; // Add the .inp extension to the filename if it's not already there

  
  // Create the remote file and write input data to it
$sftp = ssh2_sftp($connection);
$stream = fopen("ssh2.sftp://$sftp$directory/$filename", 'w');
fwrite($stream, $input);
fclose($stream);

// Check if the file was successfully created
if (file_exists("ssh2.sftp://$sftp$directory/$filename")) {
  echo "File created successfully!";
  // Set the command to be executed
//$command = 'cd ' . $directory . ' && pwd && python3';
$command = 'cd ' . $directory . ' &&  pwd && nohup python3 ConformerGenerator.py --structures_file '.$filename.' --n_cores=4';

//$command = $_POST['command'];

// Execute the command using SSH
$stream = ssh2_exec($connection, $command);
$stream_err = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);

// Get the output of the command
stream_set_blocking($stream, true);
stream_set_blocking($stream_err, true);
$progress_regex = '/\r\d{1,3}%/';
$output = '';
$error_output = '';
while(!feof($stream))

{
    // Read the output from the stream
    $output_buffer = fread($stream, 4096);
    $error_buffer = fread($stream_err, 4096);
// Check if the directory changed successfully
if (preg_match('/.*\n(.*?)\n$/', $output_buffer, $matches)) {
    $directory = $matches[1];
    if (strpos($directory, $directory_path) === false) {
        $error_output .= "Failed to change directory to $directory_path\n";
        break;
    }
}

// Update the output and progress
$output .= $output_buffer;
$error_output .= $error_buffer;
$progress_match = preg_match($progress_regex, $output, $matches);
if ($progress_match) {
    $progress_line = $matches[0];
    $progress = intval(substr($progress_line, 1, -1));
    echo "$progress%\n";
}

// Sleep for a while to avoid high CPU usage
usleep(50000);
}

// Get the exit status of the command
$exit_status = ssh2_exec($connection, 'echo $?');
stream_set_blocking($exit_status, true);
$exit_status = intval(trim(fread($exit_status, 4096)));

// Print the output and error messages
echo $output;
echo "Exit status: $exit_status\n";
if ($exit_status != 0) {
echo "Error: $error_output";
}

// Close the SSH connection
fclose($stream);
fclose($stream_err);
fclose($exit_status);


// Check for existing running process
$process_name = 'myprocess';
$command = "ps aux | grep '$process_name' | grep -v grep";
$stream = ssh2_exec($ssh_connection, $command);
stream_set_blocking($stream, true);
$process_list = stream_get_contents($stream);

// Check if the process is running
if (strpos($process_list, $process_name) !== false) {
    // If the process is running, show its details
    $command = "ps aux | grep '$process_name' | grep -v grep";
    $stream = ssh2_exec($ssh_connection, $command);
    stream_set_blocking($stream, true);
    $process_details = stream_get_contents($stream);
    echo "Process is running. Details:\n$process_details";
} else {
    // If the process is not running, show a message
    echo "Process is not running.";
}


ssh2_disconnect($connection);

} else {
  echo "File creation failed!";
}



?>