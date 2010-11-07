jQuery(function($)
{
	$.fn.extend(
	{
		jAccordion: function(settings)
		{
			return this.each(function()
			{
				var self = $(this);
				
				settings = $.extend({}, $.jAccordion.settings, settings);
				
				var items = [];
				
				self.addClass("jAccordion")
				.children("li")
				.addClass("jAccordion-item")
				.each(function()
				{
					var current = $(this);
					
					items.push(current);
				})
				.children("h2")
				.addClass("jAccordion-handle")
				.siblings("ul")
				.addClass("jAccordion-bellows")
				.children("li")
				.addClass("jAccordion-bellow");
				
				
				if(settings.navigation)
				{
					var current = this.find("a").filter(function() { return this.href == location.href; });
					
					if(current.length)
						settings.active = current.filter(settings.header).length
							? current
							: current.addClass("jAccordion-active").parent().parent().prev();
				}

				var first = items[0], last = items[self.length - 1];
				
				// calculate active if not specified, using the first header
				var headers = self.find(settings.header),
					active = findActive(settings.active),
					running = 0;
				
				if(settings.fillSpace)
				{
					var maxHeight = this.parent().height();
					
					headers.each(function()
					{
						maxHeight -= this.scrollHeight;
					});
					
					var maxPadding = 0;
					
					headers.next().each(function()
					{
						var current = $(this);
						
						maxPadding = Math.max(maxPadding, current.innerHeight() - current.height());
					}).height(maxHeight - maxPadding);
				}
				else if(settings.autoheight)
				{
					var maxHeight = 0;

					headers.next().each(function()
					{
						maxHeight = Math.max(maxHeight, this.scrollHeight);
					}).height(maxHeight);
				}
				
				headers
					.not(active || "")
					.next()
					.hide();
				
				active.parent().andSelf().addClass(settings.selectedClass);
				
				function findActive(selector)
				{
					return selector != undefined
						? typeof selector == "number"
							? headers.filter(":eq(" + selector + ")")
							: headers.not(headers.not(selector))
						: !selector
							? $("<div>")
							: headers.filter(":eq(0)");
				}
				
				function toggle(toShow, toHide, data, clickedActive, down)
				{
					var complete = function(cancel)
					{
						running = cancel ? 0 : --running;
						
						if(running)
							return;
						
						if(settings.clearStyle)
							toShow.add(toHide).css({height: "", overflow: ""});
						
						// trigger custom change event
						self.trigger("change", data);
					};
					
					// count elements to animate
					running = toHide.length == 0 ? toShow.length : toHide.length;
					
					if(settings.animated)
					{
						if(!settings.sticky && clickedActive)
						{
							toShow.slideToggle(settings.animated);
							complete(true);
						}
						else
						{
							$.jAccordion.animations[settings.animated](
							{
								toShow: toShow,
								toHide: toHide,
								complete: complete,
								down: down
							});
						}
					}
					else
					{
						if(!settings.sticky && clickedActive)
						{
							toShow.toggle();
						}
						else
						{
							toHide.hide();
							toShow.show();
						}
						
						complete(true);
					}
				}
				
				function clickHandler(event)
				{
					// called only when using activate(false) to close all parts programmatically
					if(!event.target && !settings.sticky)
					{
						active.parent().andSelf().toggleClass(settings.selectedClass);
						
						var toHide = active.next();
						var toShow = active = $([]);
						
						toggle(toShow, toHide);
						
						return false;
					}
					
					// get the click target
					var clicked = $(event.target);
					
					// due to the event delegation model, we have to check if one
					// of the parent elements is our actual header, and find that
					if(clicked.parents(settings.header).length)
						while(!clicked.is(settings.header))
							clicked = clicked.parent();
					
					var clickedActive = clicked[0] == active[0];
					
					// if animations are still active, or the active header is the target, ignore click
					if(running || (settings.sticky && clickedActive) || !clicked.is(settings.header))
						return false;
		
					// switch classes
					active.parent().andSelf().toggleClass(settings.selectedClass);
					
					if(!clickedActive)
					{
						clicked.parent().andSelf().addClass(settings.selectedClass);
					}
					
					// find elements to show and hide
					var toShow = clicked.next(),
						toHide = active.next(),
						data = [clicked, active, toShow, toHide],
						down = headers.index(active[0]) > headers.index(clicked[0]);
					
					active = clickedActive ? $([]) : clicked;
					toggle(toShow, toHide, data, clickedActive, down);
					
					return false;
				};
				
				function activateHandler(event, index)
				{
					// IE manages to call activateHandler on normal clicks
					if(arguments.length === 1)
						return;
					
					// call clickHandler with custom event
					clickHandler(
					{
						target: findActive(index)[0]
					});
				};
		
				self.bind(settings.event, clickHandler).bind("activate", activateHandler);
			});
		},
		
		activate: function(index)
		{
			return this.trigger("activate", [index]);
		},
		
		unaccordion: function()
		{
			this.find("*").andSelf().unbind();
			
			return this;
		}
	});
});

jQuery.extend(
{
	jAccordion:
	{
		settings:
		{
			selectedClass: "selected",
			sticky: true,
			animated: "slide",
			event: "hover",
			header: "a",
			autoheight: true,
			navigation: false,
			speed: 300
		},
		
		Initialize: function()
		{
			var self = this;

			var images = [];
			images.type = "array";
			
			jQuery(".jAccordion *").each(function()
			{
				var current = $(this);
				
				var bg = current.css("background-image");
				
				if(bg !== "none")
					if(bg.match(/^url[("']+(.*)[)"']+$/i))
						images.push(RegExp.$1);
			});
			
			self.PreloadImages(images);
			self.FixFlicker();
		},
		
		PreloadImages: function()
		{
			for(var i in arguments.length)
				if(arguments[i].type === "array")
					for(var j in arguments[i])
						jQuery("<img>").attr("src", arguments[i][j]);
				else
					jQuery("<img>").attr("src", arguments[i]);
		},
		
		FixFlicker: function()
		{
			if($.browser.msie === true)
				try { document.execCommand("BackgroundImageCache", false, true); } catch(e) {}
		},
		
		animations:
		{
			slide: function(settings, additions)
			{
				settings = $.extend(
				{
					easing: "swing",
					duration: 300
				}, settings, additions);
				
				if(!settings.toHide.size())
				{
					settings.toShow.animate({height: "show"}, settings);
					return;
				}
				
				var hideHeight = settings.toHide.height(),
					showHeight = settings.toShow.height(),
					difference = showHeight / hideHeight;
				
				settings.toShow.css({height: 0, overflow: 'hidden'}).show();
				
				settings.toHide.filter(":hidden").each(settings.complete).end().filter(":visible").animate({height:"hide"},
				{
					step: function(now)
					{
						settings.toShow.height((hideHeight - (now)) * difference);
					},
					duration: settings.duration,
					easing: settings.easing,
					complete: settings.complete
				});
			},
			
			bounceslide: function(settings)
			{
				this.slide(settings,
				{
					easing: settings.down ? "bounceout" : "swing",
					duration: settings.down ? 1000 : 200
				});
			},
			
			easeslide: function(settings)
			{
				this.slide(settings,
				{
					easing: "easeinout",
					duration: 700
				})
			}
		}
	}
});

jQuery(document).ready(function()
{
	jQuery.jAccordion.Initialize();
});