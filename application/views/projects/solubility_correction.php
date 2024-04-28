<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<style>
#overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 9999;
    color: #fff;
    text-align: center;
    padding-top: 20%;
}

.overlay-content {
    font-size: 24px;
}
.red{
    color: red;
}
.green{
    color: green;
}

    </style>
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
              <li class="breadcrumb-item active">Solubility Correction</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
<!-- Main content -->
<div id="overlay">
    <div class="overlay-content">
        Data Solubility correction is going on...
    </div>
</div>
<?php
$project_details = $this->projects_model->getById($jstatus[0]->project_id);

 $check_existing_data = $this->db->get_where('solubility_corrected_predicted_data', array('job_id' => $jstatus[0]->id))->row_array();
?>
<!-- Main content -->
<section class="content">

<div class="row">
          <div class="col-12">
            <!-- Custom Tabs -->
            <div class="card">
         
              <div class="card-body">
            
              <div class="container-fluid my-5">

                <div class="row">
                   <div class="col-12">
                     <div class="row justify-content-center">
                           <div class="col-md-12" >
                            <div id="cosmo"></div>
                               <div class="card">
                                   <div class="card-header d-flex p-0">
                                <h3 class="card-title p-3"><?php echo lang('projects') ?></h3>
                                <h4 class="card-title p-3">PROJECT CODE <?php echo $jstatus[0]->project_id ?>, Job ID: <?php echo $jstatus[0]->id ?>,
                                PROJECT NAME <?php echo $project_details->project_name ?></h4>
                            
                                
                              </div>
                                
                                   <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                      
                                           <button id = "btnSubmit" class="btn btn-primary" type="button"  style="font-size:16px;">Run Solubility Correction </button>
                                            <div id="loading-image" style="display:none">Please Wait...<img src="<?php echo base_url();?>icons8-dots-loading.gif" /></div>

                                          
                                       </div>
                                       <?php if (!empty($check_existing_data)) {
                                        ?>
                                       <div class="col-md-8">
                                    <?php echo form_open_multipart('projects/savesolubilitydata', [ 'class' => 'form-validate', 'autocomplete' => 'off' 
                                    , 'enctype'=>"multipart/form-data"]); ?>
                                    <div class="info-box shadow-lg">

                                    <div class="info-box-content">
                                    <span class="info-box-text"><h6>Upload Known Solubility</h6></span>

                                        <div class="row mb-2">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="inputGroupFile" aria-describedby="inputGroupFileAddon" name="file" required>
                                                    <label class="custom-file-label" for="inputGroupFile">Choose file</label>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                        <div class="row mb-2">
                                            <div class="col-lg-12 text-left">
                                            <input type="hidden" name="jobid" id="jobid" value="<?php echo $jstatus[0]->id;?>" />
                                            <input type="hidden" name="type" id="type" value="3" />
                                            <button type="submit" class="btn btn-primary" value="">Upload</button>
                                        </div>
                                        </div>

                                       


                                    </div>

                                    </div>
                                     <?php echo form_close(); ?>
                                </div>
                            <?php } ?>
                            </div>
                                                                   </div>
                           </div>
                       </div>
                       
                   </div>

    

<div style="max-height: 800px; /* Set the maximum height for vertical scrollbar */
  overflow-y: auto; /* Enable vertical scrollbar when content overflows */
  max-width: 100%; /* Set the maximum width for horizontal scrollbar */
  overflow-x: auto; /">
<!-- <button id="filterButton">Filter</button> -->

    
<div id="fetchedData"></div>
<table id="dataTable" class="table table-bordered table-hover table-striped dataTable no-footer dtr-inline" style="font-size:8px;width: 100%;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Job ID</th>
            <th>System Name</th>
            <th>Known Solubility</th>
            <th>Predicted Solubility</th>
            <th>Corrected Solubility</th>
            <th>Temp</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        <!-- Data will be dynamically added here -->
    </tbody>
</table>

        <div>
     
        </div>
      
       <div>
    </div>

    </div><br><br>
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

<script type="text/javascript">
var rowsData = [];
getData();

function getData() {
    var job_id = '<?php echo $jstatus[0]->id ?>';

    $.ajax({
        url: "<?php echo site_url('projects/getpredictedSol'); ?>",
        type: "POST",
        data: { job_id: job_id },
        dataType: "json",
        success: function(response) {
            var pdata = response;
            rowsData.push(...pdata); // Use spread syntax to push array elements into rowsData
            console.log(rowsData);

            // Call function to populate DataTable after data is loaded
            populateDataTable();
        },
        error: function(xhr, status, error) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                $.each(xhr.responseJSON.errors, function(key, item) {
                    alert(item);
                });
            } else {
                // Handle other types of errors or no errors in the response
                alert("An error occurred. Please try again later.");
            }
        },
    });
}

function populateDataTable() {
    var data = rowsData;
    console.log(data);

   var dataTable =  $('#dataTable').DataTable({
        data: data,
        buttons: [
        "copy",
        {
             extend:
                'excel' ,// Add Excel download button
                filename: function () {
                // Dynamic CSV file name based on your logic
                  return '<?php echo $project_details->project_name ?>';
              }
          },
        ],
        columns: [
            { data: 'id' },
            { data: 'job_id' },
            { data: 'ssystem_name' },
            { data: 'known_solubility' },
            { data: 'predicted_solubility' },
            { data: 'corrected_solubility' },
            { data: 'temp' },
            { data: 'created_at' }
        ],
        order: [
            [2, 'asc'], // Sort by system name in ascending order
            [6, 'asc']  // Then by temperature in ascending order
        ]
    });
    // Append Excel download button to the DataTable container
    dataTable.buttons().container().appendTo('#dataTable_wrapper .col-md-6:eq(0)');

}

</script>

<script>
$(document).ready(function() {
    $("#btnSubmit").click(function(){
      var job_id = '<?php echo $jstatus[0]->id ?>';
      var project_id = '<?php echo $jstatus[0]->project_id ?>';
      $('#loading-image').show();
        $.ajax({
        url: '<?php echo url('projects/run_solubility_correction') ?>/'+job_id,
        type: 'post',
        data: {
            job_id: job_id
        },
        async: true,
        success: function(response) {
           console.log(response);
          if(response=="Done") {
            $('#loading-image').hide();
             $('#cosmo').html("<h4 class='green'>Activity Executed Successfully</h4>");
             window.location.href = '<?php echo url('projects/solubilityCorrection/') ?>' + project_id;

            
           
          } 
          else {
           $('#loading-image').hide();
           $('#cosmo').html("<h4 class='red'>"+response+"</h4>");
           // window.location.href = '<?php echo url('projects/solubilityCorrection/') ?>' + project_id;

            
           
          }
      
        }
        });
    }); 
});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<?php include viewPath('includes/footer'); ?>

