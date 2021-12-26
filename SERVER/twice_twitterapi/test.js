var Twit = require('twitter'); var requestify = require('requestify'); 
var client = new Twit({
  consumer_key: '',
  consumer_secret: '',
  access_token_key: '',
  access_token_secret: ''
});
client.stream('user', {screen_name: 'JYPETWICE'}, function(stream){
	stream.on('data', function(event) {
		var unirest = require('unirest');
		unirest.post('http://ialpha.kr:9900/~hama/twiceschedule_admin/send.php')
		.header('Accept', 'application/x-www-form-urlencoded')
		.send({ "message": event.text })
		.end(function (response) {
		  console.log(event.text);
		});
	});
	stream.on('error', function(error) {
		throw error;
	});
});
