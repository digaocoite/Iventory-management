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
        <div class="row">
            <div class="col-md-12 col-xs-12">

                <div id="messages"></div>

                <!-- Flash Messages -->
                <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php elseif($this->session->flashdata('error')): ?>
                <div class="alert alert-error alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
                <?php endif; ?>


                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Edit Product</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- Form Start -->
                    <form role="form" action="<?php echo base_url('products/update/'.$product_data['id']) ?>"
                        method="post">
                        <div class="box-body">

                            <?php echo validation_errors(); ?>

                            <div class="form-group">
                                <label for="product_name">Product name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name"
                                    value="<?php echo $product_data['name']; ?>" autocomplete="off" />
                            </div>

                            <div class="form-group">
                                <label for="reference_number">Reference Number</label>
                                <input type="text" class="form-control" id="reference_number" name="reference_number"
                                    value="<?php echo $product_data['reference_number']; ?>" autocomplete="off" />
                            </div>

                            <div class="form-group">
                                <label for="LOT">LOT</label>
                                <input type="text" class="form-control" id="LOT" name="LOT"
                                    value="<?php echo $product_data['LOT']; ?>" autocomplete="off" />
                            </div>

                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <input type="text" class="form-control" id="qty" name="qty"
                                    value="<?php echo $product_data['qty']; ?>" autocomplete="off" />
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea type="text" class="form-control" id="description" name="description"
                                    autocomplete="off"><?php echo $product_data['description']; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="expiration_date">Expiration Date</label>
                                <input type="date" class="form-control" id="expiration_date" name="expiration_date"
                                    value="<?php echo $product_data['expiration_date']; ?>" autocomplete="off" />
                            </div>

                            <?php if ($attributes): ?>
                            <?php foreach ($attributes as $k => $v): ?>
                            <div class="form-group">
                                <label for="groups"><?php echo $v['attribute_data']['name'] ?></label>
                                <select class="form-control select_group" id="attributes_value_id"
                                    name="attributes_value_id[]" multiple="multiple">
                                    <?php foreach ($v['attribute_value'] as $k2 => $v2): ?>
                                    <option value="<?php echo $v2['id'] ?>"
                                        <?php echo in_array($v2['id'], (array)$product_data['attribute_value_id']) ? 'selected="selected"' : ''; ?>>
                                        <?php echo $v2['value'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <?php endforeach ?>
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control select_group" id="type" name="type">
                                    <?php foreach ($types as $k => $v): ?>
                                    <option value="<?php echo $v['id'] ?>"
                                        <?php echo $v['id'] == $product_data['type_id'] ? 'selected="selected"' : ''; ?>>
                                        <?php echo $v['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="store">Store</label>
                                <select class="form-control select_group" id="store" name="store">
                                    <?php foreach ($stores as $k => $v): ?>
                                    <option value="<?php echo $v['id'] ?>"
                                        <?php echo $v['id'] == $product_data['store_id'] ? 'selected="selected"' : ''; ?>>
                                        <?php echo $v['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="availability">Availability</label>
                                <select class="form-control" id="availability" name="availability">
                                    <option value="1"
                                        <?php echo $product_data['availability'] == 1 ? 'selected="selected"' : ''; ?>>Yes
                                    </option>
                                    <option value="2"
                                        <?php echo $product_data['availability'] == 2 ? 'selected="selected"' : ''; ?>>No
                                    </option>
                                </select>
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="<?php echo base_url('products/') ?>" class="btn btn-warning">Back</a>
                        </div>
                    </form>
                    <!-- Form End -->
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

<script type="text/javascript">
    $(document).ready(function () {
        $(".select_group").select2();
        $("#description").wysihtml5();

        $("#mainProductNav").addClass('active');
        $("#addProductNav").addClass('active');
    });
</script>

