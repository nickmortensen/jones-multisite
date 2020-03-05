// (function($) {
// 	'use strict';

	/**
	 * The options are Complete, Ongoing, and Upcoming
	 */

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

// })( jQuery );


document.addEventListener( 'DOMContentLoaded', () => {

	// Make visible different year fields based on what is selected among the radio buttons for projectStatus.
	document.querySelectorAll('[name = projectJobStatus]').forEach( option => {
		option.addEventListener( 'click', event => {
			// Surely there is a better way to do this. Refactor when you come across this.
			document.querySelectorAll( '#statusYearInputs > div' ).forEach( div => {
				if ( event.target.value === div.querySelector('input').dataset.conditionalvalue && div.classList.contains('hidden') ) {
					div.classList.remove('hidden');
				}
				if ( event.target.value !== div.querySelector('input').dataset.conditionalvalue && ! div.classList.contains('hidden') ) {
					div.classList.add('hidden');
				}
			} )
		}, true)
	});

}, false );

