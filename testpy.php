<!-- The HTML page with AJAX implementation -->
<!DOCTYPE html>
<html>
<head>
	<title>Generate Cosmo File from Remote Server</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<style>
body {
  font-family: Arial, sans-serif;
  background-color: #f2f2f2;
}

form {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin-top: 50px;
  padding: 20px;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

label {
  font-size: 18px;
  margin-bottom: 10px;
}

input[type="text"], textarea {
  font-size: 16px;
  padding: 10px;
  border: 2px solid #ccc;
  border-radius: 5px;
  width: 90%;
  margin-bottom: 20px;
}

input[type="submit"] {
  background-color: #4CAF50;
  color: #fff;
  font-size: 18px;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

input[type="submit"]:hover {
  background-color: #3e8e41;
}

</style>

<script>
		$(document).ready(function() {
			$("#execute").click(function() {
				var directory = $("#directory").val();
				var filename = $("#filename").val();
				var inputn = $("#inputn").val();
				//var python_script = $("#python_script").val();
				var command = "cd " + directory + " && pwd && python3 ConformerGenerator.py --structures_file h20.inp --n_cores=4";
                //var command = "cd " + directory + " && pwd && which python3 && exit";

				$("#output").html("");
				$("#progress").html("Execution in progress...");
				var interval = setInterval(function() {
					$.ajax({
						type: "POST",
						url: "t.php",
						data: { command: command, filename: filename, inputn: inputn },
						dataType: "text",
						success: function(response) {
							var lines = response.split("\n");
							var last_line = lines[lines.length - 2];
							var exit_status = parseInt(last_line);
							if (exit_status == 0) {
								$("#progress").html("Execution completed successfully!");
								clearInterval(interval);
							} else {
								$("#progress").html("Execution failed with exit status " + exit_status);
								clearInterval(interval);
							}
							$("#output").html(response);
						},
						error: function(jqXHR, textStatus, errorThrown) {
							$("#progress").html("Execution failed: " + textStatus);
							$("#output").html(errorThrown);
							clearInterval(interval);
						}
					});
				}, 1000);
			});
		});
	</script>
</head>
<body>
	<h1>Generate Cosmo File from Remote Server</h1>
	<p>
		
		<input type="hidden" id="directory" name="directory" value="/home/chemistry1/einnel/opencosmos/openCOSMO-RS_py/src/opencosmorspy">
	</p>
	
	<p>
		<label for="directory">Filename</label>
	<input type="text" name="filename" id="filename">
	</p>
	<p>
		<label for="directory">Input:</label>
		<input type="text" name="inputn" id="inputn"> 
	</p>
	<p>
		<button id="execute">Execute</button>
	</p>
	<p id="progress"></p>
	<pre id="output"></pre>
</body>
</html>
