#!/usr/bin/nodejs
//npm install cheerio request
var atoz = 'abcdefghijklmnopqrstuvwxyz'
var list = []; //array containing objects that contain url, title, owner

function save() {
	fs = require('fs');
	fs.writeFile("urls.txt", JSON.stringify(list), function (err) {
		if(err)
			console.log("ERROR: Could not write to urls.txt");
		else
			console.log("+urls.txt")
	});
}

function get_page(letter, number) {
	if(!number)
	{
		number = 0;
	}

	var request = require('request');
	var options = {
		url: 'http://australia.gov.au/views/ajax',
		method: 'POST',
		headers: {
			'Cookie': 'has_js=1', 
			'Origin': 'http://australia.gov.au', 
			'User-Agent': 'Mozilla/5.0 (BeOS; U; BeOS BePC; en-US; rv:1.9a1) Gecko/20051112', 
			'Content-Type': 'application/x-www-form-urlencoded', 
			'Referer': 'http://australia.gov.au/directories/australian-government-directories/a-to-z-list-of-government-sites', 
			'X-Requested-With': 'XMLHttpRequest'
		},
		body: 'view_name=a_to_z_gov_sites&view_display_id=block_1&view_args='+encodeURIComponent(letter)+'&view_path=atozgovsites%2F'+encodeURIComponent(letter)
				+'&view_base_path=atozgovsites&pager_element=0&page='+encodeURIComponent(number)
	};
	request(options,
		function (error, response, body) {
			if(!error && response.statusCode == 200) {
				data = JSON.parse(body);

				var cheerio = require('cheerio'),
					$ = cheerio.load(data[2].data);

				if($('.linklist').length) //got urls
				{
					$('.linklist').each(function () {
						list.push({
							'url' : $(this).find(".field-content a").attr('href'),
							'page_title' : $(this).find(".field-content a").html(),
							'department' : $(this).find("p.resultURL").html()
						});
					});

					console.log("+{"+letter+","+number+"}");
					save();
					//try next page
					get_page(letter, number+1);
				}
				else
				{
					console.log("0{"+letter+","+number+"}");
				}
			}
		});
}
for(i=0;i<atoz.length;i++)
{
	get_page(atoz.charAt(i));
}