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

fs.readFile("urls.txt", function (error, data) {
	if(!error)
	{
		list = JSON.parse(data.toString());
		list.forEach(function (item) {
			urls.push(item.url);
		});
	}
	else
	{
		console.log(error);
	}
});

fs.readFile("done.txt", function (error, data) {
	if(!error)
	{
		list = JSON.parse(data.toString());
		list.forEach(function (item) {
			done.push(item.url);
		});
	}
	else
	{
		console.log(error);
	}
});

console.log(urls.length);
console.log(done.length);
console.log(urls.diff(done));