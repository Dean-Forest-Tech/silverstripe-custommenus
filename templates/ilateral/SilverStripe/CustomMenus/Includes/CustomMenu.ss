<% if $Menu.Exists %>
    <ul class="menu-{$Slug}">
        <% loop $Menu %>
            <li class="menu-{$Slug}-item $LinkingMode $FirstLast">
                <a class="menu-{$Slug}-link" href="$Link" title="$Title.XML">$MenuTitle.XML</a>
            </li>
        <% end_loop %>
    </ul>
<% end_if %>
