<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#fafafa; padding:100px 0; font-family:Poppins, Arial, sans-serif;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" style="font-size:64px; font-weight:900; padding-bottom:30px;">
                        OLog
                    </td>
                </tr>
            </table>

            <table width="600" cellpadding="0" cellspacing="0" 
                style="background-color:white; border-radius:20px; padding:30px 40px;">

                <tr>
                    <td>
                        <h3 style="margin:0;">Hello, {{ $user->name ?? 'Unknown' }}</h3>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p style="color:gray; margin-top:5px; margin-bottom:30px;">
                            You are receiving this email because we received a password reset request for your account.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td align="center">
                        <a href="{{ $url??'' }}"
                           style="background-color:black; color:white; padding:10px 20px; border-radius:8px; font-weight:700; text-decoration:none; display:inline-block;">
                            Reset Password
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p style="color:gray; margin-top:30px; margin-bottom:0;">
                            This password reset link will expire in 60 minutes.
                        </p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p style="color:rgb(255, 187, 0); margin-top:10px;">
                            If you did not request a password reset, <b>Please do not take any action.</b>
                        </p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div style="border:1px solid rgb(217,217,217); margin:20px 0;"></div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p style="color:gray; margin:0;">
                            If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
                            <br><br>
                            <a href="{{ $url??'' }}" style="color:blue; word-break:break-all;">
                                {{ $url??'' }}
                            </a>
                        </p>
                    </td>
                </tr>

            </table>

            <table width="600" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" style="padding-top:30px; color:black;">
                        © 2026 OLog. All rights reserved.
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>