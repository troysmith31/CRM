<?php
use ChurchCRM\data\Countries;
use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;

// Set the page title and include HTML header
$sPageTitle = gettext("Family Registration");
require(SystemURLs::getDocumentRoot(). "/Include/HeaderNotLoggedIn.php");
?>

  <div class="register-box" style="width: 600px;">
    <div class="register-logo">
      <?php
        $headerHTML = '<b>Church</b>CRM';
        $sHeader = SystemConfig::getValue("sHeader");
        $sChurchName = SystemConfig::getValue("sChurchName");
        if (!empty($sHeader)) {
          $headerHTML = html_entity_decode($sHeader, ENT_QUOTES);
        } else if(!empty($sChurchName)) {
            $headerHTML = $sChurchName;
        }
      ?>
      <a href="<?= SystemURLs::getRootPath() ?>/"><?= $headerHTML ?></a>
    </div>

    <div class="register-box-body">
        <form id="example-advanced-form" action="#">
            <h3>Family</h3>
            <fieldset>
                <legend>Account Information</legend>

                <div class="form-group has-feedback">
                    <input name="familyName" type="text" class="form-control" placeholder="<?= gettext('Family Name') ?>" required>
                    <span class="fa fa-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input name="familyAddress1" class="form-control" placeholder="<?= gettext('Address') ?>" required>
                    <span class="fa fa-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <div class="row">
                        <div class="col-lg-6">
                            <input name="familyCity" class="form-control" placeholder="<?= gettext('City') ?>" required value="<?= SystemConfig::getValue('sDefaultCity') ?>">
                        </div>
                        <div class="col-lg-6">
                            <input name="familyState" class="form-control" placeholder="<?= gettext('State') ?>" required value="<?= SystemConfig::getValue('sDefaultState') ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <div class="row">
                        <div class="col-lg-3">
                            <input name="familyZip" class="form-control" placeholder="<?= gettext('Zip') ?>" required>
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
                    <input name="familyHomePhone" class="form-control" placeholder="<?= gettext('Home Phone') ?>" data-inputmask='"mask": "<?= SystemConfig::getValue('sPhoneFormat')?>"' data-mask>
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
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <button type="submit" class="btn bg-olive"><?= gettext('Next'); ?></button>
                    </div>
                    <!-- /.col -->
                </div>
            </fieldset>

            <h3>Profile</h3>
            <fieldset>
                <legend>Profile Information</legend>

                <label for="name-2">First name *</label>
                <input id="name-2" name="name" type="text" class="required">
                <label for="surname-2">Last name *</label>
                <input id="surname-2" name="surname" type="text" class="required">
                <label for="email-2">Email *</label>
                <input id="email-2" name="email" type="text" class="required email">
                <label for="address-2">Address</label>
                <input id="address-2" name="address" type="text">
                <label for="age-2">Age (The warning step will show up if age is less than 18) *</label>
                <input id="age-2" name="age" type="text" class="required number">
                <p>(*) Mandatory</p>
            </fieldset>

            <h3>Warning</h3>
            <fieldset>
                <legend>You are to young</legend>

                <p>Please go away ;-)</p>
            </fieldset>

            <h3>Finish</h3>
            <fieldset>
                <legend>Terms and Conditions</legend>

                <input id="acceptTerms-2" name="acceptTerms" type="checkbox" class="required"> <label for="acceptTerms-2">I agree with the Terms and Conditions.</label>
            </fieldset>
        </form>
    </div>
    <!-- /.form-box -->
  </div>
    <script nonce="<?= SystemURLs::getCSPNonce() ?>" >
        $(document).ready(function() {
            var form = $("#example-advanced-form").show();

            form.steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slideLeft",
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    // Allways allow previous action even if the current form is not valid!
                    if (currentIndex > newIndex)
                    {
                        return true;
                    }
                    // Forbid next action on "Warning" step if the user is to young
                    if (newIndex === 3 && Number($("#age-2").val()) < 18)
                    {
                        return false;
                    }
                    // Needed in some cases if the user went back (clean up)
                    if (currentIndex < newIndex)
                    {
                        // To remove error styles
                        form.find(".body:eq(" + newIndex + ") label.error").remove();
                        form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                    }
                    form.validate().settings.ignore = ":disabled,:hidden";
                    return form.valid();
                },
                onStepChanged: function (event, currentIndex, priorIndex)
                {
                    // Used to skip the "Warning" step if the user is old enough.
                    if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
                    {
                        form.steps("next");
                    }
                    // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
                    if (currentIndex === 2 && priorIndex === 3)
                    {
                        form.steps("previous");
                    }
                },
                onFinishing: function (event, currentIndex)
                {
                    form.validate().settings.ignore = ":disabled";
                    return form.valid();
                },
                onFinished: function (event, currentIndex)
                {
                    alert("Submitted!");
                }
            }).validate({
                errorPlacement: function errorPlacement(error, element) { element.before(error); },
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
require(SystemURLs::getDocumentRoot(). "/Include/FooterNotLoggedIn.php");
