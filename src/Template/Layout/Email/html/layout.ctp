<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">

    <title></title>

    <style>
        p a {
            color: #0087ff;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        *[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
        }

        .button-a {
            transition: all 100ms ease-in;
            background: #444444;
            color: #fff !important;
            border: 15px solid #444444;
            font-family: sans-serif;
            font-size: 13px;
            line-height: 1.1;
            text-align: center;
            text-decoration: none;
            display: block;
            border-radius: 3px;
            font-weight: bold;
            margin: 20px 0px;
        }

        .button-a:hover {
            background: #555555 !important;
            border-color: #555555 !important;
        }
    </style>
</head>

<body>
    <table class="main-body" style="box-sizing: border-box; min-height: 150px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; width: 100%; height: 100%; background-color: rgb(234, 236, 237);" width="100%" height="100%" bgcolor="rgb(234, 236, 237)">
        <tbody style="box-sizing: border-box;">
            <tr class="row" style="box-sizing: border-box; vertical-align: top;" valign="top">
                <td class="main-body-cell" style="box-sizing: border-box; background-color: #222222;" bgcolor="#222222">
                    <table class="container" style="box-sizing: border-box; font-family: Helvetica, serif; min-height: 150px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; margin-top: auto; margin-right: auto; margin-bottom: auto; margin-left: auto; height: 0px; width: 90%; max-width: 550px;" width="90%" height="0">
                        <tbody style="box-sizing: border-box;">
                            <tr style="box-sizing: border-box;">
                                <td class="container-cell" style="box-sizing: border-box; vertical-align: top; font-size: medium; padding-bottom: 50px; padding: 15px 1px 10px 1px;" valign="top">
                                    <img id="itpp" src="<?= $this->Url->build('/img/mysetup_logo.png', true) ?>" style="box-sizing: border-box; color: black; max-width: 200px; margin: 0 0 10px 5px;">
                                    <table class="list-item" style="box-sizing: border-box; height: auto; width: 100%; margin-top: 0px; margin-right: auto; margin-bottom: 10px; margin-left: auto; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; margin: 0 auto 0 auto;" width="100%">
                                        <tbody style="box-sizing: border-box;">
                                            <tr style="box-sizing: border-box;">
                                                <td class="list-item-cell" style="box-sizing: border-box; background-color: rgb(255, 255, 255); border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; overflow-x: hidden; overflow-y: hidden; padding-top: 0px; padding-right: 0px; padding-bottom: 0px; padding-left: 0px;" bgcolor="rgb(255, 255, 255)">
                                                    <table class="list-item-content" style="box-sizing: border-box; border-collapse: collapse; margin-top: 0px; margin-right: auto; margin-bottom: 0px; margin-left: auto; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; height: 150px; width: 100%;" width="100%" height="150">
                                                        <tbody style="box-sizing: border-box;">
                                                            <tr class="list-item-row" style="box-sizing: border-box;">
                                                                <td class="list-cell-right" style="box-sizing: border-box; width: 70%; color: rgb(111, 119, 125); font-size: 13px; line-height: 20px; padding-top: 10px; padding-right: 20px; padding-bottom: 0px; padding-left: 20px; padding: 20px 20px 15px 20px;" width="70%">
                                                                    <h1 class="card-title" style="box-sizing: border-box; font-size: 25px; font-weight: 700; color: rgb(68, 68, 68); font-family: Arial Black, Gadget, sans-serif; text-align: left; line-height: 28px;">
                                                                        <?= __('Hello') ?> <?= $recipient_name ?>,
                                                                    </h1>
                                                                    <p class="card-text" style="box-sizing: border-box; font-family: Arial Black, Gadget, sans-serif; margin: 10px 0 10px 0;">
                                                                        <?= $this->fetch('content') ?>
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="footer" style="box-sizing: border-box; margin-top: 50px; color: rgb(152, 156, 165); text-align: center; font-size: 11px; padding-top: 5px; padding-right: 5px; padding-bottom: 5px; padding-left: 5px; width: 100%; margin: 0 0 0 0;" width="100%" align="center">
                                        <tbody style="box-sizing: border-box;">
                                            <tr style="box-sizing: border-box;">
                                                <td class="footer-cell" style="box-sizing: border-box;">
                                                    <div class="c2577" style="box-sizing: border-box; padding-top: 10px; padding-right: 10px; padding-bottom: 10px; padding-left: 10px; padding: 0 10px 0 10px;">
                                                    </div>
                                                    <p class="footer-info" style="box-sizing: border-box; color: #ffffff; font-family: Arial, Helvetica, sans-serif;">mysetup.co
                                                        <br style="box-sizing: border-box;">
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>