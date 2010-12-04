<h2><% _t('CustomMenus.Title','Custom Menus') %></h2>
<div id="treepanes" style="overflow-y: auto;">
    $LeftMenuForm
    <% if get_menus %>
        <ul id="sitetree" class="tree">
            <li id="record-root" class="Root last">
                <a><strong><% _t('CustomMenus.TreeTitle','Menus') %></strong></a>
                    <ul>
                        <% control get_menus %><li id="record-$ID"><a href="admin/menus/show/$ID">$Title</a></li><% end_control %>
                    </ul>
            </li>
        </ul>
    <% end_if %>
</div>