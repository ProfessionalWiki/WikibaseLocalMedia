( function () {
	'use strict';

	/**
	 * Media suggester.
	 * Enhances an input box with suggestion functionality for local asset names.
	 * (uses `util.highlightSubstring`)
	 * @class jQuery.ui.mediasuggester
	 * @extends jQuery.ui.suggester
	 * @uses util
	 * @license GNU GPL v2+
	 *
	 * @constructor
	 */
	$.widget( 'ui.mediasuggester', $.ui.suggester, {

		/**
		 * @see jQuery.ui.suggester.options
		 */
		options: {
			ajax: $.ajax,
			apiUrl: null,
			indexPhpUrl: null,
			namespaceId: 6
		},

		/**
		 * @inheritdoc
		 * @protected
		 */
		_create: function() {
			if ( !this.options.apiUrl ) {
				throw new Error( 'apiUrl option required' );
			}

			if ( !this.options.source ) {
				this.options.source = this._initDefaultSource();
			}

			$.ui.suggester.prototype._create.call( this );

			this.options.menu.element.addClass( 'ui-mediasuggester-list' );
		},

		/**
		 * Initializes the default source pointing to the "query" API module of the local wiki.
		 * @protected
		 *
		 * @return {Function}
		 */
		_initDefaultSource: function() {
			var self = this;

			return function( term ) {
				var deferred = $.Deferred();

				self.options.ajax( {
					url: self.options.apiUrl,
					dataType: 'jsonp',
					data: {
						action: 'query',
						list: 'search',
						srsearch: term,
						srnamespace: self.options.namespaceId,
						srlimit: 10,
						format: 'json'
					},
					timeout: 8000
				} )
				.done( function( response ) {
					var sorted = self._prioritiseMatchingFilename( response.query.search, term );

					deferred.resolve( sorted, term );
				} )
				.fail( function( jqXHR, textStatus ) {
					// Since this is a JSONP request, this will always fail with a timeout...
					deferred.reject( textStatus );
				} );

				return deferred.promise();
			};
		},

		/**
		 * Be smart on the media search results and put an exactly matching file name on top
		 * @private
		 *
		 * @param {Array} resultList Results from the search API response
		 * @param {string} term The user's search term
		 *
		 * @return {Array}
		 */
		_prioritiseMatchingFilename: function( resultList, term ) {
			return resultList.sort( function( a, b ) {
				// use indexOf() in favour of startsWith() for browser compatibility
				if ( a.title.indexOf( this._getFileNamespace() + ':' + term ) === 0 ) {
					return -1;
				} else if ( b.title.indexOf( this._getFileNamespace() + ':' + term ) === 0 ) {
					return 1;
				} else {
					return 0;
				}
			} );
		},

		/**
		 * @see jQuery.ui.suggester._createMenuItemFromSuggestion
		 * @protected
		 *
		 * @param {Object} suggestion
		 * @param {string} requestTerm
		 * @return {jQuery.ui.ooMenu.Item}
		 */
		_createMenuItemFromSuggestion: function( suggestion, requestTerm ) {
			suggestion = suggestion.title;

			var isFile = this._getFileNamespaceRegex().test( suggestion );

			if ( isFile ) {
				suggestion = suggestion.replace( this._getFileNamespaceRegex(), '' );
			}

			var label = util.highlightSubstring(
					requestTerm,
					suggestion,
					{
						caseSensitive: false,
						withinString: true
					}
				),
				$label = $( '<span>' )
					.attr( { dir: 'ltr', title: suggestion } )
					.append( label );

			if ( isFile ) {
				$label.prepend( this._createThumbnail( suggestion ) );
			}

			return new $.ui.ooMenu.Item( $label, suggestion );
		},

		/**
		 * @private
		 *
		 * @param {string} fileName Must be a file name without the File: namespace.
		 * @return {jQuery}
		 */
		_createThumbnail: function( fileName ) {
			return $( '<span>' )
				.attr( 'class', 'ui-mediasuggester-thumbnail' )
				.css( 'background-image', this._createBackgroundImage( fileName ) );
		},

		/**
		 * @private
		 *
		 * @param {string} fileName Must be a file name without the File: namespace.
		 * @return {string} CSS
		 */
		_createBackgroundImage: function ( fileName ) {
			// Height alone is ignored, width must be set to something.
			// We accept to truncate 50% and only show the center 50% of the images area.
			return 'url("' + this.options.indexPhpUrl + '?title=Special:Filepath/'
				+ encodeURIComponent( fileName )
				+ '&width=100&height=50")';
		},

		/**
		 * @private
		 *
		 * @returns {string}
		 */
		_getFileNamespace: function () {
			return  mw.config.get('wgFormattedNamespaces')[6];
		},

		/**
		 * @private
		 *
		 * @returns {RegExp}
		 */
		_getFileNamespaceRegex: function () {
			return new RegExp( '^' + this._getFileNamespace() + ':' );
		}

	} );

}() );
