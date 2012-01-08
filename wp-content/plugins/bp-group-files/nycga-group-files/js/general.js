jQuery(document).ready( function($) {

	//Hide the sort form submit, we're gonna submit on change
	$('#nycga-group-files-sort-form input[type=submit]').hide();
	$('#nycga-group-files-sort-form select[name=order]').change(function(){
		$('form#nycga-group-files-sort-form').submit();
	});

	//Hide the category form submit, we're gonna submit on change
	$('#nycga-group-files-category-form input[type=submit]').hide();
	$('#nycga-group-files-category-form select[name=category]').change(function(){
		$('form#nycga-group-files-category-form').submit();
	});

	//Hide the upload form by default, expand as needed
	$('#nycga-group-files-upload-new').hide();
	$('#nycga-group-files-upload-button').show();
	$('#nycga-group-files-upload-button').click(function(){
		$('#nycga-group-files-upload-button').slideUp();
		$('#nycga-group-files-upload-new').slideDown();
		return false;
	});

	//prefill the new category field
	$('input.nycga-group-files-new-category').val('New Category...').css('color','#999').focus(function(){
		$(this).val('').css('color','inherit');
	});
		
	//check for presence of a file before submitting form
	$('form#nycga-group-files-form').submit(function(){
		
		//check for pre-filled values, and remove before sumitting
		if( $('input.nycga-group-files-new-category').val() == 'New Category...' ) {
			$('input.nycga-group-files-new-category').val('');
		}
		if( $('input[name=nycga_group_files_operation]').val() == 'add' ) {
			if($('input.nycga-group-files-file').val()) {
				return true;
			}
			alert('You must select a file to upload!');
			return false;
		}
	});	

	//validate group admin form before submitting
	$('form#group-settings-form').submit(function() {
		
		//check for pre-filled values, and remove before sumitting
		if( $('input.nycga-group-files-new-category').val() == 'New Category...' ) {
			$('input.nycga-group-files-new-category').val('');
		}
	});

	//Make the user confirm when deleting a document
	$('a#nycga-group-files-delete').click(function(){
		return confirm('Are you sure you wish to permanently delete this document?');
	});

	//Track when a user clicks a document via Ajax
	$('a.group-documents-title').add($('a.group-documents-icon')).click(function(){
		dash_position = $(this).attr('id').lastIndexOf('-');
		document_num = $(this).attr('id').substring(dash_position+1);

		$.post( ajaxurl ,{
			action:'nycga_group_files_increment_downloads',
			document_id:document_num
		});

	});

	//Make user confirm when deleting a category
	$('a.group-documents-category-delete').click(function(){
		return confirm('Are you sure you wish to permanently delete this category?');
	});

	//add new single categories in the group admin screen via ajax
	$('#group-documents-group-admin-categories input[value=Add]').click(function(){
		$.post(ajaxurl, {
			action:'group_documents_add_category',
			category:$('input[name=nycga_group_files_new_category]').val()
		}, function(response){
			$('#group-documents-group-admin-categories input[value=Add]').parent().before(response);
		});
		return false;
	});

	//delete single categories in the group admin screen via ajax
	$('#group-documents-group-admin-categories a.group-documents-category-delete').click(function(){
		cat_id_string = $(this).parent('li').attr('id');
		pos = cat_id_string.indexOf('-');
		cat_id = cat_id_string.substring(pos+1);
		$.post(ajaxurl, {
			action:'group_documents_delete_category',
			category_id:cat_id
		}, function(response){
			if( '1' == response ) {
				$('li#' + cat_id_string).slideUp();
			}
		});
		return false;
	});

});
