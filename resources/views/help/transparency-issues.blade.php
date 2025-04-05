@extends('layouts.app')

@section('content')

<div class="container pt-3">

	<h1>Transparency Issues</h1>

	<p class="text-primary">Friday 9th October</p>

	<p>Unfortunately due to some changes to Ableton 10, some of the older themes on Live Themes have displayed some bugs. This is down to the transparency on some elements within those themes.</p>

	<p>Previously in the editor you were able to edit the transparency of any element which may have looked good there however showed as a bit buggy within Ableton - not great!</p>

	<p>So from today transparency (alpha) controls have been restricted to just certain elements within the interface where it shouldn't cause issues (these elements have transparency in the original themes from Ableton)</p>

	<p>To make old themes compatiable, they will be automatically upgraded. This means any element that has transparency in it that shouldn't have will be reset. You'll see this in the themes with certain colours altering or the brightness of certain elements changing.</p>

	<p><strong>If your theme is one of the ones upgraded,</strong> you can go into it and edit it to change it to how you like</p>

	<p><strong>If you've remix a theme that has been updated,</strong> it'll display the message but once you save it'll fix itself.</p>

</div>

@endsection
