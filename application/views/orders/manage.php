<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Manage Orders
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Orders</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
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

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Manage Orders</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="manageTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Order Number</th>
                                    <th>Customer Name</th>
                                    <th>Customer Address</th>
                                    <th>Customer Phone</th>
                                    <th>Gross Amount</th>
                                    <th>Net Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo $order['order_number']; ?></td>
                                    <td><?php echo $order['customer_name']; ?></td>
                                    <td><?php echo $order['customer_address']; ?></td>
                                    <td><?php echo $order['customer_phone']; ?></td>
                                    <td><?php echo $order['gross_amount']; ?></td>
                                    <td><?php echo $order['net_amount']; ?></td>
                                    <td>
                                        <a href="<?php echo base_url('orders/update/'.$order['id']) ?>" class="btn btn-default"><i class="fa fa-pencil"></i></a>
                                        <button type="button" class="btn btn-default" onclick="removeFunc('<?php echo $order['id']; ?>')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>
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

<!-- remove order modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" action="<?php echo base_url('orders/remove') ?>" method="post" id="removeForm">
                <div class="modal-header">
                    <h4 class="modal-title">Remove Order</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
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

<script type="text/javascript">
var manageTable;
$(document).ready(function() {
    manageTable = $('#manageTable').DataTable({
        'ajax': 'orders/fetchOrdersData',
        'order': []
    });
});

function removeFunc(id)
{
    if(id) {
        $("#removeForm").on('submit', function() {
            var form = $(this);
            // submit the form to remove the data
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: { order_id:id },
                dataType: 'json',
                success:function(response) {
                    manageTable.ajax.reload(null, false);
                    if(response.success === true) {
                        $("#messages").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                            '<span aria-hidden="true">&times;</span></button>'+
                            response.messages+
                        '</div>');
                    } else {
                        $("#messages").html('<div class="alert alert-error alert-dismissible" role="alert">'+
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                            '<span aria-hidden="true">&times;</span></button>'+
                            response.messages+
                        '</div>');
                    }
                }
            });
            return false;
        });
    }
}
</script>

