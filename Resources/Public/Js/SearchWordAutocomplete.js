<script type="text/javascript">
	/*
	 * Adding auto-suggest feature to input element
	 */

	/* TODO we somehow have to generate this by filter object */
    
	var tx_solr_suggestUrl = 'index.php?id=47&eID=tx_ptsolr_autocomplete';
	jQuery(document).ready(function(){

		jQuery('#solrAutoComplete').autocomplete({
			//appendTo: '#solrAutoComplete',
			delay: 500,
			minLength: 2,
			position: {
				collision: "none",
				offset: '0 0'
			},
			source: function(request, response) {
				jQuery.ajax({
					url: tx_solr_suggestUrl,
					dataType: 'json',
					data: {
						termLowercase: request.term.toLowerCase(),
						termOriginal: request.term
					},
					success: function(data) {
						var rs     = [],
							output = [];

						jQuery.each(data, function(term, termIndex) {
							output.push({
								label : term,
								value : request.term,
								count: data[term]
							});
						});

						response(output);
					}
				})
			},
			select: function(event, ui) {
				$( "#solrAutoComplete" ).val( ui.item.label );
				jQuery(event.target).closest('form').submit();
				return false;
			},
			focus: function( event, ui ) {
				$( "#solrAutoComplete" ).val( ui.item.label );
				return false;
			}
		}
		)
		.data( "autocomplete" )._renderItem = function( ul, item ) {

			var formattedLabel = item.label.replace(new RegExp('(?![^&;]+;)(?!<[^<>]*)(' +
														jQuery.ui.autocomplete.escapeRegex(item.value) +
														')(?![^<>]*>)(?![^&;]+;)', 'gi'), '<strong>$1</strong>')

			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append(
					"<a>" + formattedLabel + ' <span class="result_count">' + item.count + '</span>' + "</a>"
					)
				.appendTo( ul );
		};
	});
</script>