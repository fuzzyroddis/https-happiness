#!/usr/bin/nodejs
//npm install https webshot
//apt-get install phantomjs
function save() {
	fs = require('fs');
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

hosts = []; //array for hosts
done = []; //array for jobs already done

fs = require('fs');
url = require("url");
var https = require('https');
var webshot = require('webshot');

fs.readFile("done.txt", function (error, data) {
	if(!error)
	{
		list = JSON.parse(data.toString());
		list.forEach(function (item) {
			done.push(item.url);
		});
	}
}

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

				//**todo** if not exists
				webshot(item.url, httpImage, {'phantomPath' : '/usr/bin/phantomjs'}, function(err) {
				  console.log(item.url);
				  console.log(err);
				});

				webshot(httpsUrl, httpsImage, {'phantomPath' : '/usr/bin/phantomjs'}, function(err) {
				  console.log(httpsUrl);
				  console.log(err);
				});

			    hosts.push({'url'		: item.url,
			    		  'https'		: httpsUrl,
			    		  //'length'		: d.length(),
			    		  'httpcapture'	: httpImage,
			    		  'httpscapture': httpsImage,
			    		  'page_title'	: item.page_title,
			    		  'department'	: item.department
			    });

			    done.push({'url' : item.url});

			    //console.log(httpsUrl);

			    save();

			}).on('error', function(e) {
			  //console.error(e);
			  done.push({'url' : item.url});
			});
			})(item); //closure?
		});
		save();
	}
});