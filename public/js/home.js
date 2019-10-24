$(document).ready(function () {
	$('.hasSub').click(function () {
    $(this).parent().toggleClass('subactivated');
		$(this).parent().children('ul:first').toggle();
    
    if($(this).find('i').hasClass('glyphicon-folder-open')){
      $(this).find('i').removeClass('glyphicon-folder-open').addClass('glyphicon-folder-close');
    }else{
      $(this).find('i').removeClass('glyphicon-folder-close').addClass('glyphicon-folder-open');
    }
	}); 
  
  $(".menufilter").keyup(function () {
     //$(this).addClass('hidden');
  
    var searchTerm = $(".menufilter").val();
    var listItem = $('.fordtreeview').children('li');
  
    var searchSplit = searchTerm.replace(/ /g, "'):containsi('")
    
      //extends :contains to be case insensitive
  $.extend($.expr[':'], {
  'containsi': function(elem, i, match, array)
  {
    return (elem.textContent || elem.innerText || '').toLowerCase()
    .indexOf((match[3] || "").toLowerCase()) >= 0;
  }
});
    
    $(".fordtreeview li").not(":containsi('" + searchSplit + "')").each(function(e)   {
      $(this).hide()
    });
    
    $(".fordtreeview li:containsi('" + searchSplit + "')").each(function(e) {
      $(this).show();
    });
  });  
});

$(document).on('click','.list-group-folder', function() {
    $('.list-group-item').removeClass('selected_item')
    $(this).addClass('selected_item')
})