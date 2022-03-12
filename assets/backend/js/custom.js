function initDataTable(tableIds) {
	for(i = 0; i < tableIds.length; i++){
		$(tableIds[i]).DataTable({
			keys: !0,
			language: {
				paginate: {
					previous: "<i class='mdi mdi-chevron-left'>",
					next: "<i class='mdi mdi-chevron-right'>"
				}
			},
			drawCallback: function() {
				$(".dataTables_paginate > .pagination").addClass("pagination-rounded")
			}
		});
	}
}

$("#course-datatable").DataTable({
	keys: !0,
	language: {
		paginate: {
			previous: "<i class='mdi mdi-chevron-left'>",
			next: "<i class='mdi mdi-chevron-right'>"
		}
	},
	drawCallback: function() {
		$(".dataTables_paginate > .pagination").addClass("pagination-rounded")
	}
});

function initDateRangePicker(ids) {
	for(i = 0; i < ids.length; i++){
		$(ids[i]).daterangepicker({
			locale: {
				format: 'D/MM/Y'
			}
		});
	}

}

function initDatePicker(ids) {
	for(i = 0; i < ids.length; i++){
		$(ids[i]).datepicker({
			locale: {
				format: 'D/MM/Y'
			}
		});
	}

}

function initSelect2(ids) {
	for(i = 0; i < ids.length; i++){
		$(ids[i]).select2();
	}
}

function initTimepicker() {
	var defaultOptions = {
		"showSeconds": true,
		"icons": {
			"up": "mdi mdi-chevron-up",
			"down": "mdi mdi-chevron-down"
		}
	};

	// time picker
	$('[data-toggle="timepicker"]').each(function (idx, obj) {
		var objOptions = $.extend({}, defaultOptions, $(obj).data());
		$(obj).timepicker(objOptions);
	});
}

function initSummerNote(ids) {
	for(i = 0; i < ids.length; i++){
		! function(o) {
			"use strict";
			var e = function() {
				this.$body = o("body")
			};
			e.prototype.init = function() {
				o(ids[i]).summernote({
					placeholder: "",
					height: 230,
					callbacks: {
						onInit: function(e) {
							o(e.editor).find(".custom-control-description").addClass("custom-control-label").parent().removeAttr("for")
						}
					},
				});
			}, o.Summernote = new e, o.Summernote.Constructor = e
		}(window.jQuery),
		function(o) {
			"use strict";
			o.Summernote.init()
		}(window.jQuery);
	}
}

function changeTitleOfImageUploader(photoElem) {
	var fileName = $(photoElem).val().replace(/C:\\fakepath\\/i, '');
	$(photoElem).siblings('label').text(fileName);
}

function initImageUpload(box) {
	let uploadField = box.querySelector('.image-upload');

	uploadField.addEventListener('change', getFile);

	function getFile(e){
		let file = e.currentTarget.files[0];
		checkType(file);
	}

	function previewImage(file){
		let thumb = box.querySelector('.js--image-preview'),
		reader = new FileReader();
		reader.onload = function() {
			thumb.style.backgroundImage = 'url(' + reader.result + ')';
		}
		reader.readAsDataURL(file);
		thumb.className += ' js--no-default';
	}

	function checkType(file){
		let imageType = /image.*/;
		if (!file.type.match(imageType)) {
			throw 'Datei ist kein Bild';
		} else if (!file){
			throw 'Kein Bild gewählt';
		} else {
			previewImage(file);
		}
	}

}

// initialize box-scope
var boxes = document.querySelectorAll('.box');

for (let i = 0; i < boxes.length; i++) {
	let box = boxes[i];
	initDropEffect(box);
	initImageUpload(box);
}



/// drop-effect
function initDropEffect(box){
	let area, drop, areaWidth, areaHeight, maxDistance, dropWidth, dropHeight, x, y;

	// get clickable area for drop effect
	area = box.querySelector('.js--image-preview');
	area.addEventListener('click', fireRipple);

	function fireRipple(e){
		area = e.currentTarget
		// create drop
		if(!drop){
			drop = document.createElement('span');
			drop.className = 'drop';
			this.appendChild(drop);
		}
		// reset animate class
		drop.className = 'drop';

		// calculate dimensions of area (longest side)
		areaWidth = getComputedStyle(this, null).getPropertyValue("width");
		areaHeight = getComputedStyle(this, null).getPropertyValue("height");
		maxDistance = Math.max(parseInt(areaWidth, 10), parseInt(areaHeight, 10));

		// set drop dimensions to fill area
		drop.style.width = maxDistance + 'px';
		drop.style.height = maxDistance + 'px';

		// calculate dimensions of drop
		dropWidth = getComputedStyle(this, null).getPropertyValue("width");
		dropHeight = getComputedStyle(this, null).getPropertyValue("height");

		// calculate relative coordinates of click
		// logic: click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center
		x = e.pageX - this.offsetLeft - (parseInt(dropWidth, 10)/2);
		y = e.pageY - this.offsetTop - (parseInt(dropHeight, 10)/2) - 30;

		// position drop and animate
		drop.style.top = y + 'px';
		drop.style.left = x + 'px';
		drop.className += ' animate';
		e.stopPropagation();

	}
}


function initImagePreviewer() {
	var boxes = document.querySelectorAll('.box');

	for (let i = 0; i < boxes.length; i++) {
		let box = boxes[i];
		initDropEffect(box);
		initImageUpload(box);
	}
}


function checkRequiredFields() {
	var pass = 1;
	$('form.required-form').find('input, select').each(function(){
		if($(this).prop('required')){
			if ($(this).val() === "") {
				pass = 0;
			}
		}
	});

	if (pass === 1) {
		$('form.required-form').submit();
	}else {
		error_required_field();
	}
}
