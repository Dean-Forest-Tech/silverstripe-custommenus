/**
 * Configuration for the left hand tree
 */
if(typeof SiteTreeHandlers == 'undefined') SiteTreeHandlers = {};
SiteTreeHandlers.parentChanged_url = 'admin/menus/ajaxupdateparent';
SiteTreeHandlers.orderChanged_url = 'admin/menus/ajaxupdatesort';
SiteTreeHandlers.loadPage_url = 'admin/menus/getitem';
SiteTreeHandlers.loadTree_url = 'admin/menus/getsubtree';
SiteTreeHandlers.showRecord_url = 'admin/menus/show/';
SiteTreeHandlers.controller_url = 'admin/menus';