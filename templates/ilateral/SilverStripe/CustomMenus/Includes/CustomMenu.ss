<% if $Menu.Exists %>
    <% if $ShowHeading %><h3 class="menu-{$Slug}-heading">{$Menu.Holder.Title}</h3><% end_if %>
    <ul class="menu-{$Slug}">
        <% loop $Menu %>
            <li class="menu-{$Slug}-item $LinkingMode $FirstLast">
                <a class="menu-{$Slug}-link" href="$Link" title="$Title.XML">$MenuTitle.XML</a>
            </li>
        <% end_loop %>
    </ul>
<% end_if %>
