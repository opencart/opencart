function getURLVar(key) {
    var value = [];
    
    var query = String(document.location).split('?');
    
    if (query[1]) {
        var part = query[1].split('&');

        for (i = 0; i < part.length; i++) {
            var data = part[i].split('=');
            
            if (data[0] && data[1]) {
                value[data[0]] = data[1];
            }
        }
        
        if (value[key]) {
            return value[key];
        } else {
            return '';
        }
    }
} 

$(document).ready(function() {
	route = getURLVar('route');
	
	if (!route) {
		$('#menu #home').addClass('active');
	} else {
		part = route.split('/');
		
		url = part[0];
		
		if (part[1]) {
			url += '/' + part[1];
		}		
		
		$('#menu a[href*=\'index.php?route=' + url + '\']').parents('li[id]').addClass('active');
	}	
});

// Chain ajax calls.
class Chain {
	constructor() {
		this.start = false;
		this.data = [];
	}

	attach(call) {
		this.data.push(call);

		if (!this.start) {
			this.execute();
		}
	}

	execute() {
		if (this.data.length) {
			this.start = true;

			var call = this.data.shift();

			var jqxhr = call();

			jqxhr.done(function() {
				chain.execute();
			});
		} else {
			this.start = false;
		}
	}
}

var chain = new Chain();