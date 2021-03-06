var App;

(function() {
	'use strict';

		// disable by default closing modal on backdrop click
	$.extend($.fn.modal.defaults, {backdrop:'static'});

	App = Ember.Application.create({
		LOG_TRANSITIONS: true,
		LOG_STACKTRACE_ON_DEPRECATION: true,
		currentRouteName: '',
		currentPath: '',
		breadcrumbsElements: [],
		customBreadcrumbElements: [],
		refreshCurrentPath: function() {
			var currentController = App.__container__.lookup('controller:'+this.currentRouteName),
				currentRoute = App.__container__.lookup('route:'+this.currentRouteName);
			if (currentController.refresh) {
				currentController.refresh();
			} else if (currentRoute.refresh) {
				currentRoute.refresh();
			} else {
				window.location.reload();
			}
			$('.modal').modal('hide');
		},
		Service: {},
		Socket: null,
		SocketMessageListeners: [],
		SocketConnectAttempts: 0,
		SocketMaxConnectAttempts: 100,
		SocketFirstConnectAttempt: true,
		initializeWebSocket: function() {
			if (!window.WebSocket) {
				App.Service.Notification.showError('Your browser does\'t support websockets. Please upgrade!');
			} else {
				this.SocketConnectAttempts++;

				this.Socket = new WebSocket('ws://'+document.domain+':8000/');
				this.Socket.onopen = function() {
					this.send('auth:'+$.cookie('TYPO3_Flow_Session'));
					if (console) console.log('Connection successfully opened (readyState ' + this.readyState + ') attempts: '+App.SocketConnectAttempts);
					App.SocketConnectAttempts = 0;
					App.SocketFirstConnectAttempt = false;
				};
				this.Socket.onclose = function() {
					if(this.readyState === 2) {
						if (console) console.log(
							'Closing... The connection is going through the closing handshake (readyState ' + this.readyState + ')'
						);
					} else if(this.readyState === 3) {
						if(App.SocketFirstConnectAttempt) {
							// ping websocketserver when we dont get a response at first attempt
							$.get('/Beech.ehrm/application/pingWebSocketServer');
							App.SocketFirstConnectAttempt = false;
						}
						if(App.SocketConnectAttempts == 0) {
							App.Service.Notification.showWarning('Connection to the server has been lost trying to reconnect');
							console.log(this)
						}
						if(App.SocketConnectAttempts < App.SocketMaxConnectAttempts) {
							setTimeout('App.initializeWebSocket()', 1000);
						} else {
							App.Service.Notification.showError('Connection to the server has been lost or could not be opened');
						}
					} else {
						if (console) console.log('Connection closed... (unhandled readyState ' + this.readyState + ')');
					}
				};
				this.Socket.onerror = function(event) {
					if (console) console.log('Socket error: ',event);
				};
				this.Socket.onmessage = function(event) {
					App.SocketMessageListeners.forEach(function(item) {
						item.call(this, event);
					});
				};
			}
		},

		ready: function() {
			this.initializeWebSocket();

			if (MM.init.afterInitialize) {
				for (var i in MM.init.afterInitialize) {
					if (i.match(/^[0-9]*$/)) {
						MM.init.afterInitialize[i].call();
					}
				}
			}

		}
	});

	App.deferReadiness();

	$(document).ready(function () {
		if (MM.init.onLoad) {
			for (var i in MM.init.onLoad) {
				if (i.match(/^[0-9]*$/)) {
					MM.init.onLoad[i].call();
				}
			}
		}

		App.advanceReadiness();
	});
}).call(this);