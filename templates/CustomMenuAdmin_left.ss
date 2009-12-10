<div id="treepanes" style="overflow-y: auto;">
	<h3 class="title"><a href="#" tabindex="-1"><% _t('CUSTOMMENUS','Custom Menus') %></a></h3>
	
	<div id="sitetree_holder">		
		<div id="sitetree_ul">
			<% if get_menus %>
			<ul id="sitetree" class="tree"> 
				<li id="record-0" class="Root nodelete last">
					<strong>Menus</strong>
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
