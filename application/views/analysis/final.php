<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
 <style>

/* HTML: <div class="loader"></div> */
.loader {
  width: fit-content;
  font-weight: bold;
  font-family: monospace;
  font-size: 30px;
  overflow: hidden;
}
.loader::before {
  content: "Loading...";
  color: #0000;
  text-shadow: 0 0 0 #000,10ch 0 0 #fff,20ch 0 0 #000;
  background: linear-gradient(90deg,#0000 calc(100%/3),#000 0 calc(2*100%/3),#0000 0) left/300% 100%;
  animation: l23 2s infinite;
}

@keyframes l23{
  50% {background-position: center;text-shadow: -10ch 0 0 #000,    0 0 0 #fff,10ch 0 0 #000}
  100%{background-position: right ;text-shadow: -20ch 0 0 #000,-10ch 0 0 #fff,   0 0 0 #000}
}

</style>    
 <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
 <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
 <!-- Include Chart.js and Chart.js Zoom plugin -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>
<!-- Content Header (Page header) -->
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
        <h1>Project Analysis</h1>
      </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#"><?php echo lang('home') ?></a></li>
              <li class="breadcrumb-item"><a href="<?php echo url('/projects') ?>"><?php echo lang('projects') ?></a></li>
              <li class="breadcrumb-item active">Analysis</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
<!-- Main content -->

<!-- Main content -->
<section class="content">

<div class="row">
          <div class="col-12">
            <!-- Custom Tabs -->
            <div class="card">
         
              <div class="card-body">
            
              <div class="container-fluid my-5">


<h2 style="color:red;font-size:12px;font-weight:bold;">Please make sure you clear the data before and after the generate chart</h2>

<div class="row">
   <div class="col-4">
     <div class="row justify-content-center">
           <div class="col-md-12" >
               <div class="card">
                   <div class="card-header">
                       <h5 class="mb-0">Enter Range Values</h5>
                   </div>
                   <div class="card-body">
                       
                   <select id="projectList" class="form-control valid" multiple>
    <!-- Options will be dynamically populated via AJAX -->
</select>
<hr>

<div class="col-md-12">
<div class="row">
    <div class="col-md-6" >

    <label for="min10cmgml">Min 10cmgml</label>
    <input type="text" id="min10cmgml" name="min10cmgml" value="100">

    </div>

    <div class="col-md-6" >
    <label for="max10cmgml">Max 10cmgml:</label>
    <input type="text" id="max10cmgml" name="max10cmgml" value="500">
    </div>
</div>


<div class="row">
    <div class="col-md-6" >

    <label for="min25cmgml">Min 25cmgml</label>
    <input type="text" id="min25cmgml" name="min25cmgml" value="100">

    </div>

    <div class="col-md-6" >
    <label for="max25cmgml">Max 25cmgml:</label>
    <input type="text" id="max25cmgml" name="max25cmgml" value="500">
    </div>
</div>

<div class="row">
    <div class="col-md-6" >

    <label for="min50cmgml">Min 50cmgml</label>
    <input type="text" id="min50cmgml" name="min50cmgml" value="100">

    </div>

    <div class="col-md-6" >
    <label for="max50cmgml">Max 50cmgml:</label>
    <input type="text" id="max50cmgml" name="max50cmgml" value="500">
    </div>
</div>

<hr>

<button id="submitFilters">Apply Filters</button>

</div>
                  
                   </div>
               </div>
           </div>
       </div>
   </div>
   <div class="col-8">
<div class="loader" id="loadingc" style="display:none"></div>
<div id="aid" style="font-size:14px; font-weight:bold"></div>
  
   <table id="resultsTable" class="table table-bordered table-hover table-striped dataTable no-footer" style="width:100%">
    <thead>
        <tr>
            <th>System Name</th>
            <th>10cmgml</th>
            <th>25cmgml</th>
            <th>50cmgml</th>
        </tr>
    </thead>
    <tbody>
        <!-- Data will be populated dynamically here -->
    </tbody>
</table>





<!-- HTML element to display the total count -->
<div id="totalCount"></div>





</div>

     
      		</div>
      	</div>
                  </div>
                 
                 		  
                  
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- ./card -->
          </div>


          

          <!-- /.col -->
        </div>

        
        <!-- /.row -->
        <!-- END CUSTOM TABS -->

    

      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</div>
    
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
// Cache frequently accessed elements
var projectList = $('#projectList');
var aid = $('#aid');
var submitFilters = $('#submitFilters');

// AJAX request to fetch projects
$.ajax({
    url: "<?php echo site_url('analysis/fetchProjects'); ?>",
    type: 'GET',
    dataType: 'json',
    success: function(response) {
        if (response.length > 0) {
            var options = response.map(function(project) {
                return '<option value="' + project.id + '">' + project.project_name + '</option>';
            }).join('');
            projectList.html(options);
        } else {
            projectList.html('<option value="">No projects found</option>');
        }
    },
    error: function(xhr, status, error) {
        console.error(error);
        projectList.html('<option value="">Error fetching projects</option>');
    }
});

var existingData = []; // Variable to store existing data

// Click event handler for submitFilters button
submitFilters.click(function() {
    var projectId = projectList.val();
    var projectName = projectList.find('option:selected').text(); // Get the selected project name
    $('#loadingc').show();
    $.ajax({
        url: "<?php echo site_url('analysis/fetchJobId'); ?>",
        type: 'POST',
        dataType: 'json',
        data: { projectId: projectId },
        success: function(response) {
            var jobId = response;
            aid.append(' ' + projectName);

            var formData = {
                jobId: jobId,
                min10cmgml: $('#min10cmgml').val(),
                max10cmgml: $('#max10cmgml').val(),
                min25cmgml: $('#min25cmgml').val(),
                max25cmgml: $('#max25cmgml').val(),
                min50cmgml: $('#min50cmgml').val(),
                max50cmgml: $('#max50cmgml').val()
            };

            $.ajax({
                url: "<?php echo site_url('analysis/fetchResults'); ?>",
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function(response) {
                    
                                     
// Destroy the existing DataTable instance
var table = $('#resultsTable').DataTable();
if ($.fn.DataTable.isDataTable('#resultsTable')) {
    table.destroy();
}

// Remove duplicate rows from the response data
var uniqueResponse = [];
$.each(response, function(index, item) {
    var isDuplicate = false;
    $.each(uniqueResponse, function(uniqueIndex, uniqueItem) {
        if (item.ssystem_name === uniqueItem.ssystem_name &&
            item['10cmgml'] === uniqueItem['10cmgml'] &&
            item['25cmgml'] === uniqueItem['25cmgml'] &&
            item['50cmgml'] === uniqueItem['50cmgml']) {
            isDuplicate = true;
            return false; // exit loop if duplicate is found
        }
    });
    if (!isDuplicate) {
        uniqueResponse.push(item);
    }
});

// Reinitialize DataTable with new unique data
table = $('#resultsTable').DataTable({
    data: uniqueResponse,
    columns: [
        { data: 'ssystem_name', title: 'System Name' },
        { data: '10cmgml', title: '10cmgml' },
        { data: '25cmgml', title: '25cmgml' },
        { data: '50cmgml', title: '50cmgml' }
    ]
});


$('#loadingc').hide();
                    
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle errors
                }
            });

        },
        error: function(xhr, status, error) {
            console.error(error);
            // Handle errors
        }
    });
});

</script>    
<?php include viewPath('includes/footer'); ?>