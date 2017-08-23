/**
 * Constructor function for note object
 * 
 * @param options
 */
Note = function (options) {

	if (options.color.length != 6) {
		options.color = "646464"; // default header color - grey
	}
	if (options.text == null) {
		options.text = "";
	}
	
	this.showDialog = function() {
		
		options.$noteDiv = $('<div/>')
			.appendTo($("body"))
			.height(options.height - 50)
			.one("click", options, toTextarea);
		
		options.$editableDiv = $('<div/>')
			.appendTo(options.$noteDiv)
			.html(getHtml(options.text));

		// make dialog from div
		options.$noteDiv.dialog({
			title: options.title,
			closeOnEscape: true,
			position: [parseInt(options.left), parseInt(options.top)],
			height: parseInt(options.height),
			width: parseInt(options.width),
			resize : function(event, ui) {
				$('textarea', this)
					.css('height', ($(this).parent().height() - 55) + 'px');
			},
			resizeStop : function(event, ui) {
				$.post(noteSaveUrl, {
					note: options.id,
					height :parseInt($(this).parent().css('height')),
					width :parseInt($(this).parent().css('width')),
					left :parseInt($(this).parent().css('left')),
					top :parseInt($(this).parent().css('top'))
				}, function(data){
					if (data.error !== undefined) {
						alert('An error occured during the request: ' + data.error);
					} 
				}, 'json');
			},
			dragStop : function(event, ui) {
				$.post(noteSaveUrl, {
					note: options.id,
					left :parseInt($(this).parent().css('left')),
					top :parseInt($(this).parent().css('top'))
				}, function(data){
					if (data.error !== undefined) {
					alert('An error occured during the request: ' + data.error);
					}
				}, 'json');
			},
			beforeclose : function(event, ui) {
				if (confirm('Delete this note?')) {
					$.post(noteDeleteUrl, {note: options.id}, function(data){
						if (data.error !== undefined) {
							alert('An error occured during the request: ' + data.error);
						} 
					}, 'json');
					return true;
				} else {
					return false;
				}
			}
		});
		// change color
		$(".ui-widget-header", options.$noteDiv.parent())
			.css("background", "#" + options.color);
	};
};

/**
 * Converts textarea to div
 * 
 * @param event
 * @return
 */
var toDiv = function(event) {
	options = event.data;
	
	var newText = options.$editableDiv.children().val();
	// send request if changed
	if (options.text != newText) {
		options.text = newText;
		$.post(noteSaveUrl, {
			note: options.id,
			text :options.text
		});
	}

	options.$editableDiv.html(getHtml(options.text));
	options.$noteDiv
		.one("click", options, toTextarea);
	
};

/**
 * Converts div to textare
 * 
 * @param event
 * @return
 */
var toTextarea = function(event){
	options = event.data;
	
	$('<textarea />')
		.height(options.$noteDiv.parent().height() - 55)
		.width('100%')
		.appendTo(options.$editableDiv.empty())
		.val(options.text)
		.one("blur", options, toDiv)
		.focus();
	
};

/**
 * Parse text to html
 * 
 * @param text
 * @return string
 */
var getHtml = function(text) {
	var html = text.replace(/</g, '&lt;');
	html = html.replace(/>/g, '&gt;');
	html = html.replace(/(^|[^\/])www\./g, "$1http://www.");
	html = html.replace(/(^|[^'])(https?|ftp):\/\/([a-zA-Z0-9.-]+)\/([^ \n]{10})([^ \n\)]+)/g, 
		"$1<a target='_blank' href='$2://$3/$4$5'>$3/$4...</a>");
	html = html.replace(/(^|[^'])(https?|ftp):\/\/([a-zA-Z0-9.-]+)([^ \n\)]*)/g, 
		"$1<a target='_blank' href='$2://$3$4'>$3$4</a>");

	var arr = 0;
	var reg = /href='([^\/][^']+)'/g;
	var link = '';
	var re_link = '';
	
	while (arr = reg.exec(html)) {
		link = arr[1];
		re_link = link.replace(/(\.|\?|\\)/g, "\\$1");
		html = html.replace(new RegExp("href='" + re_link + "'", "m"), 
				"href='/redirect/?link=" + encodeURIComponent(encodeURIComponent(link)) + "'");
	}
	
	html = html.replace(/([a-zA-Z0-9_.-]+@[a-zA-Z0-9.-]+\.[a-z]{2,5})/g, 
		"<a href='mailto:$1'>$1</a>");
	html = html.replace(/\n/g, "<br/>");
	html = html.replace(/<br\/> +/g, "<br/>&nbsp;");

	return html;
};

/**
 * Converts border-bottom-color to hex value without #
 * 
 * @param rgbVal
 * @return string
 */
var getColor = function(rgbVal) {
	var s = rgbVal
			.match(/rgb\s*\x28((?:25[0-5])|(?:2[0-4]\d)|(?:[01]?\d?\d))\s*,\s*((?:25[0-5])|(?:2[0-4]\d)|(?:[01]?\d?\d))\s*,\s*((?:25[0-5])|(?:2[0-4]\d)|(?:[01]?\d?\d))\s*\x29/);

	if (s) {
		s = s.splice(1);
	}
	if (s && s.length == 3) {
		d = '';
		for (i in s) {
			e = parseInt(s[i], 10).toString(16);
			e == "0" ? d += "00" : d += e;
		}
		return d;
	} else {
		return rgbVal.replace(/#/, "");
	}
};

/**
 * Array containg note's objects
 */
var noteDialogs = [];

/**
 * Load notes from server
 * 
 */
var loadDialogs = function() {
	$.post(noteListUrl, {},
        function(data){
        	if (data.error !== undefined) {
				alert('An error occured during the request: ' + data.error);
			} else {
	  	        $.each(data.notes, function(){
	  		  	  noteDialogs.push(new Note(this).showDialog());
	  	        });
  	      }
    }, 'json');
	// for some reason (yes, I am not a js programmer) new note click event handler needs to be here
	// it might be caused of jQuery remove() method

	// dropdown menu
	$('#menu li.headlink').unbind('hover');
	$('#menu li.headlink').hover(
		function() { $('ul', this).css('display', 'block'); },
		function() { $('ul', this).css('display', 'none'); }
	);
	$("a", $("#menu")).unbind('click');
    $("a", $("#menu")).click(function(){
        var noteTitle = prompt("Enter the note's title:", "");
        if (noteTitle == null) {
            return false;
        }
		$.post(noteNewUrl,
			{title: noteTitle, color: getColor($(this).css("border-bottom-color"))},
    		function(data) {
				if (data.error !== undefined) {
					alert('An error occured during the request: ' + data.error);
				} else {
					var note = new Note(data.note).showDialog();
					noteDialogs.push(note);
				}
			},
			'json'
		);
    });
};

/**
 * On page load function
 */
$(function(){
	// load dialogs when page is loaded
	loadDialogs();
    // timer for refreshing dialogs on the page:
    setInterval(function() {
    	// delete all notes
    	$.each(noteDialogs, function(i, note){
    		$(noteDialogs[i]).remove();
    		//delete noteDialogs[i];
    	});
    	noteDialogs = [];
    	// load dialogs again
    	loadDialogs();
    }, 1000 * 300 ); // five minutes interval
});
