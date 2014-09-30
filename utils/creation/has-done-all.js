#!/usr/bin/nodejs
/*
	Compares done.txt with urls.txt
*/
var fs = require('fs');

Array.prototype.diff = function(a) {
    return this.filter(function(i) {return a.indexOf(i) < 0;});
};

var arrayUnique = function(a) {
    return a.reduce(function(p, c) {
        if (p.indexOf(c) < 0) p.push(c);
        return p;
    }, []);
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

				console.log(arrayUnique(urls).length);
				console.log(arrayUnique(done).length);
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