<div class="container-fluid">
    <div class="row">
        <div class="ltbody">
            <div class="page-header">
                <h2><i class="fa fa-home"></i> House <small>|  Details</small></h2>
                Items marked <i class="fa fa-exclamation-circle text-silver"></i> are required.
            </div>
            <form method="POST" name="frmEdit" class="form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="panel-title">House Details</div>
                            </div>
                            <div class="panel-body">
                                <fieldset>
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label class="control-label" for="house_no"><i class="fa fa-exclamation-circle text-silver"></i> House No: </label>
                                            <div class="controls">
                                                <input name="house_no" type="text" class="form-control" required placeholder="24" value="<?php echo $house->house_no; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label class="control-label" for="ptb"><i class="fa fa-exclamation-circle text-silver"></i> PTB: </label>
                                            <div class="controls">
                                                <input name="ptb" type="text" class="form-control" required placeholder="PTB123456" value="<?php echo $house->ptb; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="addr1"><i class="fa fa-exclamation-circle text-silver"></i> Addr1: </label>
                                        <div class="controls">
                                            <input name="addr1" type="text" class="form-control" required placeholder="Jalan Dato Jaafar 19" value="<?php echo $house->addr1; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="addr2"><i class="fa fa-exclamation-circle text-silver"></i> Addr2: </label>
                                        <div class="controls">
                                            <input name="addr2" type="text" class="form-control" required placeholder="Taman Mutiara Desaru" value="<?php echo $house->addr2; ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label class="control-label" for="postcode"><i class="fa fa-exclamation-circle text-silver"></i> Postcode: </label>
                                            <div class="controls">
                                                <input name="postcode" type="text" class="form-control" required placeholder="81930" value="<?php echo $house->postcode; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="control-label" for="city"><i class="fa fa-exclamation-circle text-silver"></i> City: </label>
                                            <div class="controls">
                                                <input name="city" type="text" class="form-control" required placeholder="Bandar Penawar" value="<?php echo $house->city; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="control-label" for="state"><i class="fa fa-exclamation-circle text-silver"></i> State: </label>
                                            <div class="controls">
                                                <input name="state" type="text" class="form-control" required placeholder="Johor" value="<?php echo $house->state; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="country_code">Country:</label>
                                            <div class="controls">
                                                <select name="country_code" class="form-control">
                                                    <?php
                                                    foreach ($countries as $key => $row) {
                                                        ?>
                                                        <option <?php echo ($key == $house->country_code) ? 'selected' : ''; ?> value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="pull-right">
                                            <button name="btnEdit" type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title">Residents </div>

                        </div>
                        <div class="panel-body">
                            <fieldset>
                                <table id="v_house_residents" data-ajax-lookup="houses_id" data-ajax-value="<?php echo $house->id;?>" class="table table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Gender</th>
                                            <th>NRIC</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Relation</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="7">Loading</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="clearfix"></div>
                                <div class="form-actions">
                                    <div class="pull-right">
                                        <a href="?page=pddk-residents&action=add&house_id=<?php echo $house->id ;?>&return=<?php echo urlencode('page=pddk-houses&action=edit&id='.$house->id);?>" type="submit" class="btn btn-primary">Add Residents</a>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>