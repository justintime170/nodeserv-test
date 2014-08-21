"use strict";


var fs = require('fs');
var url = require('url');
var bcrypt = require('bcrypt');

var express = require('express');
var app = express();

//app.use(express.cookieParser());
/*app.use(session(
{
	genid : function(req)
	{
		bcrypt.hash(getDateTime(), 8, function(err, hash)
	    {
	    	return hash;
	    });
	},
	secret : 'sitetestsecret',
	cookie : { maxAge: 60000, secure: true },
	resave :  true,
	saveUninitialized : true
}));*/

//app.listen(90);

var http = require('http'),
	session = require('sesh').magicSession();
var server = http.createServer(app);
var bodyParser = require('body-parser');

var mysql = require('mysql');
var pool  = mysql.createPool(
{
	connectionLimit : 10,
	host : 'localhost',
	user : 'sitelogin',
	password : 'sitelogin',
	database : 'site_core'
});

/*function handler (req, res) {
	console.log("New Request...");
	fs.readFile(__dirname + "/newsite" + url.parse(req.url).pathname, function (err, data)
	{
		if (err)
		{
			res.writeHead(500);
			return res.end('Error loading ' + url.parse(req.url).pathname);
		}
		res.writeHead(200);
		res.end(data);
	});
}*/

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended: true}));

app.get('/', function(request, response)
{
	response.sendfile(__dirname + '/newsite/login.html');
	//console.log("Using first get");
});
app.get('/bootstrap/*', function(request, response)
{
	response.sendfile('/var/www' + request.path);
	//console.log("Using second get");
});
app.get('/stylesheets/*', function(request, response)
{
	response.sendfile('/var/www' + request.path);
	//console.log("Using third get");
});
app.get('/js/*', function(request, response)
{
	response.sendfile('/var/www/node/newsite/' + request.path);
	//console.log("Using third get");
});
app.get('/home/*', function(request, response)
{
	if(request.path == '/home/new_user.html')
	{
		if(request.session.data.user == 'admin')
		{
			response.sendfile('/var/www/node/newsite/' + request.path);
		}
		else
		{
			response.redirect('/accessdenied');
		}
	}
	else
	{
		if(request.session && request.session.data.user != 'Guest')
		{
			response.sendfile('/var/www/node/newsite/' + request.path);
		}
		else
		{
			response.redirect('/accessdenied');
		}
	}
});
app.get('/login', function(request, response)
{
	//console.log(request.query);
	if(request.query.username && request.query.username != '')
	{
		pool.getConnection(function(err, connection)
		{
			if(err){ throw err;}
			connection.query('SELECT * FROM users WHERE ?', {'username' : request.query.username}, function(err, rows)
			{
				if(err){ throw err;}
				connection.release();
				if(rows.length == 0)
				{
					console.log("No users found with the given username.");
					response.redirect("/");
				}
				else
				{
					request.session.data.user = request.query.username;
					console.log('Session successfully set');
					response.redirect("/home/home.html");
				}
			});
		});		
	}
	else
	{
		response.redirect("/");
	}
});
app.get('/logout', function(request, response)
{
	console.log("Logging out");
	if(request.session)
	{
		var old_user = request.session.data.user;
		request.session.data.user = 'Guest';
		pool.getConnection(function(err, connection)
		{
			if(err) throw err;
			connection.query('SELECT * FROM users WHERE ?', {'username' : old_user}, function(err, rows)
			{
				if(err){ throw err;}
				connection.release();
				if(rows.length == 1)
				{
					var new_row = rows[0];
					pool.getConnection(function(err, conn0)
			    	{
			    		if(err) throw err;
			    		conn0.query("UPDATE `users_login` SET `logged_in`='false' WHERE `uid`=" + new_row.uid + "", function(err, result)
			    		{
			    			if(err) throw err;
			    			conn0.release();
			    			if(result.affectedRows != 1)
			    			{
			    				console.log("error updating login");
			    			}
			    			else
			    			{
			    				console.log("login value set correctly");
			    			}
			    		});
			    	});
				}
				else
				{
					console.log("trouble finding the correct user at logout");
				}
			});
		})
					
	}
	response.redirect("/");
});
app.get('/accessdenied', function(request, response)
{
	if(!request.session || request.session.data.user == 'Guest')
	{
		response.sendfile('/var/www/node/newsite/accessdenied.html');
	}
	else
	{
		response.sendfile('/var/www/node/newsite/accessdenied-internal.html');
	}
});


server.listen(90, function()
{
    console.log('Listening on port %d', server.address().port);
});

var io = require('socket.io').listen(server, { log: false });

io.sockets.on('connection', function (socket)
{
	console.log("Connected to a socket");
	socket.emit('news', { hello: 'world' });
	socket.on('acknowledge', function (data)
	{
		console.log(data);
	});
	socket.on('login', function (data)
	{
		console.log('Checking for username: ' + data.username);
		pool.getConnection(function(err, connection)
		{
			if(err){ throw err;}
			connection.query('SELECT * FROM users WHERE ?', {'username' : data.username}, function(err, rows)
			{
				if(err){ throw err;}
				connection.release();
				if(rows.length == 0)
				{
					console.log("No users found with the given username.");
					socket.emit('login_resp', {'login' : 'false'});
				}
				else if(rows.length == 1)
				{
					var new_row = rows[0];
					bcrypt.compare(data.password, new_row.password, function(err, res)
					{
					    if(res == true)
					    {
					    	console.log("Login Accepted");
					    	socket.emit('login_resp', {'login' : 'true', 'username' : data.username});
					    	pool.getConnection(function(err, conn0)
					    	{
					    		if(err) throw err;
					    		conn0.query("UPDATE `users_login` SET `logged_in`='true', `login_time`='" + getDateTime() + "', `last_login`='" + getDateTime() + "' WHERE `uid`=" + new_row.uid + "", function(err, result)
					    		{
					    			if(err) throw err;
					    			conn0.release();
					    			if(result.affectedRows != 1)
					    			{
					    				console.log("error updating login");
					    			}
					    			else
					    			{
					    				console.log("login value set correctly");
					    			}
					    		});
					    	});
					    }
					    else
					    {
					    	console.log("Login failed. Retry");
					    	socket.emit('login_resp', {'login' : 'false'});
					    }
					});
				}
				else
				{
					console.log("There are multiple users with this username in the DB. This is not good.");
					socket.emit('login_resp', {'login' : 'false'});
				}
			});
		});
	});
	socket.on('add_user', function (data)
	{
		pool.getConnection(function(err, connection)
		{
			if(err){console.log(err); throw err;}
			connection.query('SELECT * FROM users WHERE ?', {'username' : data.username}, function(err, rows)
			{
				if(err){console.log(err); throw err;}
				connection.release();
				if(rows.length == 0)
				{
					bcrypt.genSalt(10, function(err, salt)
					{
						if(err){console.log(err); throw err;}
					    bcrypt.hash(data.password, salt, function(err, hash)
					    {
					    	if(err){console.log(err); throw err;}
					    	var entry = {
					    		'username' : data.username,
				        		'password' : hash,
				        		'salt' : salt,
				        		'date_added' : getDateTime(),
				        		'date_modified' : getDateTime()
					    	}
					        pool.getConnection(function(err, conn)
					        {
					        	if(err){console.log(err); throw err;}
					        	connection.query("INSERT INTO users SET ?", entry,
					        	function(err, result)
					        	{
					        		conn.release();
					        		if(err){console.log(err); throw err;}
					        		console.log("Entries made to database for new user: " + result.affectedRows);
					        		if(result.affectedRows == 1)
					        		{
					        			socket.emit('create_resp', {'created' : 'true'});
					        		}
					        	});
					        });
					    });
					});
				}
				else
				{
					console.log("User with that username already exists, failed to create");
					socket.emit('create_resp', {'created' : 'false'});
				}
			});
		});
	});
});

function getDateTime()
{
	var date;
	date = new Date();
	date = date.getUTCFullYear() + '-' +
	    ('00' + (date.getUTCMonth()+1)).slice(-2) + '-' +
	    ('00' + date.getUTCDate()).slice(-2) + ' ' + 
	    ('00' + date.getUTCHours()).slice(-2) + ':' + 
	    ('00' + date.getUTCMinutes()).slice(-2) + ':' + 
	    ('00' + date.getUTCSeconds()).slice(-2);
	return date;
}
