@extends('layouts.app')

@section('pageClass', 'body body-editor')

@section('pagestyles')
	<link href="{{ mix('css/editor.css') }}" rel="stylesheet">
@endsection

@section('content')

<div id="editor" class="editor" v-cloak>

	<div class="toolbar">

		<button v-on:click="welcome.show = true; welcome.section = 'New'" v-bind:disabled="loading || saving">
			<i class="fas fa-file"></i> New
		</button>

		<button v-on:click="welcome.show = true; welcome.section = 'Recent'" v-bind:disabled="loading || saving">
			<i class="fas fa-folder-open"></i> Open
		</button>

		<button v-on:click="dialog.save = true" v-bind:class="{ active: changes }" v-bind:disabled="loading || saving">
			<i class="fas fa-save"></i> Save
		</button>

		<button v-on:click="downloadTheme()" v-bind:disabled="loading || saving || !theme.id">
			<i class="fas fa-file-download"></i> Download
		</button>

		<button v-bind:disabled="loading || saving || !theme.id" v-on:click="viewTheme()">
			<i class="fas fa-eye"></i> View
		</button>

		<button v-bind:disabled="loading || saving || !theme.id" v-on:click="dialog.share = true">
			<i class="fas fa-share"></i> Share
		</button>

		<span class="seperator"></span>

		<button v-on:click="undo" v-bind:disabled="historyPosition == -1">
			<i class="fas fa-undo"></i> Undo
		</button>

		<button v-on:click="redo()" v-bind:disabled="historyPosition == (history.length - 1)">
			<i class="fas fa-redo"></i> Redo
		</button>

		<span class="seperator"></span>

		<button v-on:click="zoomOut()" v-bind:disabled="zoom.current.value == 0.25">
			<i class="fas fa-minus m-0"></i>
		</button>

		<button class="dropdown" v-on:click="zoom.show = !zoom.show" v-if="currentZoomLevel">
			<i class="fas fa-search-plus"></i> @{{ currentZoomLevel.name }}
			<ul v-if="zoom.show">
				<li v-for="l in zoom.levels" v-html="l.name" v-on:click.stop="zoomPreview(l.value)"></li>
			</ul>
		</button>

		<button v-on:click="zoomIn()" v-bind:disabled="zoom.current.value == 3">
			<i class="fas fa-plus m-0"></i>
		</button>

		<span class="seperator"></span>

		<span class="toolbar-alert" v-if="theme && theme.updated">
			<i class="fas fa-exclamation-triangle pr-2"></i>This theme has been updated & may look slightly different - <a href="/help/transparency-issues" target="_blank">Read More</a>
		</span>

	</div>

	<div class="main">

		<div class="sidebar">

			<div class="element" :class="{ choosing: choosingElement }">

				<div class="title">
					<i class="fas fa-mouse-pointer"></i>Element
				</div>

				<div class="element-chooser" :class="{ choosing: choosingElement }" v-on:click="choosingElement = !choosingElement" v-if="currentElement">
					<h3 v-html="currentElement.element"></h3>
					<h4>
						<span v-html="currentElement.name"></span>
					</h4>
					<i class="fal fa-eye" v-bind:class="{ highlight: highlightElement }" v-on:click.stop="highlightElement = !highlightElement" title="Highlight Element"></i>
					<i class="fal fa-chevron-down"></i>
				</div>

				<div class="content" v-if="currentElement && !choosingElement">

					<p v-if="currentElement.description" v-html="currentElement.description"></p>

					<div v-else>
						<p>This element is not available in the preview yet but you can still edit it for your theme.</p>
					</div>

					<div class="related" v-if="currentElement.related">
						<h6>Related</h6>
						<span v-for="r in currentElement.related" v-on:click="selectElement(r.element)">
							<span v-bind:style="{ 'background-color': elementColour( theme.elements[r.element] ) }"></span>@{{ theme.elements[r.element].name }}
						</span>
					</div>

				</div>

			</div>

			<div class="element-list" v-if="theme && choosingElement">
				<div class="element-list-search">
					<i class="fal fa-search"></i>
					<input type="text" v-model="elementSearch" placeholder="Search">
				</div>
				<div class="element-list-inner">
					<ul>
			            <li v-for="e in filteredElements" v-if="!e.value" v-on:click="currentElement = e; choosingElement = false; elementSearch = ''" v-bind:title="e.element + (e.name != e.element ? ' (' + e.name + ')' : '')">
			                <span class="colour" v-bind:style="{ 'background-color': elementColour(e) }"></span>
			                <span class="element" v-html="e.element"></span>
			                <span class="name" v-html="e.name != e.element ? '(' + e.name + ')' : ''"></span>
			                <i v-if="!e.description" class="fas fa-eye-slash text-muted pl-2" title="Preview not available for this element"></i>
			            </li>
			        </ul>
			    </div>
			</div>

			<div class="picker" v-if="!choosingElement">

				<div class="title">
					<i class="fas fa-eye-dropper"></i>Picker
				</div>

				<colour-picker :key="paletteUpdate" :new-mode="pickerMode" :current-element="currentElement" v-if="currentElement" v-on:change="updateColour($event)" v-on:mode="pickerMode = $event"></colour-picker>

				<div class="menu">
					<i class="fas fa-bars"></i>
					<ul>
						<li v-on:click="addToPallete(currentElement)">
							<i class="far fa-plus"></i>Add to Palette
						</li>
						<li v-on:click="invertColour(currentElement)">
							<i class="far fa-repeat-1-alt"></i>Invert Current Colour
						</li>
						<li v-on:click="invertTheme()">
							<i class="fas fa-repeat-alt"></i>Invert Whole Theme
						</li>
					</ul>
				</div>

			</div>

			<div class="palette" v-if="!choosingElement">

				<div class="title">
					<i class="fas fa-tint"></i>Palette
				</div>

				<div class="content" v-cloak>
					<h4>
						<i class="fas fa-chevron-down pr-2"></i>Your Colours
					</h4>

					<div class="holder">
						<span v-for="p in coloursUser" v-on:click="loadFromPallete(p)" v-bind:style="{ 'background-color': 'rgb(' + p.r + ',' + p.g + ',' + p.b + ')' }"></span>
					</div>

					<div v-if="coloursUser.length == 0">No Colours Saved</div>

					<h4 class="pt-4">
						<i class="fas fa-chevron-down pr-2"></i>Popular Colours
					</h4>

					<div class="holder">
						<span v-for="p in coloursGlobal" v-on:click="loadFromPallete(p)" v-bind:style="{ 'background-color': 'rgb(' + p.r + ',' + p.g + ',' + p.b + ')' }"></span>
					</div>

				</div>

			</div>

		</div>

		<div id="preview" class="preview">

			<div class="views">
				<div v-for="v in view.availableViews" v-bind:class="{ current: v == view.currentView }" v-on:click="view.currentView = v">
					@{{ v }}
				</div>
			</div>

			<div id="preview-inner" class="inner" v-bind:class="{ loaded: init }">
				@include('partials.preview')
			</div>

		</div>

	</div>

	<form ref="download" method="post" v-bind:action="theme.url + '/download'" v-if="theme && theme.id">
		@csrf
		<input type="hidden" name="theme_id" v-bind:value="theme.id">
	</form>

	<transition name="fade">
		<div class="dialog dialog-new" v-if="dialog.new" v-cloak>

			<div class="dialog-inner">
				<p>Are you sure you want to discard all your changes and create a new theme?</p>
				<a href="/editor" class="btn btn-primary mr-2">
					Yes
				</a>
				<button class="btn btn-secondary" v-on:click="dialog.new = false">
					No
				</button>
			</div>

		</div>
	</transition>

	<transition name="fade">
		<div class="dialog dialog-save" v-if="dialog.save" v-cloak>

			<div class="dialog-inner text-left" v-bind:class="{ loading: saving }">

				<h2 class="mb-4">Save Theme</h2>

				<div class="form-group mb-4">
                    <label for="name">Name</label>

                    <div>
                        <input v-model="theme.name" type="text" class="form-control" required>
                    </div>
                </div>

                <div class="form-group mb-5">
                    <label for="name" class="mb-2">Visibility</label>

                    <div>
                    	<radio name="public" icon="fas fa-globe-americas" :checked="theme.public == 1" value="1" v-on:change="theme.public = parseInt($event)">Public</radio>
                    	<radio name="public" icon="fas fa-lock-alt" :checked="theme.public == 0" value="0" v-on:change="theme.public = parseInt($event)">Private</radio>
                    </div>
                </div>

                <div>
                    <button class="btn btn-primary mr-2" type="button" v-on:click="saveTheme()" v-bind:disabled="!theme.name || saving">
                        Save
                    </button>
                    <button class="btn btn-secondary" v-on:click="dialog.save = false" v-bind:disabled="saving">
						Cancel
					</button>
                </div>

			</div>

		</div>
	</transition>

	<transition name="fade">
		<div class="dialog dialog-share" v-if="dialog.share" v-on:click="dialog.share = false" v-cloak>

			<div class="dialog-inner text-left">

				<i class="fal fa-times close"></i>

				<h2 class="mb-4">Share</h2>

				<div class="icons">
					<i v-on:click.stop="shareTwitter()" class="fab fa-twitter"></i>
					<i v-on:click.stop="shareFacebook()" class="fab fa-facebook"></i>
					<i v-on:click.stop="shareReddit()" class="fab fa-reddit"></i>
				</div>

				<div class="link">
					<input id="theme-link" v-on:click.stop="copyLink()" type="text" readonly="readonly" v-bind:value="'{{ env('APP_URL') }}' + theme.url">
					<span class="link-copied" v-if="linkCopied">
						<i class="fal fa-check pr-2"></i>Link copied to clipboard!
					</span>
				</div>

			</div>

		</div>
	</transition>

	<transition name="fade">
		<div class="dialog dialog-lg dialog-download" v-if="dialog.download" v-on:click="dialog.download = false" v-cloak>

			<div class="dialog-inner text-left">

				<i class="fal fa-times close"></i>

				<h2 class="mb-4">Preparing your download</h2>

				<div class="progress mb-4">
					<div class="progress-bar" role="progressbar" v-bind:style="{ width: download.percent + '%' }" aria-valuenow="download.percent" aria-valuemin="0" aria-valuemax="100"></div>
				</div>

				<div class="row">
					@foreach($downloadAds as $a)
					<div class="col-sm-6">
						<div class="product">
							<a href="{{ $a->url }}" target="blank">
								<img src="/storage/products/{{ $a->image }}">
							</a>
						</div>
					</div>
					@endforeach
				</div>

			</div>

		</div>
	</transition>

	<transition name="fade">
		<div class="editor-welcome" v-if="welcome.show" v-cloak>

			<div class="inner" v-bind:class="{ importing: welcome.importing }">

				<div>

					<ul class="menu">
						<li v-bind:class="{ active: welcome.section == 'New' }" v-on:click="welcome.section = 'New'">
							<i class="fas fa-file"></i>New
						</li>
						<li v-bind:class="{ active: welcome.section == 'Recent' }" v-on:click="welcome.section = 'Recent'">
							<i class="fas fa-folder-open"></i>Recent
						</li>
						<li v-bind:class="{ active: welcome.section == 'Import' }" v-on:click="welcome.section = 'Import'">
							<i class="fas fa-file-import"></i>Import
						</li>
					</ul>

					<div class="content">

						<template v-if="welcome.section == 'New'">

							<h2>
								Welcome To The Editor
							</h2>

							<p>Choose a theme to start with</p>

							<div class="themes">
								@foreach($standardThemes as $t)
								<div v-on:click="remixFromWelcome({{ $t->id }})" class="theme">
									<div class="image">
										<img src="{{ str_replace('public/','/storage/', $t->preview) }}">
									</div>
									<h6>{{ $t->name }}</h6>
								</div>
								@endforeach
							</div>

							<div class="pt-4">
								or <a href="/browse" class="btn btn-primary btn-xs">Remix</a> an another theme
							</div>

						</template>

						<template v-if="welcome.section == 'Recent'">

							<h2>Recent Themes</h2>

							<p v-if="welcome.recent.length > 0">Load one of your recently created themes</p>
							<p v-if="welcome.recent.length == 0">You haven't created any themes yet</p>

							<div class="themes">
								<div v-for="t in welcome.recent" v-on:click="loadFromWelcome(t.id)" class="theme">
									<div class="image">
										<img v-bind:src="t.preview ? t.preview.replace('public/','/storage/') : ''">
									</div>
									<h6>@{{ t.name }}</h6>
								</div>
							</div>

							<div class="pt-4" v-if="welcome.recent.length > 0">
								<a href="/my-themes" class="btn btn-primary btn-xs">View My Themes</a>
							</div>

						</template>

						<template v-if="welcome.section == 'Import'">

							<h2>Import A Theme</h2>

							<p>Import a previously made Ableton 10 theme file (.ask)</p>

							<div class="alert alert-danger" v-if="welcome.importError" v-html="welcome.importError"></div>

							<input ref="importTheme" type="file" style="display: none;" v-on:change="importTheme($event)">

							<button v-html="welcome.importing ? 'Importing..' : 'Import'" v-on:click="$refs.importTheme.click()" class="btn btn-primary" v-bind:disabled="welcome.importing">
								Import
							</button>

						</template>

					</div>

					<i class="fal fa-times welcome-close" v-on:click="welcome.show = false"></i>

				</div>

			</div>

		</div>
	</transition>

	<div class="loader" v-if="!init || loading" v-cloak>

		<div class="loader-inner">
			<svg width="300" height="300" version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
				<path fill="#fc6767" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
					<animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform>
				</path>
			</svg>
		</div>

	</div>

	<theme-classes v-if="theme">

		.preview svg{
			width: @{{ zoom.current.width }}px;
			height: @{{ zoom.current.height }}px;
			top: @{{ zoom.current.top }};
			left: @{{ zoom.current.left }};
			transform: @{{ zoom.current.transform }};
		}

		.Preferences,
		.Automation,
		.Session,
		.AudioEditor,
		.MidiEditor{
			display: none;
		}

		.TrackColourOpacity,
		.ClipBgOpacity{
			opacity: 0.5;
		}

		@for($i = 1; $i <= 9; $i++)
		.Opacity{{ $i }}0 {
			opacity: 0.{{ $i }};
		}
		@endfor

		<template v-if="view.currentView == 'Preferences'">
			.Preferences{ display: block; }
		</template>
		<template v-if="view.currentView == 'Automation'">
			.Automation{ display: block; }
			#Arrangement_Clips{ display: none; }
		</template>
		<template v-if="view.currentView == 'Session'">
			.Session{ display: block; }
		</template>
		<template v-if="view.currentView == 'Audio Editor'">
			.AudioEditor{ display: block; }
		</template>
		<template v-if="view.currentView == 'Midi Editor'">
			.MidiEditor{ display: block; }
		</template>

		<template v-for="e in theme.elements">
			.@{{ e.element }} { fill: @{{ e.rgba }} !important; }
			<template v-for="r in e.related" v-if="e.related && r.property">
				.@{{ e.element }}{ @{{ r.property }}: @{{ theme.elements[r.element].rgba }} !important }
			</template>
		</template>

		<template v-if="highlightElement">
			.preview svg rect,
			.preview svg path,
			.preview svg text,
			.preview svg polygon,
			.preview svg circle,
			.preview svg polyline{
				opacity: 0.02;
			}
			.preview svg .@{{ currentElement.element }}{
				opacity: 1;
			}
		</template>

	</theme-classes>

</div>

<div class="mobile-warning">

	<div class="inner">

		<div>
			<i class="fas fa-exclamation-triangle"></i>
		</div>

		The theme editor was not designed to be used on mobile, try it on a desktop/laptop computer

	</div>

</div>

@endsection

@section('pagescript')
	<script>
		var theme_id = {{ intval($theme_id) }};
	</script>
	<script type="text/javascript" src="{{ mix('js/editor.js') }}"></script>
@endsection
