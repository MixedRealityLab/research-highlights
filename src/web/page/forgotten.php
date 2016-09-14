<?php

/**
 * Research Highlights engine
 *
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

$cTemplate = I::RH_Template();
$cUser = I::RH_User();

$cTemplate->startCapture();

?>
            <div class="container main">
                <div class="row main">

                    <!-- BEGIN FORGOTTEN STAGE -->
                    <form class="stage stage-forgotten collapse">
                        <div class="card card-container">
                            <div class="card-title">Retrieve account details</div>
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="text" class="form-control" name="username" id="username" placeholder="University username">
                            </div>
                            <br>
                            <div class="card-center"><em>or</em></div>
                            <br>
                            <div class="input-group">
                                <span class="input-group-addon">@</span>
                                <input type="email" class="form-control" name="email" id="email" placeholder="University email address">
                            </div>
                            <br>
                            <button class="btn btn-lg btn-primary btn-block" type="submit" id="verify">Request Password</button>
                        </div>
                    </form>
                    <!-- END FORGOTTEN STAGE -->

                </div>
            </div>
<?php

$cTemplate->set('header', true);
$cTemplate->set('body', $cTemplate->endCapture());

$cTemplate->add('javascript', URI_WEB . '/js/main' . EXT_JS);
$cTemplate->add('javascript', URI_WEB . '/js/forgotten' . EXT_JS);

print $cTemplate->load('2015');
