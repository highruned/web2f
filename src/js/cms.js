(function($)
{
	$.fn.jMenu = function(settings)
	{
		settings = $.extend({}, $.jMenu.Settings, settings);
		
		return this.each(function()
		{
			var self = this, $self = $(this);
			
			var location = new String(window.location);

			var children = $self.children("li");
			
			children.each(function()
			{
				var self2 = this, $self2 = $(this);
				
				var href = $self2.children("a").attr("href");
				
				if(href !== undefined)
				{
					if(location.search(href) !== -1)
					{
						$self2.addClass("active");
					}
				}
				
				$self2.hover
				(
					function()
					{
						$self2.toggleClass("hover");
					},
					function()
					{
						$self2.toggleClass("hover");
					}
				)
				.click
				(
					function()
					{
						children.removeClass("active");
						$self2.toggleClass("active");
					},
					function()
					{
						$self2.toggleClass("active");
					}
				);
			});
		});
	};
	
	$.jMenu =
	{
		Settings:
		{
			
		},

		Initialized: false,

		Initialize: function()
		{
			if(!this.Initialized)
			{

			}
		}
	};

	$(document).ready(function()
	{
		$.jMenu.Initialize();
	});
})(jQuery);


// Modules: jDrawer, jAccordion, jPagination, jMenu (includes highlight), jPreload (images, includes pngfix)

jQuery(function($)
{
	// library plugins
	$.fn.extend(
	{
		ajaxLinks: function() 
		{
		    return this.each(function()
		    {
		        var self = this;
		
		        $("a", this).click(function()
		        {
		            $(self).load($(this).attr("href"), function()
		            {
		                $(this).ajaxLinks();
		            }).find("a").unbind("click");
		            
		            return false;
		        });
		
		        return this;
		    });
		},

		stopSelection: function()
		{
			return this.each(function()
			{
				$(this).bind("mousedown", function()
				{
					this.onselectstart = function() { return false; };
					
					return false;
				});
			});
		}
	});
	
	$.fn.extend(
	{
		jPagination: function(settings)
		{
			return this.each(function()
			{
				
			});
		}
	});
	
	$.fn.extend(
	{
		jPreload: function(settings)
		{
			if($.browser.msie === true)
				try { document.execCommand("BackgroundImageCache", false, true); } catch(e) {}
			
			return this.each(function()
			{
				$("*", this).each(function()
				{
					var bg = $(this).css("background-image");
					
					if(bg !== "none")
						if(bg.match(/^url[(\"']+(.*)[)\"']+$/i))
							$("<img>").attr("src", RegExp.$1);
				});
			});
		}
	});
});


var CMS = {};

jQuery(function($)
{
	CMS =
	{
		Initialized: false,
		
		Menus: [],
		Settings: {},
		$: [], // cache jQuery object groups
		
		Initialize: function(settings)
		{
			var self = this; // save self...
			
			// if we haven't already initialized
			if(this.Initialized === false)
			{
				this.Initialized = true;
				
				$(".deleteAll").click(function()
				{
					var self = this;
					
					$(this).parents("table:first").find(":checkbox").each(function()
					{
						this.checked = self.checked;
					});
				});
				
				this.Settings = settings;

				this.$.button = $("input[type='button']");
				this.$.submit = $("input[type='submit']");
				this.$.checkbox = $("input[type='checkbox']");
				
				if(this.Settings.ajax == true)
				{
					var ajax = function()
					{
						var href = $(this).attr("href");

						// if the link contains the cms install url, or doesn't contain a domain
						if(href.search(self.Settings.url) !== -1 || href.search("http") === -1)
						{
							var path = href.replace(self.Settings.url, "");
							
							var load = function()
							{
								$.ajax(
								{
									type: "GET",
									
									url: self.Settings.url + "/" + path,
									
									data: {"ajax": 1},
									
									failure: function()
									{
										
									},
									
									success: function(data)
									{
										$(data).find("[id]").each(function()
										{
											var self = $(this);
											
											var id = self.attr("id");

											var section = $("html").find("#" + id).html(self.html()).find("a").each(ajax);
										});
									}
								});
		
								return false;
							};
							
							$(this).click(load);
						}
					};
					
					$("a").each(ajax);
				}
				
				this.Update();
			}
		},
		
		Update: function()
		{
			var self = this; // save self...
			
			this.$.button.css("cursor", "pointer");
			this.$.submit.css("cursor", "pointer");
		}
	};
	
	
	CMS.Menu =
	{
		
		
	};
});