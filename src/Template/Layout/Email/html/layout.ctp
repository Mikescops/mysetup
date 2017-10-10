<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">

    <title></title>

    <style>
        html, body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            mso-line-height-rule: exactly;
        }

        * {
            outline: none;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        div[style*="margin: 16px 0"] {
            margin:0 !important;
        }

        table, td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }

        table table table {
            table-layout: auto;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        *[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
        }

        .x-gmail-data-detectors, .x-gmail-data-detectors *, .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
        }

        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }
        img.g-img + div {
            display:none !important;
        }

        .button-link {
            text-decoration: none !important;
        }

        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            .email-container {
                min-width: 375px !important;
            }
        }

        .button-td, .button-a {
            transition: all 100ms ease-in;
            background: #444444;
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
        .button-td:hover, .button-a:hover {
            background: #555555 !important;
            border-color: #555555 !important;
        }
        @media screen and (max-width: 600px) {
            .email-container p {
                font-size: 17px !important;
                line-height: 22px !important;
            }
        }

        .center {
            width: 100%;
            background: #222222;
            text-align: left;
        }

        .summary {
            display: none;
            font-size: 1px;
            line-height: 1px;
            max-height: 0px;
            max-width: 0px;
            opacity: 0;
            overflow: hidden;
            mso-hide: all;
            font-family: sans-serif;
        }
    </style>

    <!--[if gte mso 9]>
        <xml>
          <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
         </o:OfficeDocumentSettings>
        </xml>
    <![endif]-->
</head>

<body width="100%" bgcolor="#222222">
    <center class="center">
        <div class="summary">
            Hey you, this is an email from mySetup.co !
        </div>
        <div style="max-width: 600px; margin: auto;" class="email-container">
            <!--[if mso]>
                <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" width="600" align="center">
                    <tr>
                        <td>
            <![endif]-->

            <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
                <tr>
                    <td style="padding: 20px 0; text-align: center">
                        <img src="https://mysetup.co/img/mySetup_logo.svg" aria-hidden="true" width="200" height="50" border="0" style="height: auto; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                    </td>
                </tr>
            </table>
            <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px; margin-top: 50px !important">
                <tr>
                    <td bgcolor="#222222">

                        <?= $this->fetch('content') ?>

                    </td>
                </tr>
            </table>
            <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">
                <tr>
                    <td style="padding: 30px 10px; width: 100%; font-size: 12px; font-family: sans-serif; line-height: 18px; text-align: center; color: #888888;" class="x-gmail-data-detectors">
                        mySetup.co
                        <br />
                        88 Boulevard Lahitolle, 18000 Bourges, France
                        <br />
                        <br />
                        <img src="https://mysetup.co/img/logo_footer.svg" alt="mySetup.co's Support" style="height: 80px; background-color: #ffffff; border-radius: 50%;" >
                        <br />
                    </td>
                </tr>
            </table>

            <!--[if mso]>
                        </td>
                    </tr>
                </table>
            <![endif]-->
        </div>
    </center>
</body>
</html>
