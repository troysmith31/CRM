<?php

use ChurchCRM\data\Countries;
use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;

// Set the page title and include HTML header
$sPageTitle = gettext("Family Registration");
require(SystemURLs::getDocumentRoot() . "/Include/HeaderNotLoggedIn.php");
?>
    <style type="text/css">
        .wizard .content {
            min-height: 100px;
        }

        .wizard .content > .body {
            width: 100%;
            height: auto;
            padding: 15px;
            position: relative;
        }
    </style>
    <div class="register-box" style="width: 600px;">
        <div class="register-logo">
            <?php
            $headerHTML = '<b>Church</b>CRM';
            $sHeader = SystemConfig::getValue("sHeader");
            $sChurchName = SystemConfig::getValue("sChurchName");
            if (!empty($sHeader)) {
                $headerHTML = html_entity_decode($sHeader, ENT_QUOTES);
            } else if (!empty($sChurchName)) {
                $headerHTML = $sChurchName;
            }
            ?>
            <a href="<?= SystemURLs::getRootPath() ?>/"><?= $headerHTML ?></a>
        </div>

        <div class="box-body">
            <form id="example-advanced-form" action="#">

                <h3>Welcome</h3>
                <fieldset>
                    <div><?= SystemConfig::getValue("sSelfRegistrationTNC")?></div>

                    <input id="acceptTerms-2" name="acceptTerms" type="checkbox" class="required"> <label
                            for="acceptTerms-2"><?= gettext("I agree with the Terms and Conditions.")?></label>
                </fieldset>

                <h3>Family</h3>
                <fieldset>
                    <div class="form-group has-feedback">
                        <input name="familyName" type="text" class="form-control"
                               placeholder="<?= gettext('Family Name') ?>" required>
                        <span class="fa fa-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input name="familyAddress1" class="form-control" placeholder="<?= gettext('Address') ?>"
                               required>
                        <span class="fa fa-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <div class="row">
                            <div class="col-lg-6">
                                <input name="familyCity" class="form-control" placeholder="<?= gettext('City') ?>"
                                       required value="<?= SystemConfig::getValue('sDefaultCity') ?>">
                            </div>
                            <div class="col-lg-6">
                                <input name="familyState" class="form-control" placeholder="<?= gettext('State') ?>"
                                       required value="<?= SystemConfig::getValue('sDefaultState') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <div class="row">
                            <div class="col-lg-3">
                                <input name="familyZip" class="form-control" placeholder="<?= gettext('Zip') ?>"
                                       required>
                            </div>
                            <div class="col-lg-9">
                                <select id="familyCountry" name="familyCountry" class="form-control select2">
                                    <?php foreach (Countries::getNames() as $county) {
                                    ?>
                                    <option value="<?= $county ?>" <?php if (SystemConfig::getValue('sDefaultCountry') == $county) {
                                        echo 'selected';
                                    } ?>><?= gettext($county) ?>
                                        <?php
                                        } ?>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <input name="familyHomePhone" class="form-control" placeholder="<?= gettext('Home Phone') ?>"
                               data-inputmask='"mask": "<?= SystemConfig::getValue('sPhoneFormat') ?>"' data-mask>
                        <span class="fa fa-phone form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <label><?= gettext('How many people are in your family') ?></label>
                        <select name="familyCount" class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option selected>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                        </select>
                    </div>
                    <div class="form-group has-feedback">
                        <hr/>
                    </div>
                    <div class="form-group has-feedback">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="familyPrimaryChurch" checked>&nbsp;
                                <?= gettext('This will be my primary church.') ?>
                            </label>
                        </div>
                    </div>
                </fieldset>

                <h3>Members</h3>
                <fieldset>
                    <?php for ($x = 1; $x <= 4; $x++) { ?>
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">
                                    <?= gettext("Family Member") . " #" . $x ?>
                                </h4>
                            </div>
                            <div class="box-body">
                                <div class="form-group has-feedback">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <select name="memberRole-<?= $x ?>" class="form-control">
                                                <?php
                                                switch ($x) {
                                                    case 1:
                                                        $defaultRole = SystemConfig::getValue('sDirRoleHead');
                                                        break;
                                                    case 2:
                                                        $defaultRole = SystemConfig::getValue('sDirRoleSpouse');
                                                        break;
                                                    default:
                                                        $defaultRole = SystemConfig::getValue('sDirRoleChild');
                                                        break;
                                                }

                                                foreach ($familyRoles as $role) { ?>
                                                    <option value="<?= $role->getOptionId() ?>" <?php if ($role->getOptionId() == $defaultRole) {
                                                        echo "selected";
                                                    } ?>><?= $role->getOptionName() ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <select name="memberGender-<?= $x ?>" class="form-control">
                                                <option value="1"><?= gettext('Male') ?></option>
                                                <option value="2"><?= gettext('Female') ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group has-feedback">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input name="memberFirstName-<?= $x ?>" class="form-control" maxlength="50"
                                                   placeholder="<?= gettext('First Name') ?>" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <input name="memberLastName-<?= $x ?>" class="form-control" value=""
                                                   maxlength="50"
                                                   placeholder="<?= gettext('Last Name') ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group has-feedback">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-envelope"></i>
                                        </div>
                                        <input name="memberEmail-<?= $x ?>" class="form-control" maxlength="50"
                                               placeholder="<?= gettext('Email') ?>">
                                    </div>
                                </div>
                                <div class="form-group has-feedback">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-phone"></i>
                                                </div>
                                                <select name="memberPhoneType-<?= $x ?>" class="form-control">
                                                    <option value="mobile"><?= gettext('Mobile') ?></option>
                                                    <option value="home"><?= gettext('Home') ?></option>
                                                    <option value="work"><?= gettext('Work') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input name="memberPhone-<?= $x ?>" class="form-control" maxlength="30"
                                                       data-inputmask='"mask": "<?= SystemConfig::getValue('sPhoneFormat') ?>"'
                                                       data-mask
                                                       placeholder="<?= gettext('Phone') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group has-feedback">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-birthday-cake"></i>
                                                </div>
                                                <input type="text" class="form-control inputDatePicker"
                                                       name="memberBirthday-<?= $x ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label>
                                                <input type="checkbox"
                                                       name="memberHideAge-<?= $x ?>">&nbsp; <?= gettext('Hide Age') ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    } ?>
                </fieldset>

                <h3>Confirm</h3>
                <fieldset>
                    <legend>You are to young</legend>

                    <p>Please go away ;-)</p>
                </fieldset>

                <h3>Complete</h3>
                <fieldset>
                    <legend>Terms and Conditions</legend>

                    <input id="acceptTerms-2" name="acceptTerms" type="checkbox" class="required"> <label
                            for="acceptTerms-2">I agree with the Terms and Conditions.</label>
                </fieldset>
            </form>
        </div>
        <!-- /.form-box -->
    </div>
    <script nonce="<?= SystemURLs::getCSPNonce() ?>">
        $(document).ready(function () {
            var form = $("#example-advanced-form").show();

            form.steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slideLeft",
                onStepChanging: function (event, currentIndex, newIndex) {
                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex) {
                        return true;
                    }
                    // Forbid next action on "Warning" step if the user is to young
                    if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                        return false;
                    }
                    // Needed in some cases if the user went back (clean up)
                    if (currentIndex < newIndex) {
                        // To remove error styles
                        form.find(".body:eq(" + newIndex + ") label.error").remove();
                        form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                    }
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                },
                onStepChanged: function (event, currentIndex, priorIndex) {
                    // Used to skip the "Warning" step if the user is old enough.
                    if (currentIndex === 2 && Number($("#age-2").val()) >= 18) {
                        form.steps("next");
                    }
                    // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                    if (currentIndex === 2 && priorIndex === 3) {
                        form.steps("previous");
                    }
                },
                onFinishing: function (event, currentIndex) {
                    form.validate().settings.ignore = ":disabled";
                    return form.valid();
                },
                onFinished: function (event, currentIndex) {
                    alert("Submitted!");
                }
            }).validate({
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                },
                rules: {
                    confirm: {
                        equalTo: "#password-2"
                    }
                }
            });
        });


    </script>


    <script src="<?= SystemURLs::getRootPath() ?>/skin/external/jquery.steps/jquery.steps.min.js"></script>
    <script src="<?= SystemURLs::getRootPath() ?>/skin/external/jquery-validation/jquery.validate.min.js"></script>

<?php
// Add the page footer
require(SystemURLs::getDocumentRoot() . "/Include/FooterNotLoggedIn.php");
