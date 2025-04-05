@extends('layouts.app')

@section('pageClass', 'body body-contact')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 pt-5">

        	<h1>FAQs</h1>

            <div class="mb-5">

                <h3>Will these themes work in Ableton 11?</h3>

                <p>Early tests show are they do work in Ableton 11 but are not fully optimised for it. As soon as the final version of Ableton 11 gets released we'll get working on a 'Download for Ableton 11' option.</p>

            </div>

            <div class="mb-5">

                <h3>I can't find {insert element here} to customise</h3>

                <p>There are lots of elements you can customise within a theme and unfortunately we haven't been able to map all of them to the preview yet, however you are able to select from all the available elements from the top left hand corner of the editor.</p>

            </div>

            <div class="mb-5">

                <h3>I am getting an error telling me the theme needs a newer version of Ableton but I'm already running Ableton 10</h3>

                <p>Ableton push out minor updates quite regularly which do sometimes change the format of the theme files. For this reason we need to keep the site up to date with the latest changes so if you are seeing this error message it is because you haven't got the latest update. If you have Ableton 10 then all minor updates are free and should be done automatically, if they arn't check your preferences on how to update manually.</p>

            </div>

            <div class="mb-5">

                <h3>Can I put images or gradient colours in my theme?</h3>

                <p>Unfortunately not, Ableton only allows you to change specific colours and there is no option to use an image or gradient colour within the theme files.</p>

            </div>

        </div>
    </div>
</div>

@endsection
