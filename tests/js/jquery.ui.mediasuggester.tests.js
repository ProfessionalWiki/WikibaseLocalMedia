/**
 * @license GPL-2.0+
 */
( function () {
	'use strict';

	/**
	 * @return {jQuery}
	 */
	var newTestSuggester = function( mockSearchResult ) {
		var options = {
			ajax: function( options ) {
				var response = { query: { search: mockSearchResult || [] } };

				// This uses the search results array as a spy, and appends _requestTerm
				response.query.search._requestTerm = options.data.srsearch;

				return $.Deferred().resolve( response ).promise();
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
		var $suggester = newTestSuggester(),
			suggester = $suggester.data( 'mediasuggester' ),
			input = 'Bar',
			done = assert.async();

		$suggester.val( input );
		suggester.search().done( function( suggestions, term ) {
			assert.strictEqual( suggestions._requestTerm, 'Bar' );
			assert.strictEqual( term, input );

			done();
		} );
	} );

	QUnit.test( 'put matching file name on top of result list', function( assert ) {
		var $suggester = newTestSuggester( [
				{ title: 'File:mockResult_a.jpg' },
				{ title: 'File:mockResult_b.jpg' },
				{ title: 'File:mockResult_c.jpg' }
			] ),
			suggester = $suggester.data( 'mediasuggester' ),
			input = 'mockResult_b.jpg',
			done = assert.async();

		$suggester.val( input );
		suggester.search().done( function( suggestions, term ) {
			assert.strictEqual( suggestions[0].title, 'File:mockResult_b.jpg' );
			assert.strictEqual( suggestions[2].title, 'File:mockResult_c.jpg' );
			done();
		} );
	} );

}() );
