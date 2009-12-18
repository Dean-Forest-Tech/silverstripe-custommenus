<div id="treepanes" style="overflow-y: auto;">
	<h2 class="title"><% _t('CUSTOMMENUS','Custom Menus') %></h2>
	
	<div id="sitetree_holder">
		<ul id="TreeActions">
			<li class="action" id="MenuAdd"><button><% _t('CREATENL','Create Menu') %></button></li>
			<li class="action" id="MenuDelete"><button><% _t('DEL','Delete Menu') %></button></li>
		</ul>
		<div id="sitetree_ul">
			<% if get_menus %>
			<ul id="sitetree" class="tree"> 
				<li id="record-0" class="Root nodelete last">
					<strong>Menus</strong>
					<div style="clear:both;"></div>
					<ul>
						<% control get_menus %><li id="record-$ID" class="class-$ClassName">
							<span class="a class-$ClassName"><a href="admin/menus/show/$ID">$Title</a></span>
						</li><% end_control %>
					</ul>
				</li>
			</ul><% end_if %>
		</div>
	</div>
</div>
