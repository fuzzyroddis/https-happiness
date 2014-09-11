#!/usr/bin/nodejs

hosts = []; //array for hosts
done = []; //array for jobs already done

var fs = require('fs');
var url = require("url");
var https = require('https');

function save() {
	fs.writeFile("https.txt", JSON.stringify(hosts), function (err) {
		if(err)
			console.log("ERROR: Could not write to https.txt");
		else
			console.log("+https.txt")
	});

	fs.writeFile("done.txt", JSON.stringify(done), function (err) {
		if(err)
			console.log("ERROR: Could not write to done.txt");
		else
			console.log("+done.txt")
	});
}

fs.readFile('urls.txt', function (error, data) {
	if(!error)
	{
		list = JSON.parse(data.toString());
		list.forEach(function (item) {
			(function (item) {
			if(done.indexOf(item.url) == -1)
			{
				var urlParts = url.parse(item.url);

				var options = {
				  hostname: urlParts.host,
				  port: 443,
				  path: urlParts.path,
				  method: 'GET'
				};

				https.get(options, function(res) {

				  	httpsUrl = 'https://'+urlParts.host+urlParts.path;

				    hosts.push({'url'		: item.url,
				    		  'https'		: httpsUrl,
				    		  'host'		: urlParts.host,
				    		  'page_title'	: item.page_title,
				    		  'department'	: item.department,
				    		  'status_code'	: res.statusCode,
				    		  'headers'		: res.headers
				    });

				    done.push({'url' : item.url});

				    //console.log(httpsUrl);

				    save();

				}).on('error', function(e) {
				  //console.error(e);
				  done.push({'url' : item.url});
				  save();
				});
			}
			})(item); //closure?
		});
		save();
	}
});