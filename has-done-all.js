#!/usr/bin/nodejs
/*
	Compares done.txt with urls.txt
*/
var fs = require('fs');

Array.prototype.diff = function(a) {
    return this.filter(function(i) {return a.indexOf(i) < 0;});
};

urls = [];
done = [];

fs.readFile("done.txt", function (error, data) {
	if(!error)
	{
		list = JSON.parse(data.toString());
		list.forEach(function (item) {
			done.push(item.url);
		});

		fs.readFile("urls.txt", function (error, data) {
			if(!error)
			{
				list = JSON.parse(data.toString());
				list.forEach(function (item) {
					urls.push(item.url);
				});

				console.log(urls.length);
				console.log(done.length);
				console.log(urls.diff(done));
			}
			else
			{
				console.log(error);
			}
		});
	}
	else
	{
		console.log(error);
	}
});