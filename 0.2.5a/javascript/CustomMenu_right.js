Behaviour.register({
	'#Form_EditForm' : {
		getPageFromServer : function(id) {
			statusMessage("loading...");
			
			var requestURL = 'admin/menus/show/' + id;
			
			this.loadURLFromServer(requestURL);
			
			$('sitetree').setCurrentByIdx(id);
		}
	}
});