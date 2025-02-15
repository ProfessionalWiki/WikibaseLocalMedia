module.exports = ( function( $, vv ) {
	'use strict';

	var PARENT = vv.experts.StringValue;

	vv.experts.LocalMediaType = vv.expert( 'LocalMediaType', PARENT, {
		/**
		 * @inheritdoc
		 * @protected
		 */
		_init: function() {
			PARENT.prototype._init.call( this );

			var notifier = this._viewNotifier,
				$input = this.$input;

			const apiUrl = mw.config.get( 'wgWikibaseLocalMediaRemoteApiUrl' ) || this._options.vocabularyLookupApiUrl;
			$input.mediasuggester( {
				apiUrl: apiUrl,
				indexPhpUrl: apiUrl.replace('api.php', 'index.php'),
				namespaceId: 6
			} );

			// Using the inputautoexpand plugin, the position of the dropdown needs to be updated
			// whenever the input box expands vertically:
			$input
				.on( 'eachchange', function( event, oldValue ) {
					$input.data( 'mediasuggester' ).repositionMenu();
				} )
				.on( 'mediasuggesterchange', function( event, response ) {
					notifier.notify( 'change' );
					$input.data( 'inputautoexpand' ).expand();
				} );
		}
	} );

	return vv.experts.LocalMediaType;

}( jQuery, jQuery.valueview ) );
