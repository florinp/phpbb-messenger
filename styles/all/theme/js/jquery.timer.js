(function($){
    
    jQuery.timer = function(interval, callback, options) {
        var options = jQuery.extend({ reset: 500 }, options);
        var interval = interval || options.reset;
        
        if(!callback) return false;
        
        var Timer = function(interval, callback, disabled) {
            var self = this;
            
            this.internalCallback = function() {
                callback(self);
            };
            
            this.stop = function() {
                if(this.state === 1 && this.id) {
                    clearInterval(self.id);
                    this.state = 0;
                    return true;
                }
            };
            
            this.reset = function(time) {
                if(self.id) {
                    clearInterval(self.id);
                }
                var time = time || options.reset;
                this.id = setInterval($.proxy(this.internalCallback, this), time);
                this.state = 1;
                return true;
            }
            
            this.pause = function() {
                if(self.id && this.state === 1) {
                    clearInterval(this.id);
                    this.state = 2;
                    return true;
                }
                return false;
            }
            
            this.resume = function() {
                if(this.state === 2) {
                    this.state = 1;
					this.id = setInterval($.proxy(this.internalCallback, this), this.interval);
					return true;
                }
                return false;
            }
            
            this.interval = interval;
            
            if (!disabled) {
				this.id = setInterval($.proxy(this.internalCallback, this), this.interval);
				this.state = 1;
			}
            
        };
        
        return new Timer(interval, callback, options.disabled);
    };
    
})(jQuery);
