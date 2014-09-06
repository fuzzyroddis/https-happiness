#!/usr/bin/nodejs
//npm install https
function save() {
	fs = require('fs');
	fs.writeFile("https.txt", JSON.stringify(hosts), function (err) {
		if(err)
			console.log("ERROR: Could not write to https.txt");
		else
			console.log("+https.txt")
	});
}

hosts = []; //array for hosts

fs = require('fs');
url = require("url");
var https = require('https');
//var webshot = require('webshot');
//var capture = require('capture');

fs.readFile('urls.txt', function (error, data) {
	if(!error)
	{
		list = JSON.parse(data.toString());
		list.forEach(function (item) {
			(function (item) {
			var urlParts = url.parse(item.url);

			var options = {
			  hostname: urlParts.host,
			  port: 443,
			  path: urlParts.path,
			  method: 'GET'
			};

			https.get(options, function(res) {
				//console.log("statusCode: ", res.statusCode);
				//console.log("headers: ", res.headers);
				/* Don't forget to handle redirects especially https->http */

			  	httpsUrl = 'https://'+urlParts.host+urlParts.path;
			  	//Take a screenshot

				httpImage = 'data/screenshots/http/'+urlParts.host+'.png';
				httpsImage = 'data/screenshots/https/'+urlParts.host+'.png';

				/*webshot(item.url, httpImage, function(err) {
				  console.log(err);
				});

				webshot(httpsUrl, httpsImage, function(err) {
				  console.log(err);
				});*/

			  	/*capture([item.url], { out: httpImage }, function (err) {console.log(err);});
			  	capture([httpsUrl], { out: httpsImage }, function (err) {});*/

			    list.push({'url'		: item.url,
			    		  'https'		: httpsUrl,
			    		  //'length'		: d.length(),
			    		  'httpcapture'	: httpImage,
			    		  'httpscapture': httpsImage,
			    		  'page_title'	: item.page_title,
			    		  'department'	: item.department
			    });

			    //console.log(httpsUrl);

			    save();

			}).on('error', function(e) {
			  //console.error(e);
			});
			})(item); //closure?
		});
		save();
	}
});