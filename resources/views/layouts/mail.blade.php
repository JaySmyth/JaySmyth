<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title></title>
        <style type="text/css">
            body { font-family: sans-serif; color: #000000; font-size: 13px; line-height: 18px; }
            .main-table { padding-left: 2%; padding-right: 2%;}
            .footer { margin-top: 2%; }
            h1 { font-size: 22px; margin-bottom: 16px;}
            h2 { font-size: 18px; margin-bottom: 12px;}
            h3 { font-size: 15px; margin-bottom: 10px;}
            thead { font-weight: bold;}
            .summary td { padding-top: 3px; padding-bottom: 3px;}
            .label { font-weight: bold; }
            .null { color: #AEAEAE; font-style: italic; }
            .table { border-left: solid 1px #A7A9AC; border-top: solid 1px #A7A9AC; color: #000;}
            .table thead th { padding-top: 12px; padding-bottom: 12px;}
            .table td, th { padding: 8px; border-right: solid 1px #A7A9AC;  border-bottom: solid 1px #A7A9AC;}
            .table thead { background: #E3E3E3;}
            .error {color: #DF0101;}
            .inserted {color: #00B050;}
            .updated {color:#f60;}
            .field-error { color: #DF0101; border: solid 1px #DF0101; font-weight: bold; font-style:italic; background: #F5A9A9;}
            .error-summary { color: #DF0101; }
            .company-name { font-size: 18px; font-weight: bold; font-style: italic;}
            .red { color: #D91B21;}
            .blue { color:#000080;}
            .comment { font-size: 18px; font-style: italic;}
            .warn {color:#FF0000; background-color:#f8b9b7;}
            .fade { color: #A7A9AC; font-style: italic;}
            .text-left { text-align: left;}
            .text-center { text-align: center;}
        </style>
    </head>
    <body>
        <table border="0" cellspacing="0" width="100%" class="main-table">
            <tr>
                <td valign="top">

                    @yield('content')

                    <table border="0" cellspacing="0" class="footer">
                        <tr><td>&nbsp;</td></tr>
                        <tr>
                            <td class="company-name"><span class="blue">IFS Global</span> <span class="red">Logistics</span></td>
                        </tr>
                        <tr>
                            <td>IFS Global Logistics Ltd</td>
                        </tr>
                        <tr>
                            <td>IFS Logistics Park</td>
                        </tr>
                        <tr>
                            <td>Seven Mile Straight</td>
                        </tr>
                        <tr>
                            <td>Antrim, BT41 4QE</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><b>Tel.</b> +44 2894 464211</td>
                        </tr>
                        <tr>
                            <td>www.ifsgroup.com</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>