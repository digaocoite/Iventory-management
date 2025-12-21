<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Category: <?php echo $category_name; ?>
      <small>Products under this category</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="<?php echo base_url('category') ?>">Category</a></li>
      <li class="active"><?php echo $category_name; ?></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12 col-xs-12">

        <div id="messages"></div>

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
                <th>Qty</th>
                <th>Store</th>
                <th>Availability</th>
                <th>Expiration Date</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($products as $product): ?>
                <tr>
                  <td><?php echo $product['name']; ?></td>
                  <td><?php echo $product['reference_number']; ?></td>
                  <td><?php echo $product['LOT']; ?></td>
                  <td><?php echo $product['qty']; ?></td>
                  <td><?php echo $product['store_id']; ?></td>
                  <td><?php echo $product['availability']; ?></td>
                  <td><?php echo $product['expiration_date']; ?></td>
                  <td>
                    <?php if (in_array('updateProduct', $user_permission)): ?>
                      <a href="<?php echo base_url('products/update/'.$product['id']) ?>" class="btn btn-default"><i class="fa fa-pencil"></i></a>
                    <?php endif; ?>
                    <?php if (in_array('deleteProduct', $user_permission)): ?>
                      <button type="button" class="btn btn-default" onclick="removeFunc(<?php echo $product['id']; ?>)" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
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

<!-- Add the DataTables script to enable search functionality -->
<script type="text/javascript">
  $(document).ready(function() {
    $("#mainCategoryNav").addClass('active');

    // Initialize DataTable with search functionality
    $('#manageTable').DataTable({
      'order': [],
      'pageLength': 10
    });
  });

  function removeFunc(id) {
    if(id) {
      $("#removeForm").on('submit', function() {
        var form = $(this);

        // remove the text-danger
        $(".text-danger").remove();

        $.ajax({
          url: form.attr('action'),
          type: form.attr('method'),
          data: { product_id:id },
          dataType: 'json',
          success:function(response) {

            if(response.success === true) {
              location.reload();
            } else {
              $("#messages").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                '<strong> <span class="glyphicon glyphicon-exclamation-sign"></span> </strong>'+response.messages+
              '</div>');
            }
          }
        });

        return false;
      });
    }
  }
</script>

<!-- Modal for Remove Confirmation -->
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

