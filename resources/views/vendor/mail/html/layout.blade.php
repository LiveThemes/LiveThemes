<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<style>
@media only screen and (max-width: 600px) {
.inner-body {
width: 100% !important;
}

.footer {
width: 100% !important;
}
}

@media only screen and (max-width: 500px) {
.button {
width: 100% !important;
}
}
</style>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<!-- Email Body -->
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0">
<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">

<!-- Custom Header -->
<tr>
<td>
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td width="40" style="padding: 20px; background-color: #edf2f7;">
<a href="http://www.livethemes.co" style="display: inline-block;">
	<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" width="40" height="26" viewBox="0 0 29.93 19.53">
        <path d="M20.64,10.67a3,3,0,0,1,1-2.33l6.9-6.26a2.15,2.15,0,0,1,1.31-.6,1.9,1.9,0,0,1,2,1.42,2.31,2.31,0,0,1-.18,1.35c-.24.53-.5,1-.77,1.56-.9,1.69-1.83,3.37-2.85,5a15.77,15.77,0,0,1-1.49,2.05A3.55,3.55,0,0,1,24.5,14a3.36,3.36,0,0,1-3.82-2.88C20.66,11,20.65,10.83,20.64,10.67Z" transform="translate(-2 -1.47)"/>
        <path d="M12.41,14.7c.35.25.66.49,1,.71s.62.39.93.57a.8.8,0,0,0,.34.11.58.58,0,0,0,.62-.4,9.36,9.36,0,0,1,.46-1,3.53,3.53,0,0,1,2.58-1.8c.56-.1,1.13-.13,1.7-.19a.15.15,0,0,1,.12.07A4.36,4.36,0,0,0,22.82,15a.14.14,0,0,1,.09.08,7.13,7.13,0,0,1-.24,2.6,4.7,4.7,0,0,1-3.6,3.21,5.86,5.86,0,0,1-3.16-.13,4.57,4.57,0,0,1-2.74-2.46,7.9,7.9,0,0,1-.73-2.9C12.43,15.15,12.42,14.94,12.41,14.7Z" transform="translate(-2 -1.47)"/>
        <polygon points="0 0.53 0 4.53 20.58 4.53 24 0.53 0 0.53"/>
        <polygon points="9 14.53 0 14.53 0 18.53 8.37 18.53 8.39 18.42 9 14.53"/>
        <polygon points="0 7.53 0 11.53 13.76 11.53 13.84 11.37 17 7.53 0 7.53"/>
    </svg>
</a>
</td>
<td width="99%" style="padding: 20px; background-color: #edf2f7; font-size: 26px; color: #212529; font-weight: 500;">
	Live Themes
</td>
</tr>
</table>
</td>
</tr>

<!-- Body content -->
<tr>
<td class="content-cell">
{{ Illuminate\Mail\Markdown::parse($slot) }}

{{ $subcopy ?? '' }}
</td>
</tr>
</table>
</td>
</tr>

{{ $footer ?? '' }}
</table>
</td>
</tr>
</table>
</body>
</html>
