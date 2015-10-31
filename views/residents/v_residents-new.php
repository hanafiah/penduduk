<div class="container-fluid">
    <div class="row">
        <div class="ltbody">
            <div class="page-header">
                <h2><i class="fa fa-user"></i> Residents <small>|  New</small></h2>
                Add new resident. Items marked <i class="fa fa-exclamation-circle text-silver"></i> are required.
            </div>
            <div class="form">
                <form method="POST" name="frmNew">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title">Resident Details</div>
                                </div>
                                <div class="panel-body">
                                    <fieldset>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label class="control-label" for="first_name"><i class="fa fa-exclamation-circle text-silver"></i> First Name: </label>
                                                <input name="first_name" type="text" class="form-control" required placeholder="Muhammad">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="control-label" for="last_name"><i class="fa fa-exclamation-circle text-silver"></i> Last Name: </label>
                                                <input name="last_name" type="text" class="form-control" required placeholder="Abdullah" value="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <label for="gender">Gender:</label>
                                                <select name="gender" class="form-control">
                                                    <option value="M">Male</option>
                                                    <option value="F">Female</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="control-label" for="nric"><i class="fa fa-exclamation-circle text-silver"></i> Nric: </label>
                                                <input name="nric" type="text" class="form-control" required placeholder="790121010000">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label class="control-label" for="dob"><i class="fa fa-exclamation-circle text-silver"></i> DOB: </label>
                                                <input name="dob" type="text" class="form-control" required placeholder="" value="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label class="control-label" for="phone"><i class="fa fa-exclamation-circle text-silver"></i> Phone: </label>
                                                <input name="phone" type="text" class="form-control" required placeholder="019-1234567, 013-1234567" value="">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label class="control-label" for="email"><i class="fa fa-exclamation-circle text-silver"></i> Email: </label>
                                                <input name="email" type="text" class="form-control" required email placeholder="saya@example.com" value="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-8">
                                                <label class="control-label" for="facebook"><i class="fa fa-exclamation-circle text-silver"></i> Facebook: </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">https://www.facebook.com/</span>
                                                    <input name="facebook" type="text" class="form-control"  placeholder="hanafiah.yahya" value="" >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="occupation"><i class="fa fa-exclamation-circle text-silver"></i> Occupation: </label>
                                            <input name="occupation" type="text" class="form-control" required placeholder="Guru">
                                        </div>
                                        <?php
                                        if ($house_id !== FALSE) {
                                            ?>
                                            <hr>
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label class="control-label" for="house"><i class="fa fa-exclamation-circle text-silver"></i> House: </label>
                                                    <input name="house" type="text" class="form-control" required placeholder="24JDJ19" disabled value="<?php echo $house->house_no . ' ' . $house->addr1; ?>">
                                                    <div class="checkbox <?php echo isset($owner->id) ? 'disabled' : ''; ?>">
                                                        <label><input <?php echo isset($owner->id) ? 'disabled checked' : ''; ?> name="is_owner" type="checkbox" value="1"> house owner</label>&nbsp;
                                                    </div>
                                                    <div class="checkbox <?php echo isset($hof->id) ? 'disabled' : ''; ?>">
                                                        <label><input <?php echo isset($hof->id) ? 'disabled checked' : ''; ?> name="is_hof" type="checkbox" value="1"> head of family/tenant</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            if (isset($owner->id) || isset($hof->id)) {
                                                ?>
                                                <div class='row'>
                                                    <div class='col-md-12'>
                                                        <h4>Relation with Owner/ Head of Family</h4>
                                                    </div>
                                                    <div class='form-group col-md-6 '>
                                                        <label class="control-label" for="name"><i class="fa fa-exclamation-circle text-silver"></i> Name: </label>
                                                        <select name="person_id" class='form-control'>
                                                            <?php
                                                            if (isset($owner->id)) {
                                                                echo '<option value="' . $owner->id . '" >' . $owner->first_name . ' ' . $owner->last_name . '</option>';
                                                            }

                                                            if (isset($hof->id) && (!isset($owner->id) || (isset($owner->id) && ($owner->id != $hof->id)))) {
                                                                echo '<option value="' . $hof->id . '" >' . $hof->first_name . ' ' . $hof->last_name . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class='form-group col-md-2 '>
                                                        <label class="control-label" for="relation"><i class="fa fa-exclamation-circle text-silver"></i> Relation</label>
                                                        <select name="relationship_type_id" class='form-control'>
                                                            <?php
                                                            foreach ($relationship_type as $row) {
                                                                ?>
                                                                <option value="<?php echo $row->id; ?>" ><?php echo $row->description; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class='form-group col-md-2 '>
                                                        <label class="control-label" for="Type"><i class="fa fa-exclamation-circle text-silver"></i> Is My</label>
                                                        <select name="person1_roles_id" class='form-control'>
                                                            <?php
                                                            foreach ($roles as $row) {
                                                                ?>
                                                                <option value="<?php echo $row->id; ?>" ><?php echo $row->description; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class='form-group col-md-2 '>
                                                        <label class="control-label" for="Type"><i class="fa fa-exclamation-circle text-silver"></i> I'm His/her</label>
                                                        <select name="person2_roles_id" class='form-control'>
                                                            <?php
                                                            foreach ($roles as $row) {
                                                                ?>
                                                                <option value="<?php echo $row->id; ?>" ><?php echo $row->description; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <div class="form-actions">
                                            <div class="pull-right">
                                                <button name="btnAdd" type="submit" class="btn btn-primary">Add Resident</button>
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