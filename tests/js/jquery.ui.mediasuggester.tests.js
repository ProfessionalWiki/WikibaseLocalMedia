/**
 * @license GPL-2.0+
 */
( function () {
	'use strict';

	/**
	 * @return {jQuery}
	 */
	var newTestSuggester = function( mockSearchResult ) {
		return newTestSuggesterWithApi( function() {
			return { query: { search: mockSearchResult || [] } };
		} );
	};

	/**
	 * @param {Function} respond Returns the API response to the given request options
	 * @return {jQuery}
	 */
	var newTestSuggesterWithApi = function( respond ) {
		var options = {
			ajax: function( options ) {
				return $.Deferred().resolve( respond( options ) ).promise();
			},
			apiUrl: 'can not be empty'
		};

		return $( '<input>' )
			.addClass( 'test_suggester' )
			.appendTo( 'body' )
			.mediasuggester( options );
	};

	QUnit.module( 'jquery.ui.mediasuggester', {
		afterEach: function() {
			var $suggester = $( '.test_suggester' ),
				suggester = $suggester.data( 'mediasuggester' );
			if ( suggester ) {
				suggester.destroy();
			}
			$suggester.remove();
		}
	} );

	QUnit.test( 'Create', function( assert ) {
		var $suggester = newTestSuggester();

		assert.ok(
			$suggester.data( 'mediasuggester' ) instanceof $.ui.mediasuggester,
			'Instantiated media suggester.'
		);
	} );

	QUnit.test( 'search integration', function( assert ) {
		var requestedTerm,
			$suggester = newTestSuggesterWithApi( function( options ) {
				requestedTerm = options.data.srsearch;
				return { query: { search: [] } };
			} ),
			suggester = $suggester.data( 'mediasuggester' ),
			input = 'Bar',
			done = assert.async();

		$suggester.val( input );
		suggester.search().done( function( suggestions, term ) {
			assert.strictEqual( requestedTerm, 'Bar' );
			assert.strictEqual( term, input );

			done();
		} );
	} );

	QUnit.test( 'search resolves with no suggestions when the API returns an error', function( assert ) {
		var $suggester = newTestSuggesterWithApi( function() {
				return { error: { code: 'readapidenied', info: 'You need read permission to use this module.' } };
			} ),
			suggester = $suggester.data( 'mediasuggester' ),
			done = assert.async();

		assert.timeout( 1000 );
		$suggester.val( 'Foo' );
		suggester.search().done( function( suggestions ) {
			assert.deepEqual( suggestions, [] );
			done();
		} );
	} );

	QUnit.test( 'files starting with the search term come first, both groups in API order', function( assert ) {
		var $suggester = newTestSuggester( [
				{ title: 'File:Foo 1' },
				{ title: 'File:Bar Foo' },
				{ title: 'File:Foo 2' },
				{ title: 'File:Foo 3' },
				{ title: 'File:Baz foo' }
			] ),
			suggester = $suggester.data( 'mediasuggester' ),
			done = assert.async();

		$suggester.val( 'Foo' );
		suggester.search().done( function( suggestions ) {
			assert.deepEqual( suggestions, [
				{ title: 'File:Foo 1' },
				{ title: 'File:Foo 2' },
				{ title: 'File:Foo 3' },
				{ title: 'File:Bar Foo' },
				{ title: 'File:Baz foo' }
			] );
			done();
		} );
	} );

}() );
