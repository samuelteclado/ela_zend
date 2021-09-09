function printDiv()
{
  var divToPrint= $('div#left_colum > div.blog');
  var detailsection = $('#right_colum');
  var Printheader = $('head').html();
  newWin= window.open("");
  newWin.document.write(Printheader + '<div class="columns"><div class="col1 left">'+ divToPrint.html() + '</div></div>');
  newWin.document.close();
  newWin.focus();
  newWin.print();
  newWin.close();
}

$('#print_page').live('click', function() {
	if ( ! confirm("Press OK to print just Detail Area or Cancel to pinrt Whole page?")) 
	{
		window.print();
	}
	else {
		printDiv();
	}
});

$("#loginform").live('submit', function() {
	//alert('test');
	var username = $(':input[name="log"]', this);
	var password = $(':input[name="pwd"]', this);

	'<div class="alertin"><h6 class="bold white">Red Alert</h6><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean at tellus sem.</p></div>';
	if($(username).val() == '' )
	{
		$('#loginform_msg', this).html('<div class="alertin"><h6 class="bold white">Login Failed</h6><p>Enter a valid username!</p></div>').fadeIn('slow');
		return false;
	}
	else if($(password).val() == '')
	{
		$('#loginform_msg', this).html('<div class="alertin"><h6 class="bold white">Login Failed</h6><p>Enter a valid Password!</p></div>').fadeIn('slow');
		return false;
	}
	else {
		$('#loginform_msg', this).fadeOut('slow');
		return true;
	}
});

$('#search_form').live('submit', function() {
	var search_text = 'Enter any Keyword';
	if($('#searchBox', this).val() == '' || $('#searchBox', this).val() == search_text)
	{
		alert('Please Enter any Keyword');
		return false;
	}
	else {
		return true;
	}
});

$('#newletter_sub').live('submit', function() {
	var email_text = 'Digite um email válido';
	if($('#email_address', this).val() == '' || $('#email_address', this).val() == email_text)
	{
		alert('Digite um email válido');
		return false;
	}
	else if(!isValidEmailAddress($('#email_address', this).val()))
	{
		alert('Email inválido! Digite um email válido');
		return false;
	}
	else {
		return true;
	}
});

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
};

$(document).ready(function() {
	$(".color-picker").miniColors({
		letterCase: 'uppercase',
		change: function(hex, rgb) {
			new_append(hex);
		}
	});
	function new_append(hex)
	{
		var style = '/* Text color */.colr {color:%s !important;}.txthover:hover, ul.links li a:hover {	color:%s !important;}.backcolr, .sub-menu, span.current {	background-color:%s !important;}.page-numbers:hover {	background-color:%s !important;}a.comment-reply-link:hover{	background-color:%s !important;}.backcolrdark {	background-color:%s !important;	background-image:url(../images/dark.png);}.bordercolr {	border-color:%s !important;}.footerwidgets h4.backcolr{	color:%s !important;	background:none !important;}/* Navigation */.ddsmoothmenu ul li a:hover, .ddsmoothmenu ul li.current-menu-item a{	background-color:%s;}/* Feedlist thumb hover */.feedlist ul li:hover a.thumb{	border-left:%s solid 5px;}/* Announcement */.navigation .announcment:hover a.mlink{color:%s;}/* Gallery Filter */ul#filterOptions li a:hover{background: %s;}ul#filterOptions li.active a{background: %s;}/* Sponsers Buttons */.sponsers #controls a.prevBtn:hover, .sponsers #controls a.nextBtn:hover{background-color:%s;}';
		var newstyle = style.replace(/%s/g, hex);
		$('head > style[title="custom_style"]').text(newstyle);
		$.cookies.set('custom_color', hex);
	}
});