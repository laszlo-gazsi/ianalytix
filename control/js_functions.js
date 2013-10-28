var sitesListStart = 0;
var date = "";

function ajaxRequest(url, destination, functionToExecute){
	$.ajax({
		type: 'GET',
		url: url,
		success:
			function(content){
		    	document.getElementById(destination).innerHTML = content;
		    	if (typeof functionToExecute != 'undefined') functionToExecute();
			}
		});
}

//defining String functions
function trim(str) {
    return str.replace(/^\s*/, "").replace(/\s*$/, "");
}

//eye-candy

function initLoader(){
	setTimeout(document.getElementById('loader').style.zIndex = '1000', 1000);
}

function closeLoader(){
	setTimeout(document.getElementById('loader').style.zIndex = '-1000', 1000);
}

//execued on pageload
function reload_all(){
	ajaxRequest('view/main_right.php', 'right');
	ajaxRequest('view/main_left.php', 'left');
	ajaxRequest('view/navigation.php', 'navigation', closeLoader);
}

//user registration
function load_user_registration_form(){
	$.fn.colorbox({href:'view/user_registration_form.html'});
}

function register(){
	var error = 0;
	var error_report = '';
	
	password1 = trim(document.getElementById('registration_form_password').value);
	password2 = trim(document.getElementById('registration_form_password_confirmation').value);
	email = trim(document.getElementById('registration_form_email').value);
	
	//verifying if all fields are filled in and a valid email is given
	
	if ( email == null || email == '' || email.indexOf('@') < 1 || email.indexOf('.') < 1){
		error++;
		error_report += '<li>You must provide a valid email address!</li>';
	}
	
	if ( password1 == null || password1 == '' ){
		error++;
		error_report += '<li>You must enter a password!</li>';
	}
	
	if ( password2 == null || password2 == '' ){
		error++;
		error_report += '<li>You must confirm your password!</li>';
	}
	
	//verifying if passwords match
	
	if ( password1 != password2 ){
		error++;
		error_report += '<li>The passwords you have entered do not match!</li>';
	}
	
	//verifying if the email is already in use
	$.ajax({ type: 'GET', url: 'validation/email_validation.php?email=' + email,
		success:
			function(content){
		    	if (content != 'ok') error_report = '<li>An account already exists with this email address!</li>' + error_report;
		    	else if (error == 0) {
		    		url = 'control/register_user.php?email=' + escape(email) + '&password=' + SHA1(password1);
		    		$.fn.colorbox({href:url, width:"50%"});
		    	}
			}
	});
	
	
	document.getElementById('registration_form_errors').innerHTML = '<ul>' + error_report + '</ul>';
	$.fn.colorbox.resize();
}

//login / logout
function login(){
	mail = trim(document.getElementById('email').value);
	pass = trim(document.getElementById('password').value);
	
	if (mail != null && pass != null && mail != '' && pass != '' && mail != 'email' && pass != '12345678'){
		ajaxRequest('control/login.php?email=' + mail + '&password=' + SHA1(pass), 'right', reload_all);
	}
}

function logout(){
	initLoader();
	ajaxRequest('control/logout.php', 'right', reload_all);
}

function activate(){
	initLoader();
	code = document.getElementById('activation_code').value;
	if (code != null && code != ''){
		ajaxRequest('control/activate_account.php?code=' + code, 'left', reload_all);
	}
}

//load pages
function load_users_sites( startNr ){
	initLoader();
	sitesListStart = startNr;
	date = "";
	ajaxRequest('view/sites_list.php?start=' + startNr, 'left', closeLoader);
}

//adding new site
function load_add_site_form(){
	$.fn.colorbox({href:'view/add_new_site_form.html'});
}

function add_new_site(){
	url = escape(document.getElementById('new_site_url').value);
	$.fn.colorbox({href:'control/add_site.php?url=' + url, width:"50%", 
					overlayClose: false, 
					onComplete:function(){ ajaxRequest('view/sites_list.php?start=' + sitesListStart, 'left'); }});
}

//removing site
function ask_remove_site(ID){
	$.fn.colorbox({href:'view/confirm_remove.php?ID=' + ID});
}

function remove_no(){
	$.fn.colorbox.close();
}

function remove_yes(ID){
	$.fn.colorbox({href:'control/remove_site.php?ID=' + ID,
		onComplete:function(){ ajaxRequest('view/sites_list.php?start=' + sitesListStart, 'left'); }});
}

//displaying statistics data

function load_stats(ID){
	initLoader();
	sitesListStart = 0;
	ajaxRequest('view/stats_index.php?ID=' + ID, 'left', set_date_picker);
}

function set_date_picker(){
	
	$('#date').daterangepicker({
		presets: {
		specificDate: 'Specific date',
		dateRange: 'Custom range'
		}
		//was used to automatically make the request, but the datepicker is too buggy for this...
		/*onClose: function(){
			date = '';
			date = document.getElementById('date').value;
			ajaxRequest('view/stats_overview.php?ID=' + lastID + '&date=' + date, 'overview');
		} */
	});
	document.getElementById('date').value = date;
	if (document.getElementById('date').value == ""){
		closeLoader();
		$('#date').trigger('click');
	}
	else
		$('#stats_button').trigger('click');
}

function load_stat_elements(ID){
	initLoader();
	date = document.getElementById('date').value;
	ajaxRequest('view/stats_overview.php?ID=' + ID + '&date=' + date, 'overview', load_unique_graph(ID));
}

function load_unique_graph(ID){
	ajaxRequest('view/unique_graph.php?ID=' + ID + '&date=' + date, 'unique_graph', load_pageview_graph(ID));
}

function load_pageview_graph(ID){
	ajaxRequest('view/pageview_graph.php?ID=' + ID + '&date=' + date, 'pageview_graph', load_small_topcontent(ID));
}

function load_small_topcontent(ID){
	ajaxRequest('view/stats_small_topcontent.php?ID=' + ID + '&date=' + date, 'top_content', load_small_keywords(ID));
}

function load_small_keywords(ID){
	ajaxRequest('view/stats_small_keywords.php?ID=' + ID + '&date=' + date, 'keywords', load_small_referrers(ID));
}

function load_small_referrers(ID){
	ajaxRequest('view/stats_small_referrers.php?ID=' + ID + '&date=' + date, 'referrers', closeLoader);
}

//displaying full reports

function load_full_referrers(ID, start){
	initLoader();
	ajaxRequest('view/stats_full_referrers.php?ID=' + ID + '&date=' + date + '&start=' + start, 'left', closeLoader);
}

function load_full_content(ID, start){
	initLoader();
	ajaxRequest('view/stats_full_content.php?ID=' + ID + '&date=' + date + '&start=' + start, 'left', closeLoader);
}

function load_full_keywords(ID, start){
	initLoader();
	ajaxRequest('view/stats_full_keywords.php?ID=' + ID + '&date=' + date + '&start=' + start, 'left', closeLoader);
}