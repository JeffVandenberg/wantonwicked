var CM = { };

/*-------------------------------- Gallery --------------------------------*/

CM.Gallery = (function() {
	function Gallery(itemInfos, showThumbnails) {	
		this.element = $('#flipbook');
		
		this.unit = -1; // Not yet loaded
		
		this.items = [];
		
		var base = this;
		
		var i = 0;
		
		itemInfos.forEach(function(info) {
			var item = new GalleryItem(i, info);
			
			base.items.push(item);
			
			i++;
		});
		
		this.captionEl = $('#caption');
		
		this.prevLink = this.element.find('.previousLink');
		this.nextLink = this.element.find('.nextLink');
		
		this.prevLink.on('click', this.viewPrevious.bind(this));
		this.nextLink.on('click', this.viewNext.bind(this));
		
		if(this.items.length == 1) {
			this.prevLink.hide();
			this.nextLink.hide();
		}

		this.canvasEl = this.element.find('.canvas');
		this.artwork = this.element.find('.artwork');
		
		if(showThumbnails) {
			this.thumbStrip = new CM.Thumbnails('#thumbnails', /*stage*/ base);
		}
		
		var hash = window.location.hash;
		
		if(hash && hash.length > 1) {
			var hashValue = hash.split("#")[1];
			
			var itemIndex = parseInt(hashValue, 10) - 1;
				
			this.view(itemIndex);
		}
		else {
			this.view(0);
		}
		
		$(window).on('hashchange', this.onHashChange.bind(this));
	}
	
	Gallery.prototype.onHashChange = function(e) {	
		var hash = window.location.hash;
		
		if(hash && hash.length > 1) {
			
			var hashValue = hash.split('#')[1];
			
			var itemIndex = parseInt(hashValue, 10) - 1;
					
			if(itemIndex != this.unit) {			
						
				this.view(itemIndex);	
			}
		}
	};
	
	Gallery.prototype.view = function(itemIndex) {
		if(this.items.length != 0 && ((itemIndex + 1) > this.items.length)) {
			this.view(0); // LOOP
			
			return;
		}
		
		if(itemIndex == this.unit) {	
			return; // don't load again.
		}		
				
		var item = this.items[itemIndex];
				
		if(!item) {			
			return;
		}
		
		this.unit = itemIndex;
		
		window.location = "#" + item.number;
		
		var previousItem = this.items[itemIndex - 1];
		var nextItem = this.items[itemIndex + 1];
		
		if(previousItem) {
			previousItem.load();
		}
		
		if(nextItem) {
			nextItem.load();
		}
		
		if(itemIndex == 0) {
			this.prevLink.addClass('disabled');
		}
		else {
			this.prevLink.removeClass('disabled');
		}
		
		if((itemIndex + 1) == this.items.length) {
			this.nextLink.addClass('end');
		}
		else {
			this.nextLink.removeClass('end');
		}
		
		this.animate();
	};
	
	Gallery.prototype.viewNext = function(e) {
		this.view(this.unit + 1);
		
		return false;
	};
	
	Gallery.prototype.viewPrevious = function(e) {
		this.view(this.unit - 1);
		
		return false;
	};
	
	Gallery.prototype.animate = function() {	
		var item = this.items[this.unit];
		
		var base = this;
		
		// Hide the caption
		this.captionEl.hide();
					
		// Hide the artwork		
		this.artwork.css('opacity', 0);
		
		if(!item.media.isLoaded) {
			this.canvasEl.addClass('loading');
		}
		
		// Fade the image in once it loads
		item.load().done(this.onLoad.bind(this));
				
		// Set the caption
		if(item.description.length > 0) {
			this.captionEl.html(item.description);
			
			this.captionEl.show(); 
		}
		
		// Do the thumbnail stuff
		if(this.thumbStrip) {						
			this.thumbStrip.selectThumbnail(this.unit);
		}
	};
	
	Gallery.prototype.onLoad = function(media) {		
		var item = this.items[this.unit];
							
		var base = this;
			
		var newArtworkEl = $('<div />').attr('class', 'artwork');
		
		newArtworkEl.css({
			backgroundImage: 'url("' + media.url + '")',
			opacity: 0
		});
		
		base.artwork.replaceWith(newArtworkEl);
		
		base.artwork = newArtworkEl;
		
		base.artwork.stop().animate({ opacity: 1 }, 500, function() { 
			base.canvasEl.removeClass('loading');
		});	
	}
	
	return Gallery;
})();

var GalleryItem = (function() {
	function GalleryItem(index, info) {
		this.description = info.description;
		
		this.media = info.media;
		
		this.image = new Image();
		
		this.index = index;
		this.number = index + 1;
		this.isLoading = false;

		this.media.isLoaded = false;
		
		var rendition = this.media.renditions[0];
		
		this.media.width = rendition.width;
		this.media.height = rendition.height;
		this.media.url = rendition.url;
		
		this.errorCount = 0;
	}
	
	GalleryItem.prototype.load = function() {
		var base = this;
		
		var defer = $.Deferred();
		
		if(this.media.isLoaded) {
		
			defer.resolve(this.media);
						
			return defer;
		}
		
		if(this.isLoading) {
			defer.resolve(this.media);
			
			return defer;
		}
		
		this.isLoading = true;		
				
		$(this.image).on('load', function() {
			base.media.isLoaded = true;
			
			defer.resolve(base.media);
		});
				
		this.image.onerror = function() {
			base.errorCount++;
			base.isLoading = false;
			
			if(base.errorCount < 2) { 
				base.load();
			}		
		};

		this.image.src = this.media.url;
		
		return defer;
	};
	
	return GalleryItem;
})();

/*-------------------------------- Video Stage --------------------------------*/

CM.VideoStage = (function() {
	function VideoStage(itemInfos, showThumbnails) {		

		this.items = itemInfos;
		
		this.unit = 0;
		
		this.element = $('#videoContainer');		

		if(showThumbnails) {
			this.thumbStrip = new CM.Thumbnails('#thumbnails', this);
		}
		
		this.captionWrapper = this.element.find('.caption');
		
		var hash = window.location.hash;
		
		if(hash && hash.length > 1) {
			var hashValue = hash.split("#")[1];
			
			var itemIndex = parseInt(hashValue, 10) - 1;
						
			this.view(itemIndex);
		}
		
		if(this.thumbStrip) {						
			this.thumbStrip.selectThumbnail(this.unit);
		}
		
		$(window).on('hashchange', this.onHashChange.bind(this));
	}

	VideoStage.prototype.onHashChange = function(e) {	
		var hash = window.location.hash;
		
		if(hash && hash.length > 1) {
			
			var hashValue = hash.split('#')[1];
			
			var itemIndex = parseInt(hashValue, 10) - 1;
					
			if(itemIndex != this.unit) {			
						
				this.view(itemIndex);	
			}
		}
	};
	
	VideoStage.prototype.view = function(index) {	
	
		var item = this.items[index];

		if(index == this.unit) {					
			return; // don't load again.
		}
		
		if(item) {
			this.unit = index;
			
			window.location = "#" + (index + 1);
				
			this.internalView(item);
		}		
	};
	
	VideoStage.prototype.internalView = function(item) {
		
		if(!item || !item.media) {
			return;
		}
		
		var renditions = item.media.renditions;
		
		var sources = [];
		
		renditions.forEach(function(r) {						
			sources.push(new MediaSource(r.url, r.type));
		});
		
		var caption = item.description;
		var poster = item.media.poster.renditions[0];
	
		var captionEl = this.element.find('.caption');
		var captionText = captionEl.find('div');
		
		if(caption) {			
			captionText.html(caption);
			
			this.element.removeClass('noCaption');
		}
		else {
			this.element.addClass('noCaption');
		}
								
		player.setPosterSrc(poster.url);
		
		player.setSources(sources);
		
		player.reload();
		
		if(this.thumbStrip) {						
			this.thumbStrip.selectThumbnail(this.unit);
		}
	}
	
	return VideoStage;
})();

/*-------------------------------- Thumbnails --------------------------------*/

CM.Thumbnail = (function() {
	function Thumbnail(index, element, strip) {		
		this.element = element;
		this.index = index;
		
		this.strip = strip;
		this.stage = strip.stage;
		
		this.imageEl = element.find('img');
		
		this.width = this.imageEl.width();
		
		this.element.on('click', this.select.bind(this));
	}
	
	Thumbnail.prototype.select = function() {
	
		this.strip.element.find('.selected').removeClass('selected');
		
		this.element.addClass('selected');
		
		if(this.stage) {
			if(this.stage.unit != this.index) {
				this.stage.view(this.index);
			}
		}
	};
	
	Thumbnail.prototype.load = function() {
		if(this.imageEl.attr('src').endsWith("c.gif")) {
			this.imageEl.attr('src', this.imageEl.data('src'));
		}
	};
	
	return Thumbnail;
})();

CM.Thumbnails = (function() {
	function Thumbnails(element, stage) {		
		this.element = $(element);
		
		this.stage = stage;
		
		this.thumbnails = [];
		
		var i = 0;
		
		this.setCount = 0;
		this.currentSet = 0;
		
		this.setEls = this.element.find('.set');
		
		var base = this;
		
		this.setEls.each(function() {
			var setEl = $(this);
			
			base.setCount++;
			
			// Remove the text nodes
			setEl.contents().filter(function() { return this.nodeType === 3; }).remove();

			setEl.find('.thumb').each(function() {
				var liEl = $(this);
				
				var thumbnail = new CM.Thumbnail(i, liEl, base);
								
				thumbnail.setNumber = base.setCount;
				
				base.thumbnails.push(thumbnail);
				
				i++;
			});
		});
		
				
		this.viewportEl = this.element.find('.viewport');
		this.prevEl = this.element.find('.previous');
		this.nextEl = this.element.find('.next');
		
		this.prevEl.on('click', this.loadPreviousSet.bind(this));
		this.nextEl.on('click', this.loadNextSet.bind(this));
		
		if(this.setCount == 1) {
			this.prevEl.hide();
			this.nextEl.hide();
		}
		
		
	};
	
	Thumbnails.prototype.selectThumbnail = function(index) {		
		var thumbnail = this.thumbnails[index];
		
		if(thumbnail) {
			thumbnail.select();
						
			this.loadSet(thumbnail.setNumber);
		}
	};
	
	Thumbnails.prototype.loadPreviousSet = function() {
		this.loadSet(this.currentSet - 1);
	};
	
	Thumbnails.prototype.loadNextSet = function() {	
		this.loadSet(this.currentSet + 1);
	};
	
	Thumbnails.prototype.loadSet = function(setNumber) {
		if(!this.hasSet(setNumber) || this.currentSet == setNumber) {
			return;
		}
		
		this.currentSet = setNumber;
		
		if(setNumber == 1) {
			this.prevEl.addClass('disabled');
		}
		else {
			this.prevEl.removeClass('disabled');
		}
		
		if(this.hasSet(setNumber + 1)) {
			this.nextEl.removeClass('disabled');
		}
		else {
			this.nextEl.addClass('disabled');
		}
		
		var setEl = $(this.setEls[setNumber - 1]);
		
		if(setEl.length > 0) {			
			setEl.find('img.lazy').each(function() {
				var imgEl = $(this);
					
				imgEl.removeClass('lazy').attr('src', imgEl.data('src'));
			});
		
			this.moveTo(setEl,{ duration: 0.5 }  );	
		}		
	};
	
	Thumbnails.prototype.hasSet = function(setNumber) {	
		return setNumber <= this.setCount && setNumber > 0;
	};
	
	Thumbnails.prototype.moveTo = function(element, options) {    	
		var x = element.position().left;
		
		$('.viewport').stop().animate({ scrollLeft: x }, { duration: 500, easing: 'easeOutQuint' });
	};
	
	return Thumbnails;
})();