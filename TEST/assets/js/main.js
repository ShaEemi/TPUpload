$(document).ready(function(){

	htmlStructure = {
		callUploadImgOverview: function(){
			return $(`<div class="overview_bloc ">
						<hr/>
						<div class="col-sm-2 no-padd-l">
							<img class="upload_overview" src="" alt="">
						</div>

						<div class="overview_content">
							<span style="margin-top: 10px;" class="col-sm-6 file_name font12"></span>
							<span style="margin-top: 10px;" class="col-sm-3 file_size font12 text-muted"></span>
							<div class="col-sm-1 btn_container">
								<button class="remove_file_btn btn btn-danger btn-sm" type="button"><i class="glyphicon glyphicon-remove"></i></button>
							</div>
						</div>

						<div class="overview_notif col-sm-10 hidden">
						</div>
						
						<div class="clearfix"></div>
					</div>`);
		},

		callFileInput: function(name){
			return $(`<input class="upload_input hidden" type="file" name="` + name + `"/>`);
		}
	}

	/*
	* Methode inutile servant d'alternative dans une structure ternaire 
	*/
	$.fn.nothing = function(){
		return this;
	}

	/*
	* Générer l'aperçu HTML de(s) informations du fichier uploadé
	*/
	$.fn.generateOverview = function(overviewHtml){
		return this.each(function(){
			var $thisFileInput = $(this);

			$.each($thisFileInput.prop('files'), function(key, vakue) {
				var $newOverviewBloc = cloneAndDisplay(overviewHtml, $thisFileInput.closest('.bloc_file_uploader'), true);

				$newOverviewBloc.addClass('overview-of-input-' + $thisFileInput.attr('id'));

				if(key > 0){
					$newOverviewBloc.find('hr').css('opacity', 0);
					$newOverviewBloc.find('.remove_file_btn').remove();
				}

				$newOverviewBloc.find('.file_name').text($(this)[0].name);
				$newOverviewBloc.find('.file_size').text(($(this)[0].size / 1000).toFixed(2) + 'KB');
				$newOverviewBloc.find('.upload_overview').css({'max-width' : '100%', 'height' : '35px'}).addClass('loaded_file_overview');
				$newOverviewBloc.find('.remove_file_btn').attr({'target-file': $thisFileInput.attr('id'), 'index-file': key, 'related-overviews' : 'overview-of-input-' + $thisFileInput.attr('id')});
				readURL($(this), $newOverviewBloc.find('.upload_overview'));
			});
		});
	}

	/*
	* Suppression d'un fichier chargé
	*/
	$('form').on('click', '.overview_bloc .remove_file_btn', function(e){
		$('#' + $(this).attr('target-file')).remove();
		$('.' + $(this).attr('related-overviews')).remove();
	});

	/*
	* Suppression de tous les input:file vides, lors du submit
	*/
	$('form').on('submit', function(){
		clearEmptyFileInputs();
	});


	var $blocUploadPhotos = $('.upload_photos_modal .bloc_file_uploader');
	var $formUploadPhotos = $('.upload_photos_modal form');
	var $btnDeleteSeveralPhotos = $('#photos .btn_delete_several_photos');
	var $photoOverlay = $('#photos .boat_photo .overlay');

	/*
	* Clonage d'un input type file, et déclenchement d'un trigger click sur ce dernier pour afficher la fenêtre de sélection de fichier
	*/
	$('body').on('click', '.upload_photos_modal .active.btn_add_files', function(){
		triggerFileInput($blocUploadPhotos, 'files[]');
	});

	/*
	* Géneration de l'overview de(s) fichier(s) chargé(s)
	*/
	$('form').on('change', 'input:file.generated_file_input', function(){
		$(this).generateOverview(htmlStructure.callUploadImgOverview());
	});
});

/*
* Methode permettant de cloner un bloc html de le génerer à un endroit précis, et de l'afficher ou non
*/
function cloneAndDisplay($element, area, show){
	return  $element.clone(true)[show !== false ? 'show' : 'nothing']().appendTo(area);
}

/*
* Methode permettant de générer une chaine aléatoire
*/
function generateRandomString(str_length){
    var text = '';
    var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    for(var i = 0; i < str_length; i++){
    	text += possible.charAt(Math.floor(Math.random() * possible.length));
    }

    return text;
}

/*
* Génération d'un input file
*/
function triggerFileInput($parentBloc, inputName){
	var $newFileInput = cloneAndDisplay(htmlStructure.callFileInput(inputName), $parentBloc, false);
	var newInputId = generateRandomString(8);
	$newFileInput.removeAttr('id');
	
	$newFileInput.attr('id', newInputId).addClass('generated_file_input');
	$('#' + newInputId).trigger('click');
}

/*
* Suppression des input:file vides
*/
function clearEmptyFileInputs(){
	$.each($('form input:file'), function(){
		if($(this).prop('files').length == 0){
			$(this).remove();
		}
	});
}

/*
* Générer l'aperçu de l'image uploadée
*/
function readURL($fileInput, $fileOverview) {
    if ($fileInput && $fileInput[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $fileOverview.attr('src', e.target.result);
        }

        reader.readAsDataURL($fileInput[0]);
    }
}



