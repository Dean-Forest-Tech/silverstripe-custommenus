<div id="form_actions_right" class="ajaxActions"></div>

<% if EditForm %>
	$EditForm
<% else %>
	<form id="Form_EditForm" action="admin?executeForm=EditForm" method="post" enctype="multipart/form-data">
		<h1><% _t('CustomMenuAdmin.MODULETITLE','Custom Menus') %></h1>

		<p><% _t('CustomMenuAdmin.CHOOSEMENU','Please choose a menu from the left, or select "Create Menu" to create a new menu.') %></p>
	</form>
<% end_if %>

<p id="statusMessage" style="visibility:hidden"></p>