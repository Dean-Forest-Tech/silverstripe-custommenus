<div id="treepanes" style="overflow-y: auto;">
	<h2 id="heading_sitetree" class=" selected title"><% _t('CUSTOMMENUS.LEFTTITLE','Custom Menus') %></h2>
	
	<div id="sitetree_holder">
		$LeftMenuForm
		
		<div id="sitetree_ul">
			<% if get_menus %>
			<ul id="sitetree" class="tree"> 
				<li id="record-0" class="Root nodelete last">
					<span class="a Root nodelete last">
                                            <span class="b"><span class="c"><a href="admin/menus/root">Menus</a></span></span>
                                        </span>
					<ul>
						<% control get_menus %><li id="record-$ID" class="$ClassName $Current">
                                                   <a href="admin/menus/show/$ID">$Title</a>
						</li><% end_control %>
					</ul>
				</li>
			</ul><% end_if %>
		</div>
	</div>
</div>
