<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <!-- Include necessary CSS and JS files -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap.min.js"></script>
</head>
<body>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Manage
                <small>Products</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Products</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12 col-xs-12">

                    <div id="messages"></div>

                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php elseif($this->session->flashdata('error')): ?>
                        <div class="alert alert-error alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if(in_array('createProduct', $user_permission)): ?>
                        <a href="<?php echo base_url('products/create') ?>" class="btn btn-primary">Add Product</a>
                        <br /> <br />
                    <?php endif; ?>

                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Manage Products</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="manageTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Reference Number</th>
                                        <th>LOT</th>
                                        <th>Category Type</th>
                                        <th>Qty</th>
                                        <th>Store</th>
                                        <th>Availability</th>
                                        <th>Expiration Date</th>
                                        <th>Expires In</th>
                                        <?php if(in_array('updateProduct', $user_permission) || in_array('deleteProduct', $user_permission)): ?>
                                            <th>Action</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- col-md-12 -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php if(in_array('deleteProduct', $user_permission)): ?>
        <!-- remove product modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Remove Product</h4>
                    </div>
                    <form role="form" action="<?php echo base_url('products/remove') ?>" method="post" id="removeForm">
                        <div class="modal-body">
                            <p>Do you really want to remove?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Remove</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    <?php endif; ?>

    <script type="text/javascript">
    var manageTable;
    var base_url = "<?php echo base_url(); ?>";

    $(document).ready(function() {
        $("#mainProductNav").addClass('active');

        // Initialize the DataTable
        manageTable = $('#manageTable').DataTable({
            'ajax': {
                'url': base_url + 'products/fetchProductData',
                'type': 'POST'
            },
            'columns': [
                { 'data': 0 }, // Product Name
                { 'data': 1 }, // Reference Number
                { 'data': 2 }, // LOT
                { 'data': 3 }, // Category Type
                { 'data': 4 }, // Qty
                { 'data': 5 }, // Store
                { 'data': 6 }, // Availability
                { 'data': 7 }, // Expiration Date
                { 'data': 8 }, // Expires In
                { 'data': 9 }  // Action buttons
            ],
            'order': []
        });

        // Hide messages after 5 seconds
        setTimeout(function() {
            $(".alert-dismissible").fadeOut("slow", function() {
                $(this).remove();
            });
        }, 5000); // 5000ms = 5 seconds
    });

    function removeFunc(id) {
        if(id) {
            $("#removeForm").off('submit').on('submit', function() {
                var form = $(this);
                $(".text-danger").remove();
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: { product_id: id },
                    dataType: 'json',
                    success: function(response) {
                        manageTable.ajax.reload(null, false);
                        if(response.success === true) {
                            $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                              '<strong> <span class="glyphicon glyphicon-ok-sign"></span> </strong>'+response.messages+
                            '</div>');
                            $("#removeModal").modal('hide'); // Close the modal on success
                            $('body').removeClass('modal-open');
                            $('.modal-backdrop').remove();
                        } else {
                            $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                              '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                              '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
                            '</div>');
                        }
                        // Hide messages after 5 seconds
                        setTimeout(function() {
                            $(".alert-dismissible").fadeOut("slow", function() {
                                $(this).remove();
                            });
                        }, 5000); // 5000ms = 5 seconds
                    }
                });
                return false;
            });
        }
    }
    </script>

</body>
</html>

