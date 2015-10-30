<div class="container-fluid">
    <div class="row">
        <div class="ltbody">
            <div class="page-header">
                <h2><i class="fa fa-home"></i> House <small>|  New</small></h2>
                Add new house. Items marked <i class="fa fa-exclamation-circle text-silver"></i> are required.
            </div>
            <div class="form">
                <form method="POST" name="frmNew">
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
                                                    <input name="house_no" type="text" class="form-control" required placeholder="24">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="control-label" for="ptb"><i class="fa fa-exclamation-circle text-silver"></i> PTB: </label>
                                                <div class="controls">
                                                    <input name="ptb" type="text" class="form-control" required placeholder="PTB123456" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="addr1"><i class="fa fa-exclamation-circle text-silver"></i> Addr1: </label>
                                            <div class="controls">
                                                <input name="addr1" type="text" class="form-control" required placeholder="Jalan Dato Jaafar 19">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="addr2"><i class="fa fa-exclamation-circle text-silver"></i> Addr2: </label>
                                            <div class="controls">
                                                <input name="addr2" type="text" class="form-control" required placeholder="Taman Mutiara Desaru" value="Taman Mutiara Desaru">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label class="control-label" for="postcode"><i class="fa fa-exclamation-circle text-silver"></i> Postcode: </label>
                                                <div class="controls">
                                                    <input name="postcode" type="text" class="form-control" required placeholder="81930" value="81930">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="city"><i class="fa fa-exclamation-circle text-silver"></i> City: </label>
                                                <div class="controls">
                                                    <input name="city" type="text" class="form-control" required placeholder="Bandar Penawar" value="Bandar Penawar">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label class="control-label" for="state"><i class="fa fa-exclamation-circle text-silver"></i> State: </label>
                                                <div class="controls">
                                                    <input name="state" type="text" class="form-control" required placeholder="Johor" value="Johor">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="country_code">Country:</label>
                                                <div class="controls">
                                                    <select name="country_code" class="form-control">
                                                        <?php
                                                        foreach ($countries as $key => $row) {
                                                            ?>
                                                            <option <?php echo ($key == 'MY') ? 'selected' : ''; ?> value="<?php echo $key; ?>"><?php echo $row; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <div class="pull-right">
                                                <button name="btnAdd" type="submit" class="btn btn-primary">Add House</button>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>