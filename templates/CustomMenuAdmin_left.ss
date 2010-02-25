<div id="treepanes" style="overflow-y: auto;">
	<h2 id="heading_sitetree" class="selected title"><% _t('CUSTOMMENUS.LEFTTITLE','Custom Menus') %></h2>
	$LeftMenuForm
	<% if get_menus %>
	<div id="sitetree_holder">
	    <ul id="sitetree" class="tree">
		    <li id="record-root" class="Root last">
			<a href="admin/menus/root">Menus</a>
			    <ul>
				<% control get_menus %><li id="record-$ID" class="Menu"><a href="admin/menus/show/$ID">$Title</a></li><% end_control %>
			    </ul>
		    </li>
	    </ul>
	</div><% end_if %>

</div>