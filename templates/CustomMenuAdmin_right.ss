<div id="form_actions_right" class="ajaxActions"></div>

<% if EditForm %>
	$EditForm
<% else %>
	<form id="Form_EditForm" action="admin?executeForm=EditForm" method="post" enctype="multipart/form-data">
		<h1><% _t('CustomMenus.Title') %></h1>

		<p><% _t('CustomMenus.ChooseMenu') %>.</p>
	</form>
<% end_if %>

<p id="statusMessage" style="visibility:hidden"></p>